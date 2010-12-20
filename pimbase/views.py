from django.http import HttpResponse, HttpResponseRedirect, HttpResponseServerError
from django.shortcuts import render_to_response
from django.core.paginator import Paginator, InvalidPage, EmptyPage
from django.template import RequestContext
from pimbase.models import *
from pimbase.forms import UserForm
from django.core.urlresolvers import reverse
from django.http import HttpResponse
import datetime

def search(request):
	""" Search for specific tags. """
	
	org_list = Organisation.objects.all()

	role_ids = request.session['tags']
	if len(role_ids) > 0:
		org_list = org_list.filter(citizenrole__in = role_ids)

	tag_ids = request.session['tags']
	if len(tag_ids) > 0:
		org_list = org_list.filter(tags__in = tag_ids)

	return org_list

def index(request, param = None):
	# initialize the session
	request.session.setdefault('roles', [])
	request.session.setdefault('companies', [])
	request.session.setdefault('tags', [])
	request.session.setdefault('sectors', [])

	# URL processing
	page_id = 1
	if param != None:
		try:
			page_id = int(param)
		except (ValueError, TypeError):
			return HttpResponseServerError("Invalid parameter")

	tags = Organisation.tags.all()
	sectors = Sector.objects.all()
	citizenroles = CitizenRole.objects.all()
	selected_sectors = Sector.objects.filter(pk__in = request.session['sectors'])
	selected_citizenroles = CitizenRole.objects.filter(pk__in = request.session['roles'])
	selected_tags = Organisation.tags.filter(pk__in = request.session['tags'])
	selected_companies = Organisation.objects.filter(pk__in = request.session['companies'])
	org_list = search(request)
	org_count = org_list.count()
	paginator = Paginator(org_list, 10)

	try:
		org = paginator.page(page_id)
	except (EmptyPage, InvalidPage):
		return HttpResponseServerError("Page doesn't exist")

	# Based on the Yahoo search pagination pattern:
	# http://developer.yahoo.com/ypatterns/navigation/pagination/search.html
	# XXX: make the numbers less mystical.
	if org.number > 4:
		search_range = range(org.number - 3, min(3 + org.number, org.paginator.num_pages))
	else:
		search_range = range(1, min(7, org.paginator.num_pages + 1))

	return render_to_response('pim/index.html',
		{
		'tags': tags,
		'sectors': sectors,
		'citizenroles': citizenroles,
		'org_count': org_count,
		'organisations': org,
		'selected_sectors': selected_sectors,
		'selected_citizenroles': selected_citizenroles,
		'selected_companies': selected_companies,
		'selected_tags': selected_tags,
		'search_range': search_range,
		},
		context_instance=RequestContext(request))
def addcitizenrole(request, param):
	request.session.setdefault('roles', [])
	try:
		role_id = int(param)
	except (ValueError, TypeError):
		return HttpResponseServerError("Invalid parameter")

	if not CitizenRole.objects.filter(pk=role_id):
		return HttpResponseServerError("Object doesn't exist")

	if role_id not in request.session['roles']:
		request.session['roles'].append(role_id)
		request.session.modified = True
	return HttpResponseRedirect(reverse('pimbase.views.index'))

def delcitizenrole(request, param):
	request.session.setdefault('roles', [])

	try:
		role_id = int(param)
	except (ValueError, TypeError):
		return HttpResponseServerError("Invalid parameter")

	request.session['roles'].remove(role_id)
	request.session.modified = True

	return HttpResponseRedirect(reverse('pimbase.views.index'))

def addsector(request, param):
	sectors = request.session.setdefault('sectors', [])
	try:
		sector_id = int(param)
	except (ValueError, TypeError):
		return HttpResponseServerError("Invalid parameter")

	if not Sector.objects.filter(pk=sector_id):
		return HttpResponseServerError("Object doesn't exist")

	if sector_id not in request.session['sectors']:
		request.session['sectors'].append(sector_id)
		request.session.modified = True
	return HttpResponseRedirect(reverse('pimbase.views.index'))

def delsector(request, param):
	request.session.setdefault('sectors', [])

	try:
		sector_id = int(param)
	except (ValueError, TypeError):
		return HttpResponseServerError("Invalid parameter")

	request.session['sectors'].remove(sector_id)
	request.session.modified = True

	return HttpResponseRedirect(reverse('pimbase.views.index'))

