from django import forms
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
    firstname = NameField(label=_("Firstname"), max_length=200)
    lastname = NameField(label=_("Lastname"), max_length=200)
    street_address = NameField(label=_("Street"), max_length=200)
    postcode = NLZipCodeField(label=_("Postcode"))
    city = NameField(label=_("Residence"), max_length=200)
