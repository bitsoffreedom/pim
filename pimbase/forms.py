from django import forms
from models import Identifier

class UserForm(forms.Form):
    def __init__(self, request, *args, **kwargs):
        super(UserForm, self).__init__(*args, **kwargs)

        request.session.setdefault('companies', [])

        ids = Identifier.objects.filter(organisation__in = request.session['companies'])
        if ids:
            for i in ids:
                self.fields['misc_%d' % (i.pk, )] = forms.CharField(label = '%s %s' % (i.organisation.short_name, i.name))

    firstname = forms.CharField(label="Voornaam")
    lastname = forms.CharField(label="Achternaam")
    street_address = forms.CharField(label="Adres")
    postcode = forms.CharField(label="Postcode")
    city = forms.CharField(label="Stad")
