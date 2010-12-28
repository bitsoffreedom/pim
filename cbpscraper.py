#!/usr/bin/env python

import mechanize
from BeautifulSoup import BeautifulSoup
import urllib2
import pprint

BASE_URL = "http://www.cbpweb.nl/asp/"
SEARCH_FORM = BASE_URL + "ORSearch.asp"



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
				"name": colls[0].find("a").string,
				"url": BASE_URL+colls[0].find("a")["href"],
		})
	return companies


def get_company_info(company):
	page = BeautifulSoup(urllib2.urlopen(company["url"]).read())
	company["meldingen"] = {}
	for row in page.find("table", {"class": "list"}).findAll("tr")[1:]:
		colls = row.findAll("td")
		id = colls[1].string
		url = BASE_URL + colls[0].find("a")["href"]
		description = colls[0].find("a").string
		melding = get_detailed_info(url)
		melding["url"] = url
		melding["description"] = description
		company["meldingen"][id] = melding
	return company

def get_detailed_info(url):
	page = BeautifulSoup(urllib2.urlopen(url).read())
	info = {}
	print url
	for row in page.find("table", {"class": "list"}).findAll("tr", recursive=False):
		r = row.findAll("td", recursive=False)
		if len(r) == 2:
			table = r[1].find("table")
			if table:
				print "TABLE"
			else:	
				key = r[0].string
				value = r[1].string
				info[key.strip()] = value.strip()
				print "OK"
		else:
			print "ERROR"
	return info


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
		comp = list_companies_from_page(page)
		for c in comp:
			pprint.pprint(get_company_info(c))
		

