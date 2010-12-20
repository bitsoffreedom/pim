from django import forms
from models import Identifier

class UserForm(forms.Form):
	def __init__(self, request, *args, **kwargs):
		super(UserForm, self).__init__(*args, **kwargs)
		# XXX: the misc form data  shouldn't overwrite firstname, address etc

		request.session.setdefault('companies', [])

		ids = Identifier.objects.filter(organisation__in = request.session['companies'])
		if ids:
			for i in ids:
				self.fields[i.name] = forms.CharField()

	firstname = forms.CharField()
	lastname = forms.CharField()
	street_address = forms.CharField()
	postcode = forms.CharField()
	city = forms.CharField()
