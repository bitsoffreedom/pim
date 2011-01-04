#!/bin/sh

i=99
while [ $i -lt 1000 ]; do
	if [ -e data/`printf %03d ${i}`.log ]; then
		echo "done"
	else
		echo Scraping data/`printf %03d ${i}`.json
		./cbpscraper.py `printf %03d ${i}` > data/`printf %03d ${i}`.log
	fi
	let i=i+1
done
