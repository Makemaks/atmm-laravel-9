#!/bin/bash

# Author: James Bolongan
# Date Created: 10-25-2019
# Description: Bash Script that will download a file from S3 bucket for those videos that have not yet transferred to the second server
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
	videos=$( curl -v -X GET https://dev.allthingsmichaelmclean.com/api/cronvideos \
		-H "Content-Type: application/json" \
		-H "Authorization: Bearer $token" )

	echo "$videos" | jq -c '.[]' |
	while IFS= read -r line;
		do
			video_path=$(echo "$line" | jq -j '.video, "\n"');
			file_name=$(echo "$line" | jq -j '.file_name, "\n"');
			video_id=$(echo "$line" | jq -j '.id, "\n"');

			aws s3 cp s3://songwriter-sunday-school-test/${video_path} /var/vhosts/videos/dev/${video_id}/

			CHECKFILE=/var/vhosts/videos/dev/${video_id}/${file_name}
			if [[ -f "$CHECKFILE" ]] ; then
				curl -v -X PUT https://dev.allthingsmichaelmclean.com/api/cronvideos/${video_id} \
					-H "Content-Type: application/json" \
					-H "Authorization: Bearer $token"  \
					-d '{ "iscopiedinsecondserver": "1"}'
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
	videos=$( curl -v -X GET https://allthingsmichaelmclean.com/api/cronvideos \
		-H "Content-Type: application/json" \
		-H "Authorization: Bearer $token" )

	echo "$videos" | jq -c '.[]' |
	while IFS= read -r line;
		do
			video_path=$(echo "$line" | jq -j '.video, "\n"');
			file_name=$(echo "$line" | jq -j '.file_name, "\n"');
			video_id=$(echo "$line" | jq -j '.id, "\n"');

			aws s3 cp s3://songwriter-sunday-school/${video_path} /var/vhosts/videos/live/${video_id}/

			CHECKFILE=/var/vhosts/videos/live/${video_id}/${file_name}
			if [[ -f "$CHECKFILE" ]] ; then
				curl -v -X PUT https://allthingsmichaelmclean.com/api/cronvideos/${video_id} \
					-H "Content-Type: application/json" \
					-H "Authorization: Bearer $token"  \
					-d '{ "iscopiedinsecondserver": "1"}'
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
	videos=$( curl -v -X GET http://songwritersundayschool.local/api/cronvideos \
		-H "Content-Type: application/json" \
		-H "Authorization: Bearer $token" )
	echo "$videos" | jq -c '.[]' |
	while IFS= read -r line;
		do
			video_path=$(echo "$line" | jq -j '.video, "\n"');
			file_name=$(echo "$line" | jq -j '.file_name, "\n"');
			video_id=$(echo "$line" | jq -j '.id, "\n"');

			SOURCEFILE=/home/jamesbbolongan/applications/sssnew/sss/storage/videos/${file_name}
			if [[ -f "$SOURCEFILE" ]] ; then
				cp /home/jamesbbolongan/applications/sssnew/sss/storage/videos/${file_name} /home/jamesbbolongan/Desktop/fordelete
			fi

			CHECKFILE=/home/jamesbbolongan/Desktop/fordelete/${file_name}
			if [[ -f "$CHECKFILE" ]] ; then
				curl -v -X PUT http://songwritersundayschool.local/api/cronvideos/${video_id} \
					-H "Content-Type: application/json" \
					-H "Authorization: Bearer $token"  \
					-d '{ "iscopiedinsecondserver": "1"}'
			fi
	done
fi
LOCAL
