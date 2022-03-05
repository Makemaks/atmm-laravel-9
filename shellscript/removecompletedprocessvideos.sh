#!/bin/bash

# Author: James Bolongan
# Date Created: 11-02-2019
# Description: Bash Script that will remove the video on the second server and in S3 bucket if the video completely converted. Need to remove the video to open/free some disk space both in the second Linux Server and S3 bucket.
# Requirements: "aws s3" must be properly configured and installed
# Notes: This script will run only on AWS servers. Executing this shell sccript outside AWS server will not execute. And this script is not part of Laravel framework.

gettoken=$( curl -v -X POST https://dev.allthingsmichaelmclean.com/api/login \
	-H "Content-Type: application/json" \
	-d '{ "email": "devapi@songwriter.com","password" : "tc123456" }' )

error=$(echo "$gettoken" | jq -j '.error');
if [[ $error == 'Unauthorized' ]] ; then
     echo "Unauthorized."
else
	token=$(echo "$gettoken" | jq -j '.token');
	videos=$( curl -v -X GET https://dev.allthingsmichaelmclean.com/api/get-completed-process-videos \
		-H "Content-Type: application/json" \
		-H "Authorization: Bearer $token" )
	echo "$videos" | jq -c '.[]' |
	while IFS= read -r line;
		do
			video_path=$(echo "$line" | jq -j '.video, "\n"');
			file_name=$(echo "$line" | jq -j '.file_name, "\n"');
			video_id=$(echo "$line" | jq -j '.id, "\n"');

			CHECKFILE=/var/vhosts/videos/dev/${video_id}/${file_name}
			if [[ -f "$CHECKFILE" ]] ; then
				remove_video=$(cd /var/vhosts/videos/dev/${video_id}/ && rm ${file_name})
				aws s3 rm s3://songwriter-sunday-school-test/videos/${video_id}/${file_name}
			fi
	done
fi

<<LIVE
gettoken=$( curl -v -X POST https://allthingsmichaelmclean.com/api/login \
	-H "Content-Type: application/json" \
	-d '{ "email": "devapi@songwriter.com","password" : "tc123456" }' )

error=$(echo "$gettoken" | jq -j '.error');
if [[ $error == 'Unauthorized' ]] ; then
     echo "Unauthorized."
else
	token=$(echo "$gettoken" | jq -j '.token');
	videos=$( curl -v -X GET https://allthingsmichaelmclean.com/api/get-completed-process-videos \
		-H "Content-Type: application/json" \
		-H "Authorization: Bearer $token" )
	echo "$videos" | jq -c '.[]' |
	while IFS= read -r line;
		do
			video_path=$(echo "$line" | jq -j '.video, "\n"');
			file_name=$(echo "$line" | jq -j '.file_name, "\n"');
			video_id=$(echo "$line" | jq -j '.id, "\n"');

			CHECKFILE=/var/vhosts/videos/live/${video_id}/${file_name}
			if [[ -f "$CHECKFILE" ]] ; then
				remove_video=$(cd /var/vhosts/videos/live/${video_id}/ && rm ${file_name})
				aws s3 rm s3://songwriter-sunday-school/videos/${video_id}/${file_name}
			fi
	done
fi
LIVE

<<LOCAL
gettoken=$( curl -v -X POST http://songwritersundayschool.local/api/login \
	-H "Content-Type: application/json" \
	-d '{ "email": "devapi@songwriter.com","password" : "tc123456" }' )

error=$(echo "$gettoken" | jq -j '.error');
if [[ $error == 'Unauthorized' ]] ; then
     echo "Unauthorized."
else
	token=$(echo "$gettoken" | jq -j '.token');
	videos=$( curl -v -X GET http://songwritersundayschool.local/api/get-completed-process-videos \
		-H "Content-Type: application/json" \
		-H "Authorization: Bearer $token" )
	echo "$videos" | jq -c '.[]' |
	while IFS= read -r line;
		do
			video_path=$(echo "$line" | jq -j '.video, "\n"');
			file_name=$(echo "$line" | jq -j '.file_name, "\n"');
			video_id=$(echo "$line" | jq -j '.id, "\n"');

			CHECKFILE=/home/jamesbbolongan/Desktop/fordelete/testconverted/${file_name}
			if [[ -f "$CHECKFILE" ]] ; then
				remove_video=$(cd /home/jamesbbolongan/Desktop/fordelete/testconverted/ && rm ${file_name})
			fi
	done
fi
LOCAL
