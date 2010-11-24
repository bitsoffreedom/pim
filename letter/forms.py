from django import forms
from models import Organisation

class AddCompanyForm(forms.Form):
	companies = forms.ModelMultipleChoiceField(
		queryset=Organisation.objects.all(),
		widget=forms.CheckboxSelectMultiple()
	)

class UserForm(forms.Form):
	firstname = forms.CharField()
	lastname = forms.CharField()
	street_address = forms.CharField()
	postcode = forms.CharField()
	city = forms.CharField()
