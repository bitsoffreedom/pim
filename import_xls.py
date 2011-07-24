import os
os.environ["DJANGO_SETTINGS_MODULE"] = "settings"
import django
from django.core.management import setup_environ
import settings
from pimbase.models import *
setup_environ(settings)
import xlrd

wb = xlrd.open_workbook('companies.xls')
sh = wb.sheet_by_index(0)

# Column mapping
NAAM = 0
STRAAT = 1
POSTCODE = 2
PLAATS = 3
WEBSITE = 4
SOORT = 5

try:
    country = Country.objects.get(name='Nederland')
except Country.DoesNotExist:
    assert False, "The 'Nederland' object should already exist."
    country = Country()
    country.setname('Nederland')

country.save()

# Skip the first row, it holds metadata.
for rownum in range(1, sh.nrows):
    o = Organisation()
    o.name = sh.row_values(rownum)[NAAM]
    o.shortname = sh.row_values(rownum)[NAAM]
    o.address = sh.row_values(rownum)[STRAAT]
    o.postcode = sh.row_values(rownum)[POSTCODE]

    try:
        city = City.objects.get(name=sh.row_values(rownum)[PLAATS])
    except City.DoesNotExist:
        city = City()
        city.setname(sh.row_values(rownum)[PLAATS])
    city.save()

    o.city = city
    o.country = country

    o.website = sh.row_values(rownum)[WEBSITE]

    # Save, so we can use the m2m relationship.
    o.save()

    try:
        citizenrole = CitizenRole.objects.get(label = sh.row_values(rownum)[SOORT])
    except CitizenRole.DoesNotExist:
        citizenrole = CitizenRole()
        citizenrole.name =  sh.row_values(rownum)[SOORT]
        citizenrole.label = sh.row_values(rownum)[SOORT]
    citizenrole.save()

    o.citizenrole.add(citizenrole)

    o.save()

