from taggit.managers import TaggableManager

from django.utils.translation import ugettext_lazy as _

from django.db import models

class City(models.Model):
	name = models.CharField(max_length=64, verbose_name=_('name'))
	slug = models.SlugField()
	
	class Meta:
		verbose_name=_('city')
		verbose_name_plural=_('cities')

	def __unicode__(self):
		return self.name

class Country(models.Model):
	name = models.CharField(max_length=64, verbose_name=_('name'))
	slug = models.SlugField()

	class Meta:
		verbose_name=_('country')
		verbose_name_plural=_('countries')

	def __unicode__(self):
		return self.name

class Sector(models.Model):
	name = models.CharField(max_length=64, verbose_name=_('name'))
	slug = models.SlugField()

	class Meta:
		verbose_name=_('sector')
		verbose_name_plural=_('sectors')

	def __unicode__(self):
		return self.name
	
class ConsumerRelation(models.Model):
	role = models.CharField(max_length=20)

	class Meta:
		verbose_name=_('consumer relation')
		verbose_name_plural=_('consumer relations')

	def __unicode__(self):
		return self.role

class Brand(models.Model):
	name = models.CharField(max_length=20)
	slug = models.SlugField()

	class Meta:
		verbose_name=_('brand')
		verbose_name_plural=_('brands')

	def __unicode__(self):
		return self.name

class Identifier(models.Model):
	""" Identifier which company require to identify a user in the letter """
	name = models.CharField(max_length=20)
	organisation = models.ForeignKey('Organisation')

	class Meta:
		verbose_name=_('identifier')
		verbose_name_plural=_('identifiers')

class ConsumerInformation(models.Model):
	""" Information which companies have about an user"""
	
	name = models.CharField(max_length=200, blank=True, help_text="")

	class Meta:
		verbose_name=_('consumer information')
		verbose_name_plural=_('consumer information')

	def __unicode__(self):
		return self.name

class Organisation(models.Model):
	""" A model representing an organisation. """

	name = models.CharField(max_length=200, verbose_name=_('name'), help_text="De officiele naam van de organisatie")
	""" Official name of organisation. """
	short_name = models.CharField(max_length=200, blank=True, verbose_name=('short name'), help_text=('A short name for an organisation.'))
	kvknumber = models.CharField(max_length=200, blank=True)
	street_address = models.CharField(max_length=200, verbose_name=_('street address'), blank=True)
	housenr = models.CharField(max_length=20, verbose_name=_('house number'), blank=True)
	answernr = models.CharField(max_length=20, verbose_name=_('answer number'), blank=True)
	postbus = models.CharField(max_length=20, blank=True)
	postcode = models.CharField(max_length=20, blank=True)
	city = models.ForeignKey(City, blank=True, null=True)
	country_name = models.ForeignKey(Country, blank=True, null=True)
	sector = models.ForeignKey(Sector, blank=True, null=True)
	website = models.CharField(max_length=200, blank=True)
	brands = models.ManyToManyField(Brand, blank=True, null=True)
	tags = TaggableManager()
	notificationnumber = models.DecimalField(max_digits=10,
		decimal_places=0, blank=True, null=True, help_text="CBP registernummer")
	consumerrelation = models.ManyToManyField(ConsumerRelation, blank=True,
		null=True, help_text="Rol van de burger/consument ten opzichte van de" \
		"maatschappij en dienstverleners en andere organisaties")
	relation = models.ManyToManyField("self", through="Relation",
		symmetrical=False, blank=True, null=True)
	consumerinforation = models.ManyToManyField(ConsumerInformation)

	class Meta:
		verbose_name=_('organisation')
		verbose_name_plural=_('organisations')

	def __unicode__(self):
		return self.name

class Relation(models.Model):
	""" A relation between two companies """
	
	src = models.ForeignKey(Organisation, related_name='src')
	dest = models.ForeignKey(Organisation, related_name='dest')
	confidence = models.DecimalField(max_digits=10, decimal_places=0)

