#!/bin/sh

i=1
while [ $i -lt 1000 ]; do
	if [ ! -x data/`printf %03d ${i}`.json ]; then
		./cbpscraper.py `printf %03d ${i}` > data/`printf %03d ${i}`.log
	fi
	let i=i+1
done
