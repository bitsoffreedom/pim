from django import forms
from models import Identifier
from django.core import exceptions
from django.utils.translation import ugettext as _
from django.contrib.localflavor.nl.forms import NLZipCodeField

class NameField(forms.CharField):
    # XXX: There is no source for the validity of this invalid characters.
    invalid_characters = ";|[]{}<>*&^%$#@!`~|\/?+=_:"

    def validate(self, *args, **kwargs):
        super(NameField, self).validate(*args, **kwargs)

        if args[0] in self.invalid_characters:
            raise exceptions.ValidationError(_("Please don't enter any of the following characters: %(characters)s") % {'characters': self.invalid_characters, } )

class UserForm(forms.Form):
    def __init__(self, request, *args, **kwargs):
        super(UserForm, self).__init__(*args, **kwargs)

#        request.session.setdefault('companies', [])
#
#        ids = Identifier.objects.filter(organisation__in = request.session['companies'])
#        if ids:
#            for i in ids:
#                self.fields['misc_%d' % (i.pk, )] = forms.CharField(label = '%s %s' % (i.organisation.short_name, i.name))

    firstname = NameField(label="Voornaam", max_length=200)
    lastname = NameField(label="Achternaam", max_length=200)
    street_address = NameField(label="Straat", max_length=200)
    postcode = NLZipCodeField(label="Postcode")
    city = NameField(label="Woonplaats", max_length=200)
