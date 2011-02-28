import glob
import json

from pprint import pprint

import django
from django.core.management import setup_environ
import settings
from pimbase.models import *
setup_environ(settings)

from cbphex import *


for f in glob.glob("data/*.json"):
	print f
	companies = json.load(open(f))
	for c in companies:
		print "\t", c["name"].encode("utf-8")
		print "\t\t", url_decode(c["url"])
		o = Organisation(country=None)
		o.name = c["name"]
		o.website = c["url"]
        url_dec = url_decode(c["url"])
        if (url_dec[2]):
            o.postcode = url_dec[2]
        else:
            o.postcode = url_dec[1]
	    o.save()
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
                            o2 = Organisation.objects.filter(postcode = v["Postadres"].split("\n")[1][:4], name = v["Naam"])
                        else:
                            o2 = None
                    else:
                        o2 = None
                    if (o2):
                        o2 = o2[0]
                    else:
                        o2 = Organisation()

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

            city = City()
            if len(v["Bezoekadres"].split("\n")) > 1:
                city.setname(v["Bezoekadres"].split("\n")[1].split(" ")[1])

            city.save()

            country = Country()
            if len(v["Bezoekadres"].split("\n")) > 1:
                country.setname(v["Bezoekadres"].split("\n")[2])

            country.save()

            reg = CBPRegistration()
            reg.organisation = o
            reg.registration_number = mid
            reg.outside_eu = v["doorgifte_buiten_eu"]
			#reg.name = mid["naam_verwerking"]
            reg.save()
