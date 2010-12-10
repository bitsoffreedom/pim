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
	
class CitizenRole(models.Model):
	name = models.CharField(max_length=20)

	class Meta:
		verbose_name=_('citizen role')
		verbose_name_plural=_('citizen roles')

	def __unicode__(self):
		return self.name;

class Brand(models.Model):
	owner = models.ForeignKey('Organisation')
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
		verbose_name=_('consumer identifier')
		verbose_name_plural=_('consumer identifiers')

class CollectedInformation(models.Model):
	""" Information which companies have about an user"""
	
	name = models.CharField(max_length=200, blank=True, help_text="")

	class Meta:
		verbose_name=_('collected information')
		verbose_name_plural=_('collected information')

	def __unicode__(self):
		return self.name

class OrganisationType(models.Model):
	""" The possible types of Organisation that exist. e.g. nonprofit,
	business etc """

	name = models.CharField(max_length=40, verbose_name=_('name'))

	def __unicode__(self):
		return self.name
	

class Organisation(models.Model):
	""" A model representing an organisation. """

	name = models.CharField(max_length=200, verbose_name=_('name'), help_text="De officiele naam van de organisatie")
	""" Official name of organisation. """
	short_name = models.CharField(max_length=200, blank=True, verbose_name=('short name'), help_text=('A short name for an organisation.'))
	kvknumber = models.CharField(max_length=200, blank=True)
	address = models.CharField(max_length=200, verbose_name=_('street address or PO box'), blank=True)
	postcode = models.CharField(max_length=20, blank=True)
	organisationtype = models.ForeignKey(OrganisationType, blank=True, null=True)
	city = models.ForeignKey(City, blank=True, null=True)
	country = models.ForeignKey(Country, blank=True, null=True, default=lambda:Country.objects.get(name='Nederland'))
	sector = models.ForeignKey(Sector, blank=True, null=True)
	website = models.CharField(max_length=200, blank=True)
	tags = TaggableManager()
	citizenrole = models.ManyToManyField(CitizenRole, blank=True, null=True,
	     verbose_name='citizen role',
	     help_text=_('The sort of relations this organisation has with citizens.'))
	collectedinformation = models.ManyToManyField(CollectedInformation, blank=True, null=True,
	     verbose_name=('collected information'),
	     help_text=_('The sort of information this organisation gathers about consumers.'))
	relation = models.ManyToManyField("self", through="Relation",
		symmetrical=False, blank=True, null=True)

	class Meta:
		verbose_name=_('organisation')
		verbose_name_plural=_('organisations')

	def __unicode__(self):
		return self.name

class RelationType(models.Model):
	""" Types of relationships. """

	name = models.CharField(max_length=64, verbose_name=_('name'))
	slug = models.SlugField()

	class Meta:
		verbose_name = _('relation type')
		verbose_name_plural = _('relation types')

	def __unicode__(self):
		return self.name

class Relation(models.Model):
	""" A relation between two companies """
	
	from_organisation = models.ForeignKey(Organisation, 
	                                      related_name='from',
	                                      verbose_name=_('from'))
	to_organisation = models.ForeignKey(Organisation, 
	                                    related_name='to',
	                                    verbose_name=_('to'))
	type = models.ForeignKey(RelationType)

class CBPRegistration(models.Model):
	""" A database registered with the CBP. This
		model should match the data in the public 
		BKR register as closely as possible. 

		TODO: Either directly contact the CBP about
		their datastructure and/or regular data
		synchronization of the public register or
		make a real good study of register access results.
		
		This model should be intelligent and do stuff like:
		1) We fill in the registration number.
		2) Go out to CBP and request all available information
		   about stored data, intended use etcetera.
		3) Fill this into our own database.
		4) Update the organisation with the kinds of data
		   stored by this party.
	    
		Reference: http://www.cbpweb.nl/Pages/ind_reg_meldreg.aspx
	"""
	
	organisation = models.ForeignKey(Organisation)	  
	# To do: add a validator here making sure that the result is always 7 digits long.
	registration_number = models.PositiveIntegerField(primary_key=True, verbose_name=_('registration number'))
	name = models.CharField(max_length=255, verbose_name=_('database name'), blank=True, null=True)
	purpose = models.CharField(max_length=2048, verbose_name=_('purpose of processing'), blank=True, null=True)
	# XXX: There is no way to translate the registration number to a valid CBP URL. That is why we save the url. 
	# XXX: at some point we want to change this to just the unique internal
	# cbp identifier (moid). Or tell the CBP their application is retarted,
	# and they should fix it.
	url = models.URLField(max_length=255,verbose_name=_('cbp url'), blank=True, null=True)
	
	def __unicode__(self):
		return self.name

