#!/usr/bin/env python

import mechanize
from BeautifulSoup import BeautifulSoup

SEARCH_FORM = "http://www.cbpweb.nl/asp/ORSearch.asp"


def list_postcode(pc=""):
	if len(pc) < 2:
		return False # Should give at least 2 digits
	# Start a new browseer
	br = mechanize.Browser()
	br.open(SEARCH_FORM)
	# Select the right form
	br.select_form("searchform")
	# Because the page is obfuscated, just fill in all the fields.
	for name in br.form._pairs():
		br[name[0]] = pc
	# Mark the "search by postcode" radio button
	br["level0"] = ["3"]
	# For now, just return the raw page with results
	resp = br.submit().read()
	if resp.find("Er zijn meer dan 50 meldingen gevonden.") > -1:
		return False
	elif resp.find("Er zijn geen meldingen gevonden") > -1:
		return None
	return resp


def list_companies_from_page(page):
	companies = []
	soup = BeautifulSoup(page)
	table = soup.find("table", {"border":"1"})
	rows = table.findAll("tr")[1:]
	for row in rows:
		colls = row.findAll("td")
		companies.append({
				"link": colls[0].find("a")["href"],
		})
	return companies


if __name__ == "__main__":
	companies = []
	postcodes = ["%02d" % (i) for i in range(100)]
	while postcodes:
		pc = postcodes.pop()
		page = list_postcode(pc)
		if page == None: continue
		if not page:
			postcodes += [pc+"%d" % (i) for i in range(10)]
			continue
		print pc
		comp = list_companies_from_page(page)
		if comp:
			companies += comp
		print "Total:", len(companies)
	print companies
	print len(companies)


