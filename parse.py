import xlrd

wb = xlrd.open_workbook('test.xls')
sh = wb.sheet_by_index(0)

# workbook columns
field_role=1
field_database=2
field_website=3
field_reportsto=4
field_collaborationwith=5
field_receivespiifrom=6
field_preflabel=7
field_altlabel=8
field_hasmyname=10
field_hasmystreetaddress=11
field_hasmytelephonenumber=12
field_hasmyairmilescardid=13
field_hasmycarlicensenumber=14
field_remarks=15
field_notificationnumber=16

class Organisation():
	pk = 0 # pk
        preflabel = ""
        altlabel =  ""
	kvknumber = 0 
        street_address =  ""
        housenr =  ""
        answernr =  ""
        postbus = ""
        postcode =  ""
        city = 0 # fk
        country_name = 0 # fk
        sector = 0 # fk
        website =  ""
        hasmyname = 0 # bool
        hasmystreetaddress = 0 # bool
        hasmytelephonenumber = 0 # bool
        hasmyairmilecardid = 0 # bool
        hasmycardlicensenumber = 0 # bool
        notificationnumber = 0 # decimal
        citizenorconsumerrole = [] # fk

class Role():
	pk = 0 # pk
	role = ""

	def __unicode__(self):
		return self.role

def createrole(r):
	print "- fields:"
	print "    role: %s" % (r.role)
	print "  model: letter.ConsumerRelation"
	print "  pk: %d" % (r.pk)

def createorganisation(o):
	print "- fields:"
#	print "    altlabel: '%s'" % (o.altlabel)
	print "    answernr: '%s'" % (o.answernr)
	print "    brands: [%s]" % (",".join(o.brands))
	print "    consumerrelation: [%s]" % (",".join(o.citizenorconsumerrole))
	print "    city: %d" % (o.city)
	print "    country_name: %d" % (o.country_name)
#	if o.hasmyname:
#		print "    hasmyname: true"
#	if o.hasmystreetaddress:
#		print "    hasmystreetaddress: true"
#	if o.hasmytelephonenumber:
#		print "    hasmytelephonenumber: true"
#	if o.hasmyairmilecardid:
#		print "    hasmyairmilecardid: true"
#	if o.hasmycardlicensenumber:
#		print "    hasmycardlicensenumber: true"
	print "    housenr: '%s'" % (o.housenr)
#	print "    keyword: [%s]" % (",".join(o.keyword))
	print "    postbus: '%s'" % (o.postbus)
	print "    postcode: '%s'" % (o.postcode)
	print "    name: '%s'" % (o.preflabel.encode('ascii','ignore'))
	print "    sector: %d" % (o.sector)
	print "    website: '%s'" % (o.website)
	print "    street_address: '%s'" % (o.street_address)
	print "  model: letter.organisation"
	print "  pk: %d" % (o.pk)

companylist = []
i = 1;
for rownum in range(10, 40):
	companylist.append(sh.row_values(rownum))

# map columns to role object

role_list = []
for c in companylist:
	if c[field_role] not in role_list and len(c[field_role]) > 0:
		r = c[field_role]
		role_list.append(r)

role_pk_counter = 1
role_objectlist = []

for r in role_list:
	ro = Role()
	ro.pk = role_pk_counter
	ro.role = r
	createrole(ro)
	role_objectlist.append(ro)
	role_pk_counter += 1

def findrolebyname(name):
	for r in role_objectlist:
		if r.role == name:
			return r
	return 0

# map columns to organisation object
organisation_pk_counter = 1
org_objectlist = []
for c in companylist:
	o = Organisation()
	o.pk = organisation_pk_counter
	o.preflabel = c[field_preflabel]
	o.altlabel = c[field_altlabel]
	o.street_address = "" # not mapped
	o.housenr = "" # not mapped
	o.answernr = "" # not mapped
	o.postbus = "" # not mapped
	o.postcode = "" # not mapped
	o.city = 1 # not mapped
	o.country_name = 1 # not mapped
	o.sector = 1 # not mapped
	o.website = c[field_website]
	o.brands = [] # not mapped
	o.keyword = [] # not mapped
	o.hasmyname = c[field_hasmyname]
	o.hasstreetaddress = c[field_hasmystreetaddress]
	o.hasmytelephonenumber = c[field_hasmytelephonenumber]
	o.hasmyairmilecardied = c[field_hasmyairmilescardid]
	o.hasmycarlicensenumber = c[field_hasmycarlicensenumber]
	role = findrolebyname(c[field_role])
	if role:
		o.citizenorconsumerrole = [str(role.pk),]
	org_objectlist.append(o)
	organisation_pk_counter += 1

def findorgbyname(name):
	for o in org_objectlist:
		if o.preflabel == name:
			return o

	return 0

# Make proper top organisation pointers now. Since the TOP organisations are in
# the list now.
for c in companylist:
	r = findorgbyname(c[field_reportsto])
	if len(c[field_reportsto]) > 0 and r:
		o = findorgbyname(c[field_preflabel])

for o in org_objectlist:
	createorganisation(o)
