#!/bin/sh

PWD=`dirname $0`
BASEPATH=`basename $PWD`

GIT=git
VIRTUALENV="virtualenv --distribute"
PIP="pip --timeout 30 -q"
ENVDIR=env

if [ ! -d $ENVDIR ]; then
    echo "Preparing virtualenv environment in $ENVDIR directory"
    
    if pip install virtualenv; then
        echo 'VirtualEnv installed allright'
    else
        echo 'Error installing VirtualEnv, breaking off'
        exit 1
    fi
    
    $VIRTUALENV $ENVDIR
fi
        
echo 'Installing required packages'
if pip install -E $ENVDIR -r requirements.txt; then
    echo 'That went allright, continue'
else
    echo 'Error installing dependencies, breaking off'
    exit 1
fi

if [ ! -f portnumber ]; then
    echo 'Port number not set.'
    while [ -z "$PORTNUMBER"  ]; do
        read 'Which port number should the builtin server listen to? ' PORTNUMBER
        echo
        echo "Setting default port number to: $PORTNUMBER"
        echo
    done
    
    echo $PORTNUMBER > portnumber
fi

if [ ! -f settings_local.py ]; then
    echo 'No local settings file found.'
    cp -v settings_local.py.example settings_local.py
    
    echo
    echo 'Generating secret key in settings_local.py.'
    SECRET_KEY=`./runserver.sh generate_secret_key`
    echo >> settings_local.py
    echo "# Make this unique, and don't share it with anybody." >> settings_local.py
    echo "SECRET_KEY = '$SECRET_KEY'" >> settings_local.py
fi

if [ ! -f database.sqlite ]; then
    echo 'No database found, running syncdb.'
    ./runserver.sh syncdb
fi
