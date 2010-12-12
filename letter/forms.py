from django import forms
from models import Organisation

class UserForm(forms.Form):
	firstname = forms.CharField()
	lastname = forms.CharField()
	street_address = forms.CharField()
	postcode = forms.CharField()
	city = forms.CharField()
