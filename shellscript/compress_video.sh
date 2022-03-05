#!/bin/bash

# Author: James Bolongan
# Date Created: 10-28-2019
# Description: Bash Script that will compress and convert(single) video to different resolutions(480, 720, 1080, original)
# Requirements: "ffmpeg", "aws s3" must be properly configured and installed
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

ffmpeg_compress() {
    local filename=${1}
    local basename=${2}
    local extension=${3}
    local resolution=${4}
    local video_id=${5}
    local video_path=${6}
    local token=${7}

    newfilename=$(echo "$basename""_${resolution}.${extension}");

	if [[ $resolution == 'default' ]] ; then
		ffmpeg -y -i ${file_name} -c:v libx264 -crf 28 -preset veryfast -profile:v baseline -level 3.0 -strict -2 ${newfilename}
	else
		ffmpeg -y -i ${filename} -s hd${resolution} -c:v libx264 -crf 28 -c:a aac -strict -2 ${newfilename}
	fi

	upload_result=$(aws s3 cp /var/vhosts/videos/dev/${video_id}/${newfilename} s3://songwriter-sunday-school-test/videos/${video_id}/${newfilename} --acl public-read)

	strpos "${upload_result}" "upload:"
	if [ $res > -1 ]
	then
		curl -v -X PUT https://dev.allthingsmichaelmclean.com/api/cronvideos/${video_id} \
			-H "Content-Type: application/json" \
			-H "Authorization: Bearer $token"  \
			-d '{ "video_'${resolution}'": "videos/'${video_id}'/'${newfilename}'"}'
		remove_video=$(cd /var/vhosts/videos/dev/${video_id}/ && rm ${newfilename})
	fi
}

if pgrep -x "ffmpeg" > /dev/null
then
    echo "FFMPEG currently compressing some videos. Can't execute another ffmpeg task."
else

	gettoken=$( curl -v -X POST https://dev.allthingsmichaelmclean.com/api/login \
		-H "Content-Type: application/json" \
		-d '{ "email": "devapi@songwriter.com","password" : "tc123456" }' )

	error=$(echo "$gettoken" | jq -j '.error');
	if [[ $error == 'Unauthorized' ]] ; then
	     echo "Unauthorized."
	else

		token=$(echo "$gettoken" | jq -j '.token');
		video=$( curl -v -X GET https://dev.allthingsmichaelmclean.com/api/cronsinglevideo-other-server \
			-H "Content-Type: application/json" \
			-H "Authorization: Bearer $token" )

		if [ -z "$video" ]
		then
		      echo "No video need to convert."
		else
			video_path=$(echo "$video" | jq -j '.video');
			file_name=$(echo "$video" | jq -j '.file_name');
			video_id=$(echo "$video" | jq -j '.id');
			video_480=$(echo "$video" | jq -j '.video_480');
			video_720=$(echo "$video" | jq -j '.video_720');
			video_1080=$(echo "$video" | jq -j '.video_1080');
			video_default=$(echo "$video" | jq -j '.video_default');

			SOURCEFILE=/var/vhosts/videos/dev/${video_id}/${file_name}
			if [[ -f "$SOURCEFILE" ]] ; then
				cd /var/vhosts/videos/dev/${video_id}/

				filename=$(basename -- "$SOURCEFILE")
				extension="${filename##*.}"
				base_name="${filename%.*}"

				if [[ $video_480 == '' || $video_480 == null ]] ; then
					ffmpeg_compress "${file_name}" "${base_name}" "${extension}" "480" "${video_id}" "${video_path}" "${token}"
				elif [[ $video_720 == '' || $video_720 == null ]] ; then
					ffmpeg_compress "${file_name}" "${base_name}" "${extension}" "720" "${video_id}" "${video_path}" "${token}"
				elif [[ $video_1080 == '' || $video_1080 == null ]] ; then
					ffmpeg_compress "${file_name}" "${base_name}" "${extension}" "1080" "${video_id}" "${video_path}" "${token}"
				elif [[ $video_default == '' || $video_default == null ]] ; then
					ffmpeg_compress "${file_name}" "${base_name}" "${extension}" "default" "${video_id}" "${video_path}" "${token}"
				else
					echo "No video resolution found need to convert."
				fi

			fi
		fi

	fi

fi


<<LIVE
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

ffmpeg_compress() {
    local filename=${1}
    local basename=${2}
    local extension=${3}
    local resolution=${4}
    local video_id=${5}
    local video_path=${6}
    local token=${7}

    newfilename=$(echo "$basename""_${resolution}.${extension}");

	if [[ $resolution == 'default' ]] ; then
		ffmpeg -y -i ${file_name} -c:v libx264 -crf 28 -preset veryfast -profile:v baseline -level 3.0 -strict -2 ${newfilename}
	else
		ffmpeg -y -i ${filename} -s hd${resolution} -c:v libx264 -crf 28 -c:a aac -strict -2 ${newfilename}
	fi

	upload_result=$(aws s3 cp /var/vhosts/videos/live/${video_id}/${newfilename} s3://songwriter-sunday-school/videos/${video_id}/${newfilename} --acl public-read)

	strpos "${upload_result}" "upload:"
	if [ $res > -1 ]
	then
		curl -v -X PUT https://allthingsmichaelmclean.com/api/cronvideos/${video_id} \
			-H "Content-Type: application/json" \
			-H "Authorization: Bearer $token"  \
			-d '{ "video_'${resolution}'": "videos/'${video_id}'/'${newfilename}'"}'
		remove_video=$(cd /var/vhosts/videos/live/${video_id}/ && rm ${newfilename})
	fi
}

if pgrep -x "ffmpeg" > /dev/null
then
    echo "FFMPEG currently compressing some videos. Can't execute another ffmpeg task."
else

	gettoken=$( curl -v -X POST https://allthingsmichaelmclean.com/api/login \
		-H "Content-Type: application/json" \
		-d '{ "email": "devapi@songwriter.com","password" : "tc123456" }' )

	error=$(echo "$gettoken" | jq -j '.error');
	if [[ $error == 'Unauthorized' ]] ; then
	     echo "Unauthorized."
	else

		token=$(echo "$gettoken" | jq -j '.token');
		video=$( curl -v -X GET https://allthingsmichaelmclean.com/api/cronsinglevideo-other-server \
			-H "Content-Type: application/json" \
			-H "Authorization: Bearer $token" )

		if [ -z "$video" ]
		then
		      echo "No video need to convert."
		else
			video_path=$(echo "$video" | jq -j '.video');
			file_name=$(echo "$video" | jq -j '.file_name');
			video_id=$(echo "$video" | jq -j '.id');
			video_480=$(echo "$video" | jq -j '.video_480');
			video_720=$(echo "$video" | jq -j '.video_720');
			video_1080=$(echo "$video" | jq -j '.video_1080');
			video_default=$(echo "$video" | jq -j '.video_default');

			SOURCEFILE=/var/vhosts/videos/live/${video_id}/${file_name}
			if [[ -f "$SOURCEFILE" ]] ; then
				cd /var/vhosts/videos/live/${video_id}/

				filename=$(basename -- "$SOURCEFILE")
				extension="${filename##*.}"
				base_name="${filename%.*}"

				if [[ $video_480 == '' || $video_480 == null ]] ; then
					ffmpeg_compress "${file_name}" "${base_name}" "${extension}" "480" "${video_id}" "${video_path}" "${token}"
				elif [[ $video_720 == '' || $video_720 == null ]] ; then
					ffmpeg_compress "${file_name}" "${base_name}" "${extension}" "720" "${video_id}" "${video_path}" "${token}"
				elif [[ $video_1080 == '' || $video_1080 == null ]] ; then
					ffmpeg_compress "${file_name}" "${base_name}" "${extension}" "1080" "${video_id}" "${video_path}" "${token}"
				elif [[ $video_default == '' || $video_default == null ]] ; then
					ffmpeg_compress "${file_name}" "${base_name}" "${extension}" "default" "${video_id}" "${video_path}" "${token}"
				else
					echo "No video resolution found need to convert."
				fi

			fi
		fi

	fi

fi
LIVE

<<LOCAL
ffmpeg_compress() {
    local filename=${1}
    local basename=${2}
    local extension=${3}
    local resolution=${4}
    local video_id=${5}
    local video_path=${6}
    local token=${7}

    newfilename=$(echo "$basename""_${resolution}.${extension}");

	if [[ $resolution == 'default' ]] ; then
		ffmpeg -y -i ${file_name} -c:v libx264 -crf 28 -preset veryfast -profile:v baseline -level 3.0 -strict -2 ${newfilename}
	else
		ffmpeg -i ${filename} -s hd${resolution} -c:v libx264 -crf 28 -c:a aac -strict -2 ${newfilename}
	fi

	cp ${newfilename} /home/jamesbbolongan/Desktop/fordelete/testconverted/

	CHECKFILE=/home/jamesbbolongan/Desktop/fordelete/testconverted/${newfilename}
	if [[ -f "$CHECKFILE" ]] ; then
		curl -v -X PUT http://songwritersundayschool.local/api/cronvideos/${video_id} \
			-H "Content-Type: application/json" \
			-H "Authorization: Bearer $token"  \
			-d '{ "video_'${resolution}'": "'${newfilename}'"}'
		remove_video=$(cd /home/jamesbbolongan/Desktop/fordelete/testconverted/ && rm ${newfilename})
	fi
}

if pgrep -x "ffmpeg" > /dev/null
then
    echo "FFMPEG currently compressing some videos. Can't execute another ffmpeg task."
else

	gettoken=$( curl -v -X POST http://songwritersundayschool.local/api/login \
		-H "Content-Type: application/json" \
		-d '{ "email": "devapi@songwriter.com","password" : "tc123456" }' )

	error=$(echo "$gettoken" | jq -j '.error');
	if [[ $error == 'Unauthorized' ]] ; then
	     echo "Unauthorized."
	else

		token=$(echo "$gettoken" | jq -j '.token');
		video=$( curl -v -X GET http://songwritersundayschool.local/api/cronsinglevideo-other-server \
			-H "Content-Type: application/json" \
			-H "Authorization: Bearer $token" )

		if [ -z "$video" ]
		then
		      echo "No video need to convert."
		else
			video_path=$(echo "$video" | jq -j '.video');
			file_name=$(echo "$video" | jq -j '.file_name');
			video_id=$(echo "$video" | jq -j '.id');
			video_480=$(echo "$video" | jq -j '.video_480');
			video_720=$(echo "$video" | jq -j '.video_720');
			video_1080=$(echo "$video" | jq -j '.video_1080');
			video_default=$(echo "$video" | jq -j '.video_default');

			SOURCEFILE=/home/jamesbbolongan/Desktop/fordelete/${file_name}
			if [[ -f "$SOURCEFILE" ]] ; then
				cd /home/jamesbbolongan/Desktop/fordelete/

				filename=$(basename -- "$SOURCEFILE")
				extension="${filename##*.}"
				base_name="${filename%.*}"

				if [[ $video_480 == '' || $video_480 == null ]] ; then
					ffmpeg_compress "${file_name}" "${base_name}" "${extension}" "480" "${video_id}" "${video_path}" "${token}"
				elif [[ $video_720 == '' || $video_720 == null ]] ; then
					ffmpeg_compress "${file_name}" "${base_name}" "${extension}" "720" "${video_id}" "${video_path}" "${token}"
				elif [[ $video_1080 == '' || $video_1080 == null ]] ; then
					ffmpeg_compress "${file_name}" "${base_name}" "${extension}" "1080" "${video_id}" "${video_path}" "${token}"
				elif [[ $video_default == '' || $video_default == null ]] ; then
					ffmpeg_compress "${file_name}" "${base_name}" "${extension}" "default" "${video_id}" "${video_path}" "${token}"
				else
					echo "No video resolution found need to convert."
				fi

			fi
		fi
	fi
fi
LOCAL
