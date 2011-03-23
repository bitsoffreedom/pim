import os
os.environ["DJANGO_SETTINGS_MODULE"] = "settings"
import sys
import glob
import json

from pprint import pprint

import django
from django.core.management import setup_environ
import settings
from pimbase.models import *
setup_environ(settings)

from cbphex import *

if len(sys.argv) == 2:
	flist = glob.glob(os.path.join(sys.argv[1], "*.json"))
else:
	flist = glob.glob("/mnt/ramdisk/data/*.json")
i = 0
for f in flist:
    i = i + 1
    print "%d of %d (%s)" % (i, len(flist) * 2, f)
    companies = json.load(open(f))
    for c in companies:
        o = Organisation(country=None)
        o.name = c["name"]
        o.website = c["url"]
        url_dec = url_decode(c["url"])
        if (url_dec[2]):
            o.postcode = url_dec[2]
        else:
            o.postcode = url_dec[1]
        o.save()
#        for mid in c["meldingen"].keys():
#            if c["meldingen"][mid].has_key("betrokkenen"):
#                for b in c["meldingen"][mid]["betrokkenen"].keys():
#                    try:
#                        collected = CollectedInformation.objects.get(name=b)
#                    except CollectedInformation.DoesNotExist:
#                        collected = CollectedInformation()
#                        collected.name = b
#                    collected.save()
#                    o.collectedinformation.add(collected)
#                    o.save()

# pass 2
for f in flist:
    i = i + 1
    print "%d of %d (%s)" % (i, len(flist) * 2, f)
    companies = json.load(open(f))
    for c in companies:
        for mid in c["meldingen"].keys():
            for v in c["meldingen"][mid]["verantwoordelijken"]:
                if v.has_key("Bezoekadres"):
                    if len(v["Bezoekadres"].split("\n")) > 1:
                        o2 = Organisation.objects.filter(postcode = v["Bezoekadres"].split("\n")[1][:4], name = v["Naam"])
                    else:
                        o2 = None
                else:
                    o2 = None
                if (o2):
                    o2 = o2[0]
                else:
                    if v.has_key("Postadres"):
                        if len(v["Postadres"].split("\n")) > 1:
                            o2 = Organisation.objects.filter(postcode__startswith = v["Postadres"].split("\n")[1][:4], name = v["Naam"])
                        else:
                            o2 = None
                    else:
                        o2 = None
                    if (o2):
                        o2 = o2[0]
                    else:
                        assert("omgwtfbbq: no organisation found!")
#                        o2 = Organisation()

                o2.name = v["Naam"]
                if (v.has_key("Postadres")):
                    if len(v["Postadres"].split("\n")) > 1:
                        o2.address = v["Postadres"].split("\n")[0]
                        o2.postcode = v["Postadres"].split("\n")[1].split(" ")[0]
                else:
                    if len(v["Bezoekadres"].split("\n")) > 1:
                        o2.address = v["Bezoekadres"].split("\n")[0]
                        o2.postcode = v["Bezoekadres"].split("\n")[1].split(" ")[0]

            o2.save()

            # XXX: add city/country to company

            if len(v["Bezoekadres"].split("\n")) > 1 and len(v["Bezoekadres"].split("\n")[1].split(" ", 1)) > 1:
                try:
                    city = City.objects.get(name=v["Bezoekadres"].split("\n")[1].split(" ", 1)[1].lower())
                except City.DoesNotExist:
                    city = City()

                city.setname(v["Bezoekadres"].split("\n")[1].split(" ", 1)[1].lower())

                city.save()


            if len(v["Bezoekadres"].split("\n")) > 2:
                try:
                    country = Country.objects.get(name=v["Bezoekadres"].split("\n")[2].lower())
                except Country.DoesNotExist:
                    country = Country()

                country.setname(v["Bezoekadres"].split("\n")[2].lower())

                country.save()

#            reg = CBPRegistration()
#            reg.organisation = o
#            reg.registration_number = mid
#            reg.outside_eu = c["meldingen"][mid]["doorgifte_buiten_eu"]
#            #reg.name = mid["naam_verwerking"]
#            reg.save()
