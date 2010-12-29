#!/usr/bin/env python

import mechanize
from BeautifulSoup import BeautifulSoup
import urllib2
import pprint

BASE_URL = "http://www.cbpweb.nl/asp/"
SEARCH_FORM = BASE_URL + "ORSearch.asp"


def clean(s):
	return strip_tags(str(s)).strip()

def strip_tags(s):
    start = s.find("<")
    if start == -1:
        return s # No tags
    end = s.find(">", start)+1
    return s[:start]+strip_tags(s[end:])


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

def parse_persoonsgegevens_table(table):
	persoonsgegevens = {}
	rows = table.findAll("tr", recursive=False)
	for r in range(1, len(rows)-1, 3):
		name = clean(rows[r].findAll("td")[1])
		value = clean(rows[r+1].findAll("td")[1])
		persoonsgegevens[name] = value
	persoonsgegevens["bijzonder"] = clean(rows[-1].findAll("td")[1])
	return persoonsgegevens


def get_detailed_info(url):
	page = BeautifulSoup(urllib2.urlopen(url).read())
	info = {}
	#print url
	rows = page.find("table", {"class": "list"}).findAll("tr", recursive=False)
	i = 0
	while i < len(rows):
		colls = rows[i].findAll("td", recursive=False)
		if clean(colls[0]) == "Ontvanger(s)":
			values = []
			if colls[0].has_key("rowspan"):
				if len(colls) > 1:
					values.append(clean(colls[1]))
				rs = int(colls[0]["rowspan"])
				for j in xrange(1,rs):
					values.append(clean(rows[i+j]))
				i += rs - 1
			else:
				print "ERROR: no rowspan!"
			info["ontvangers"] = values
		elif clean(colls[0]) == "Meldingsnummer":
			info["id"] = int(clean(colls[1]))
		elif clean(colls[0]) == "Naam verwerking":
			info["naam_verwerking"] = clean(colls[1])
		elif clean(colls[0]) == "Verantwoordelijke(n)":
			print colls[1].find("table")
			info["verantwoordelijken"] = "TODO"
		elif clean(colls[0]) == "Doel(en) van verwerking":
			values = []
			if colls[0].has_key("rowspan"):
				if len(colls) > 1:
					values.append(clean(colls[1]))
				rs = int(colls[0]["rowspan"])
				for j in xrange(1,rs):
					values.append(clean(rows[i+j]))
				i += rs - 1
			else:
				print "ERROR: no rowspan!"
			info["doelen"] = values
		elif clean(colls[0]) == "Betrokkene(n)":
			betrokkenen = {}
			done = False
			while not done:
				colls = rows[i+1].findAll("td", recursive=False)
				name = clean(colls[0])
				value = parse_persoonsgegevens_table(colls[1].find("table"))
				betrokkenen[name] = value
				if clean(rows[i+2]) != "":
					done = True
				i += 2
			i -= 1
			info["betrokkenen"] = betrokkenen
		elif clean(colls[0]) == "Doorgifte buiten EU":
			v = clean(colls[1].string)
			if v == "J": v = True
			elif v == "N": v = False
			info["doorgifte_buiten_eu"] = v
		elif clean(colls[0]) == "Doorgifte passend":
			v = clean(colls[1])
			if v == "J": v = True
			elif v == "N": v = False
			info["doorgifte_passend"] = v
		else:
			print "UNKNOWN: " + clean(colls[0])
		i += 1
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
			#get_company_info(c)
		

