from django.db import models

class City(models.Model):
	city = models.CharField(max_length=20)

	class Meta:
		verbose_name_plural="cities"

	def __unicode__(self):
		return self.city;

class Country(models.Model):
	country = models.CharField(max_length=20)

	class Meta:
		verbose_name_plural="countries"

	def __unicode__(self):
		return self.country;

class Sector(models.Model):
	sector = models.CharField(max_length=20)

	def __unicode__(self):
		return self.sector;
	
class Role(models.Model):
	role = models.CharField(max_length=20)

	def __unicode__(self):
		return self.role;

class Keyword(models.Model):
	keyword = models.CharField(max_length=20)

	def __unicode__(self):
		return self.keyword;

class Brand(models.Model):
	name = models.CharField(max_length=20)

class Identifier(models.Model):
	name = models.CharField(max_length=20)

class Organisation(models.Model):
	preflabel = models.CharField(max_length=200, help_text="De officiele naam van de organisatie")
	altlabel = models.CharField(max_length=200, blank=True, help_text="Een alternatieve naam voor de organisatie")
	kvknumber = models.CharField(max_length=200, blank=True)
	street_address = models.CharField(max_length=200, blank=True)
	housenr = models.CharField(max_length=20, blank=True)
	answernr = models.CharField(max_length=20, blank=True)
	postbus = models.CharField(max_length=20, blank=True)
	postcode = models.CharField(max_length=20, blank=True)
	city = models.ForeignKey(City, blank=True, null=True)
	country_name = models.ForeignKey(Country, blank=True, null=True)
	sector = models.ForeignKey(Sector, blank=True, null=True)
	website = models.CharField(max_length=200, blank=True)
	brands = models.ManyToManyField(Brand, blank=True, null=True)
	keyword = models.ManyToManyField(Keyword, blank=True, null=True)
	hasmyname = models.BooleanField()
	hasmystreetaddress = models.BooleanField()
	hasmytelephonenumber = models.BooleanField()
	hasmyairmilecardied = models.BooleanField()
	hasmycardlicensenumber = models.BooleanField()
	notificationnumber = models.DecimalField(max_digits=10,
		decimal_places=0, blank=True, null=True, help_text="CBP registernummer")
	citizenorconsumerrole = models.ManyToManyField(Role, blank=True,
		null=True, help_text="Rol van de burger/consument ten opzichte van de" \
		"maatschappij en dienstverleners en andere organisaties")
	relation = models.ManyToManyField("self", through="Relation",
		symmetrical=False, blank=True, null=True)
	identifiers = models.ManyToManyField(Identifier, blank=True, null=True)

	def __unicode__(self):
		return self.preflabel;

class Relation(models.Model):
	src = models.ForeignKey(Organisation, related_name='src')
	dest = models.ForeignKey(Organisation, related_name='dest')
	confidence = models.DecimalField(max_digits=10, decimal_places=0)

class Church(Organisation):
	religion = models.CharField(max_length=200)
	number_of_priests = models.DecimalField(max_digits=10, decimal_places=0)

