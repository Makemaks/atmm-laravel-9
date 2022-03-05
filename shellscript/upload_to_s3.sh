#!/bin/bash

# Author: James Bolongan
# Date Created: 10-07-2019
# Description: bash script that will upload video files to AWS S3 bucket
# Requirements: "aws s3" must be properly configured and installed. 
# Notes: This script will run only on AWS servers. Executing this shell sccript outside AWS server will not execute. And this script is not part of Laravel framework.

strpos() 
{
    local str=${1}
    local offset=${3}

    if [ -n "${offset}" ]; then
        str=`substr "${str}" ${offset}`
    else
        offset=0
    fi

    str=${str/${2}*/}

    if [ "${#str}" -eq "${#1}" ]; then
        return 0
    fi

   	res=$((${#str}+${offset}));
}

get_unuploaded_video=$(echo "SELECT CONCAT(id,' ',file_size) AS id, file_name   FROM video_details WHERE video is NULL OR video = '' ORDER BY id ASC;" | mysql -umzwgakdsxm -pEens9V2tuU -hlocalhost mzwgakdsxm)
count_unuploaded=$(echo "SELECT COUNT(id) as count FROM video_details WHERE video is NULL OR video = '';" | mysql -umzwgakdsxm -pEens9V2tuU -hlocalhost mzwgakdsxm)

replace=""
count=$(echo ${count_unuploaded//count/$replace})

if [[ $count > 0 ]] 
then
	replace=""
	result=$(echo ${get_unuploaded_video//file_name/$replace})

    counter_ids=0
    counter_fnames=0
    counter_fize=0
    ids=()
    filesizes=()
    filenames=()
	for item in ${result[@]}; 
		do 

			if [[ $item != "id" && $item != "file_name" ]]
			then

				if [ -n "$(printf '%s\n' "$item" | sed 's/[0-9]//g')" ] 
				then

				    filenames[counter_fnames]=$item
				    counter_fnames=$(expr $counter_fnames + 1) 

				else

					IFS=' ' 
					read -ra idfilesize <<< "$item" 

					ids[counter_ids]=${idfilesize[0]}
					counter_ids=$(expr $counter_ids + 1)

					filesizes[counter_fize]=${idfilesize[0]}
					counter_fize=$(expr $counter_fize + 1)

				fi
			fi
	done

    new_counter_ids=0
    new_ids=()
    new_counter_sizes=0
    new_sizes=()

	len=${#ids[@]}
	for (( i=0; i<$len; i++ )); 
		do 
			if [[ $i != "" ]] 
			then
				
				if [[ $(( $i  % 2 )) == 0 ]] 
				then
					
					new_ids[new_counter_ids]=${ids[i]}
					new_counter_ids=$(expr $new_counter_ids + 1)

				else
					
					new_sizes[new_counter_sizes]=${ids[i]}
					new_counter_sizes=$(expr $new_counter_sizes + 1)
				fi
			fi
	done

	######################################### start SCAN and CHECK directories for reference ##################################################
	dir_videos=$(cd /var/www/songwritersundayschool.com/livesongwritersundayschool/storage/videos && ls -l | tr -s ' ' | cut -d' ' -f5,9-)

    dir_counter_file_size=0
    dir_counter_file_name=0
    dir_file_sizes=()
    dir_file_names=()
	for item in ${dir_videos[@]}; 
		do 
			if [ -n "$(printf '%s\n' "$item" | sed 's/[0-9]//g')" ] 
			then
			    dir_file_names[dir_counter_file_name]=$item
			    dir_counter_file_name=$(expr $dir_counter_file_name + 1)	
			else
			    dir_file_sizes[dir_counter_file_size]=$item
			    dir_counter_file_size=$(expr $dir_counter_file_size + 1)
			fi
	done
	######################################### end SCAN and CHECK directories for reference ##################################################

	len=${#filenames[@]}
	for (( i=0; i<$len; i++ )); 
		do 
			if [[ $i != "" ]] 
			then

				if echo ${dir_file_names[@]} | grep -q -w "${new_sizes[$i]} ${filenames[$i]}" ; then 

					###### live ######
					upload_result=$(aws s3 cp /var/www/songwritersundayschool.com/livesongwritersundayschool/storage/videos/${filenames[$i]} s3://songwriter-sunday-school/videos/${new_ids[$i]}/${filenames[$i]})

					###### dev ######
					#upload_result=$(aws s3 cp /var/www/songwritersundayschool.com/devsongwritersundayschool/storage/videos/${filenames[$i]} s3://songwriter-sunday-school-test/videos/${new_ids[$i]}/${filenames[$i]})

					strpos "${upload_result}" "upload:"
					if [ $res > -1 ] 
					then
						echo "upload success";
						var="UPDATE video_details SET video = 'videos/${new_ids[$i]}/${filenames[$i]}', uploaded_via = 'cron' WHERE id = '${new_ids[$i]}' ;"
						update_video_column=$(echo $var | mysql -umzwgakdsxm -pEens9V2tuU -hlocalhost mzwgakdsxm)
						remove_video=$(cd /var/www/songwritersundayschool.com/livesongwritersundayschool/storage/videos && rm ${filenames[$i]})
					fi

				else 
				    echo "cant find the video on the upload directory"
				fi

			fi
	done

else
	echo "no video files need to upload";
fi