#!/usr/bin/env python

import os
os.environ["DJANGO_SETTINGS_MODULE"] = "settings"
import sys
import glob

from pprint import pprint

import django
from django.core.management import setup_environ
import settings
from pimbase.models import *
setup_environ(settings)

import csv

from import_utils import create_organisation, normalise_city

f = open('import.csv', 'r')
c = csv.reader(f, delimiter=';', quoting=csv.QUOTE_NONE)

# Used to generate the following...
#for r in c:
#    i=0
#    for b in r:
#        print "%s=%d" % (b.replace(" ", "").replace("-", ""), i )
#        i += 1
#
#    break

SOORTHO=0
PROVINCIE=1
BEVOEGDGEZAGNUMMER=2
BRINNUMMER=3
INSTELLINGSNAAM=4
STRAATNAAM=5
HUISNUMMERTOEVOEGING=6
POSTCODE=7
PLAATSNAAM=8
GEMEENTENUMMER=9
GEMEENTENAAM=10
DENOMINATIE=11
TELEFOONNUMMER=12
INTERNETADRES=13
STRAATNAAMCORRESPONDENTIEADRES=14
HUISNUMMERTOEVOEGINGCORRESPONDENTIEADRES=15
POSTCODECORRESPONDENTIEADRES=16
PLAATSNAAMCORRESPONDENTIEADRES=17
NODAALGEBIEDCODE=18
NODAALGEBIEDNAAM=19
RPAGEBIEDCODE=20
RPAGEBIEDNAAM=21
WGRGEBIEDCODE=22
WGRGEBIEDNAAM=23
COROPGEBIEDCODE=24
COROPGEBIEDNAAM=25
ONDERWIJSGEBIEDCODE=26
ONDERWIJSGEBIEDNAAM=27
RMCREGIOCODE=28
RMCREGIONAAM=29


def insert_school(r):
    context = {}
    context['name'] = r[INSTELLINGSNAAM]
    context['address'] = "%s %s" % (r[STRAATNAAMCORRESPONDENTIEADRES], r[HUISNUMMERTOEVOEGINGCORRESPONDENTIEADRES])
    context['website'] = r[INTERNETADRES]
    context['postcode'] = r[POSTCODECORRESPONDENTIEADRES]
    context['city'] = normalise_city(r[PLAATSNAAMCORRESPONDENTIEADRES])
    context['citizenrole'] = 'opleiding'

    create_organisation(**context)

for r in c:
    if r[SOORTHO] == "hbo":
        insert_school(r)
