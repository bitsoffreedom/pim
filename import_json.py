import glob
import json

import django
from django.core.management import setup_environ
import settings
from pimbase.models import *
setup_environ(settings)

for f in glob.glob("data/*.json"):
	print f
	companies = json.load(open(f))
	for c in companies:
		print "\t", c["name"].encode("utf-8")
		o = Organisation(country=None)
		o.name = c["name"]
		o.website = c["url"]
		o.save()
		for mid in c["meldingen"].keys():
			reg = CBPRegistration()
			reg.organisation = o
			reg.registration_number = mid
			#reg.name = mid["naam_verwerking"]
			reg.save()
			



