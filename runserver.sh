#!/bin/sh

cd `dirname $0`

PWD=`pwd`
BASEPATH=`basename $PWD`
PYTHON=python
SCREEN=screen
ENVDIR=env
HOSTNAME=`hostname`
PORTNUMBER=8000

if [ -d $ENVDIR ]; then
    echo 'Using python from virtualenv environment' >&2
    PYTHON=$ENVDIR/bin/python
fi

[ -f hostname ] && HOSTNAME=`cat hostname`
[ -f portnumber ] && PORTNUMBER=`cat portnumber`

if [ $1 ]; then
    $PYTHON manage.py $*
else
    $SCREEN -S $BASEPATH $PYTHON manage.py runserver $HOSTNAME:$PORTNUMBER
fi

