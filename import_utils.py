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

CITY_TRANS = {
"'S-GRAVENHAGE": "Den Haag",
"'S-HERTOGENBOSCH": "Den Bosch",
"EDE GLD": 'Ede',
"HENGELO OV": 'Hengelo',
"VELP GLD": 'Velp',
}

def normalise_city(name):
    if name in CITY_TRANS:
        return CITY_TRANS[name]

    return name.title()

def create_organisation(**context):
    o = Organisation(country=None)

    field_list = ['addressee', 'postcode', 'address', 'website' ]

    for field in field_list:
        if field in context:
            setattr(o, field, context[field])

    assert len(context['name']) > 0
    o.setname(context['name'])

    try:
        citizenrole = CitizenRole.objects.get(name = context['citizenrole'])
    except CitizenRole.DoesNotExist:
        citizenrole = CitizenRole()
        citizenrole.name = context['citizenrole']
        citizenrole.save()

    try:
        city = City.objects.get(name = context['city'])
    except City.DoesNotExist:
        print "Creating city: " + context['city']
        city = City()
        city.setname(context['city'])
        city.save()

    try:
        country = Country.objects.get(name = 'Nederland')
    except Country.DoesNotExist:
        assert 0, "Country 'Nederland' should exist in the Database"

    # Save, so a primary key will be generated (needed for m2m relations)
    o.save()

    o.city = city
    o.country = country
    o.citizenrole.add(citizenrole)

    o.save()
