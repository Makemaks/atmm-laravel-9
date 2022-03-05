#!/bin/bash

# Author: James Bolongan
# Date Created: 07-19-2020
# Description: Bash Script that will do an API call to SSS to auto update the nmitransaction table


gettoken=$( curl -v -X POST http://songwritersundayschool.local/api/login \
	-H "Content-Type: application/json" \
	-d '{ "email": "devapi@songwriter.com","password" : "tc123456" }' )

error=$(echo "$gettoken" | jq -j '.error');
if [[ $error == 'Unauthorized' ]] ; then
     echo "Unauthorized."
else
	token=$(echo "$gettoken" | jq -j '.token');
	result=$( curl -v -X GET http://songwritersundayschool.local/api/nmitransactions \
		-H "Content-Type: application/json" \
		-H "Authorization: Bearer $token" )
	echo "$result"

fi



<<DEV
gettoken=$( curl -v -X POST https://dev.allthingsmichaelmclean.com/api/login \
	-H "Content-Type: application/json" \
	-d '{ "email": "devapi@songwriter.com","password" : "tc123456" }' )

error=$(echo "$gettoken" | jq -j '.error');
if [[ $error == 'Unauthorized' ]] ; then
     echo "Unauthorized."
else
	token=$(echo "$gettoken" | jq -j '.token');
	result=$( curl -v -X GET https://dev.allthingsmichaelmclean.com/api/nmitransactions \
		-H "Content-Type: application/json" \
		-H "Authorization: Bearer $token" )
	echo "$result"

fi
DEV

<<LIVE
gettoken=$( curl -v -X POST https://allthingsmichaelmclean.com/api/login \
	-H "Content-Type: application/json" \
	-d '{ "email": "devapi@songwriter.com","password" : "tc123456" }' )

error=$(echo "$gettoken" | jq -j '.error');
if [[ $error == 'Unauthorized' ]] ; then
     echo "Unauthorized."
else
	token=$(echo "$gettoken" | jq -j '.token');
	result=$( curl -v -X GET https://allthingsmichaelmclean.com/api/nmitransactions \
		-H "Content-Type: application/json" \
		-H "Authorization: Bearer $token" )
	echo "$result"

fi
LIVE