def addcompany(request, param):
	company = request.session.setdefault('companies', [])
	try:
		company_id = int(param)
	except (ValueError, TypeError):
		return HttpResponseServerError("Invalid parameter")

	if not Organisation.objects.filter(pk=company_id):
		return HttpResponseServerError("Object doesn't exist")

	if company_id not in request.session['companies']:
		request.session['companies'].append(company_id)
		request.session.modified = True
	return HttpResponseRedirect(reverse('pimbase.views.index'))

def delcompany(request, param):
	request.session.setdefault('companies', [])

	try:
		company_id = int(param)
	except (ValueError, TypeError):
		return HttpResponseServerError("Invalid parameter")

	request.session['companies'].remove(company_id)
	request.session.modified = True

	return HttpResponseRedirect(reverse('pimbase.views.index'))

def addkeyword(request, param):
	request.session.setdefault('tags', [])

	try:
		tag_id = int(param)
	except (ValueError, TypeError):
		return HttpResponseServerError("Invalid parameter")

	if not Organisation.tags.filter(pk=tag_id):
		return HttpResponseServerError("Object doesn't exist")

	if tag_id not in request.session['tags']:
		request.session['tags'].append(tag_id)
		request.session.modified = True
	return HttpResponseRedirect(reverse('pimbase.views.index'))

def delkeyword(request, param):
	request.session.setdefault('tags', [])

	try:
		tag_id = int(param)
	except (ValueError, TypeError):
		return HttpResponseServerError("Invalid parameter")

	request.session['tags'].remove(tag_id)
	request.session.modified = True

	return HttpResponseRedirect(reverse('pimbase.views.index'))

def userdata(request):
	request.session.setdefault('companies', [])
	selected_companies = Organisation.objects.filter(pk__in = request.session['companies'])

	if request.method == 'POST':
		form = UserForm(request, request.POST)
		if form.is_valid():
			request.session['firstname'] = form.cleaned_data['firstname']
			request.session['lastname'] = form.cleaned_data['lastname']
			request.session['street_address'] = form.cleaned_data['street_address']
			request.session['postcode'] = form.cleaned_data['postcode']
			request.session['city'] = form.cleaned_data['city']

			ids = Identifier.objects.filter(organisation__in = request.session['companies'])

			misc = []
			for i in ids:
				if 'misc_%d' % (i.pk, ) in form.cleaned_data.keys():
					misc.append((i, form.cleaned_data['misc_%d' % (i.pk, )]))

			request.session['misc'] = misc
			request.session.modified = True

			return HttpResponseRedirect(reverse('pimbase.views.generate'))
	else:
		form = UserForm(request)

	return render_to_response('pim/userdata.html',
		{
		'form': form,
		'selected_companies': selected_companies
		},
		context_instance=RequestContext(request))

def generate(request):
	request.session.setdefault('companies', [])
	selected_companies = Organisation.objects.filter(pk__in = request.session['companies'])

	return render_to_response('pim/generate.html', {'selected_companies': selected_companies})

def generatehtml(request, param):
	request.session.setdefault('companies', [])

	try:
		company_id = int(param)
	except (ValueError, TypeError):
		return HttpResponseServerError("Invalid parameter")

	if company_id not in request.session['companies']:
		return HttpResponseServerError("Object doesn't exist")

	required_keys = (
		'firstname',
		'lastname',
		'street_address',
		'postcode',
		'city'
	)
	# <= means issubset
	if required_keys <= request.session.keys():
		return HttpResponseServerError("Invalid parameters")

	try:
		organisation = Organisation.objects.get(pk=company_id)
	except Organisation.DoesNotExist:
		return HttpResponseServerError("Object doesn't exist")

	return render_to_response('pim/generatehtml.html',
		{
		'organisation': organisation,
		'firstname': request.session['firstname'],
		'lastname': request.session['lastname'],
		'street_address': request.session['street_address'],
		'postcode': request.session['postcode'],
		'city': request.session['city'],
		'misc': request.session['misc'],
		'currentdate': datetime.date.today(),
		})

def datadetectives(request):
	return render_to_response('pim/datadetectives.html')
	
