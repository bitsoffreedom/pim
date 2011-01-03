import json
import os
import sqlite3

def insert_doelen(melding_id, doelen, cursor):
	for doel in doelen:
		cursor.execute("INSERT INTO doel (melding_id, naam) " \
				" VALUES (?, ?)", (melding_id, doel))

def insert_verantwoordelijken(melding_id, verantwoordelijken, cursor):
	for verantwoordelijke in verantwoordelijken:
		naam = None
		bezoekadres = None
		postadres = None
		for key, value in verantwoordelijke.iteritems():
			if key == "Naam":
				if naam != None:
					raise Exception("Field Naam found twice")
				naam = value
			elif key == "Bezoekadres":
				if bezoekadres != None:
					raise Exception("Field Bezoekadres found twice")
				bezoekadres = value
			elif key == "Postadres":
				if postadres != None:
					raise Exception("Field Postadres found twice")
				postadres = value
			else:
				raise Exception('Unknown field found');
		cursor.execute("INSERT INTO verantwoordelijke (melding_id," \
				"bezoekadres, naam, postadres) VALUES " \
				"(?, ?, ?, ?)", (melding_id, bezoekadres, naam,
				postadres))

def insert_ontvangers(melding_id, ontvangers, cursor):
	for ontvanger in ontvangers:
		cursor.execute("INSERT INTO ontvanger (melding_id, naam) " \
				" VALUES (?, ?)", (melding_id, ontvanger));

def insert_betrokkene_data(betrokkene_id, betrokkene, cursor):
	for name, value in betrokkene.iteritems():
		cursor.execute("INSERT INTO betrokkene_data (betrokkene_id," \
				"name, value) VALUES (?, ?, ?)", (betrokkene_id,
				name, value))

def insert_betrokkenen(melding_id, betrokkenen, cursor):
	for name in betrokkenen.keys():
		betrokkene = betrokkenen[name]
		cursor.execute("INSERT INTO betrokkene (melding_id, naam)" \
				"VALUES (?, ?)", (melding_id, name)) 
		betrokkene_id = cursor.lastrowid
		insert_betrokkene_data(betrokkene_id, betrokkene, cursor)

def insert_meldingen(company_id, meldingen, cursor):
	for id in meldingen.keys():
		melding = meldingen[id]
		cursor.execute("INSERT INTO melding (company_id, cbp_id," \
				"description, doorgifte_passend, url," \
				"doorgifte_buiten_eu, naam_verwerking) VALUES " \
				"(?, ?, ?, ?, ?, ?, ?)", (company_id, id,
				melding['description'],
				melding['doorgifte_passend'], melding['url'],
				melding['doorgifte_buiten_eu'],
				melding['naam_verwerking']))
		melding_id = cursor.lastrowid
		if "betrokkenen" in melding.keys():
			insert_betrokkenen(melding_id, melding["betrokkenen"], cursor)
		if "ontvangers" in melding.keys():
			insert_ontvangers(melding_id, melding["ontvangers"], cursor)
		if "verantwoordelijken" in melding.keys():
			insert_verantwoordelijken(melding_id, melding["verantwoordelijken"], cursor)
		if "doelen" in melding.keys():
			insert_doelen(melding_id, melding["doelen"], cursor)

def insert_companies(fp, cursor):
	j = json.load(fp)
	i = 0

	for company in j:
		cursor.execute("INSERT INTO company (url, name) VALUES (?, ?)",
			(company["url"], company["name"]))
		company_id = cursor.lastrowid

		insert_meldingen(company_id, company["meldingen"], cursor)

		i += 1

	return i

connection = sqlite3.connect("cbp.sqlite")
c = connection.cursor()

ncompanies = 0
for dirname, dirnames, filenames, in os.walk('.'):
	for filename in filenames:
		if filename.endswith(".json"):
			path = os.path.join(dirname, filename)
			fp = open(path, 'r')
			ncompanies += insert_companies(fp, c)

print "Inserted %d companies" % (ncompanies, )
connection.commit()
c.close()
