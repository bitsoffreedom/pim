from django.template import defaultfilters
from taggit.managers import TaggableManager

from django.utils.translation import ugettext_lazy as _

from django.db import models

class City(models.Model):
    name = models.CharField(max_length=64, verbose_name=_('name'), unique=True)
    slug = models.SlugField(unique=True)
    
    class Meta:
        verbose_name=_('city')
        verbose_name_plural=_('cities')

    def __unicode__(self):
        return self.name

    def setname(self,name):
        self.name = name
        self.slug = defaultfilters.slugify(name)

class Country(models.Model):
    name = models.CharField(max_length=64, verbose_name=_('name'), unique=True)
    slug = models.SlugField(unique=True)

    class Meta:
        verbose_name=_('country')
        verbose_name_plural=_('countries')

    def __unicode__(self):
        return self.name

    def setname(self,name):
        self.name = name
        self.slug = defaultfilters.slugify(name)

class CitizenRole(models.Model):
    """ TODO: Describe what this entity means. """

    name = models.CharField(max_length=20)
    label = models.CharField(max_length=200, help_text=_("Description of \
        the citizen role. This is used to generate \
        understandable descriptions for the filters."))

    class Meta:
        verbose_name=_('citizen role')
        verbose_name_plural=_('citizen roles')

    def __unicode__(self):
        return self.name;

class OrganisationType(models.Model):
    """ The possible types of Organisation that exist. e.g. nonprofit,
    business etc """

    name = models.CharField(max_length=40, verbose_name=_('name'))

    def __unicode__(self):
        return self.name

class Organisation(models.Model):
    """ A model representing an organisation. """

    name = models.CharField(max_length=200, verbose_name=_('name'), help_text="De officiele naam van de organisatie", unique=True)
    """ Official name of organisation. """
    short_name = models.CharField(max_length=200, blank=True, verbose_name=('short name'), help_text=('A short name for an organisation.'))
    kvknumber = models.CharField(max_length=200, blank=True)
    addressee = models.CharField(max_length=200, verbose_name=_('The name of the department or contact to send the letter to'), blank=True, null=True)
    address = models.CharField(max_length=200, verbose_name=_('street address or PO box'), blank=True)
    postcode = models.CharField(max_length=20, blank=True)
    organisationtype = models.ForeignKey(OrganisationType, blank=True, null=True)
    city = models.ForeignKey(City, blank=True, null=True)
    country = models.ForeignKey(Country, blank=True, null=True) #, default=lambda:Country.objects.get(name='Nederland'))
    website = models.CharField(max_length=200, blank=True)
    tags = TaggableManager()
    citizenrole = models.ManyToManyField(CitizenRole, blank=True, null=True,
         verbose_name='citizen role',
         help_text=_('The sort of relations this organisation has with citizens.'))

    class Meta:
        verbose_name=_('organisation')
        verbose_name_plural=_('organisations')
        ordering = [ "name", ]

    def __unicode__(self):
        return self.name

    def html_link(self):
        return "<a href=\"%s\">%s</a>" % (self.website, self.website)
    html_link.allow_tags = True

    def setname(self,name):
        self.name = name
        self.short_name = defaultfilters.slugify(name)

