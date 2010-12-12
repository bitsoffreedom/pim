from django.http import HttpResponse, HttpResponseRedirect
from django.shortcuts import render_to_response
from django.core.paginator import Paginator, InvalidPage, EmptyPage
from django.template import RequestContext
from letter.models import *
from letter.forms import UserForm
from django.core.urlresolvers import reverse
from reportlab.pdfgen import canvas
from django.http import HttpResponse

# XXX: Currently a 1:1 mapping. Should be extended to 1:many
# XXX: All the mapper code included in the index and addcitizenrole methods are
# basically hacks. They should be replaced.
CITIZENROLE_MAPPER = (
	(1, "Ik heb een Auto", "Auto-eigenaar"),
	(2, "Ik heb een Bankrekening", "Bank-klant"),
	(3, "Ik heb reis veel", "Buitendland-betaler"),
	(4, "Ik heb shop veel", "Consument"),
	(5, "Ik heb m'n eigen bedrijf", "Ondernemer"),
)

def search(request):
	""" Search for specific tags. """
	
	org_list = Organisation.objects.all()

	selected_citizenroles = [x[2] for x in CITIZENROLE_MAPPER if x[0] in request.session['roles']]
	if len(selected_citizenroles) > 0:
		org_list = org_list.filter(citizenrole__name__in = selected_citizenroles)

	tag_ids = request.session['tags']
	if len(tag_ids) > 0:
		org_list = org_list.filter(tags__in = tag_ids)

	return org_list

def index(request, param = None):
	# initialize the session
	request.session.setdefault('roles', [])
	request.session.setdefault('companies', [])
	request.session.setdefault('tags', [])

	# URL processing
	page_id = 1
	if param != None:
		try:
			page_id = int(param)
		except (ValueError, TypeError):
			return HttpResponse("fail")

	tags = Organisation.tags.all()
	selected_citizenroles = [x for x in CITIZENROLE_MAPPER if x[0] in request.session['roles']]
	selected_tags = Organisation.tags.filter(pk__in = request.session['tags'])
	selected_companies = Organisation.objects.filter(pk__in = request.session['companies'])
	org_list = search(request)
	org_count = org_list.count()
	paginator = Paginator(org_list, 10)

	try:
		org = paginator.page(page_id)
	except (EmptyPage, InvalidPage):
		return HttpResponse("fail")

	return render_to_response('pim/index.html',
		{
		'tags': tags,
		'org_count': org_count,
		'organisations': org,
		'selected_citizenroles': selected_citizenroles,
		'selected_companies': selected_companies,
		'selected_tags': selected_tags,
		},
		context_instance=RequestContext(request))

def addcitizenrole(request, param):
	roles = request.session.setdefault('roles', [])
	try:
		role_id = int(param)
	except (ValueError, TypeError):
		return HttpResponse("fail")

	if role_id not in [r[0] for r in CITIZENROLE_MAPPER]:
		return HttpResponse("fail1")

	citizenrole = [r[2] for r in CITIZENROLE_MAPPER if r[0] == role_id]
	if not CitizenRole.objects.filter(name=citizenrole[0]):
		return HttpResponse("fail2")

	if role_id not in request.session['roles']:
		request.session['roles'].append(role_id)
		request.session.modified = True
	return HttpResponseRedirect(reverse('letter.views.index'))

def delcitizenrole(request, param):
	request.session.setdefault('roles', [])

	try:
		role_id = int(param)
	except (ValueError, TypeError):
		return HttpResponse("fail")

	request.session['roles'].remove(role_id)
	request.session.modified = True

	return HttpResponseRedirect(reverse('letter.views.index'))

def addcompany(request, param):
	company = request.session.setdefault('companies', [])
	try:
		company_id = int(param)
	except (ValueError, TypeError):
		return HttpResponse("fail")

	if not Organisation.objects.filter(pk=company_id):
		return HttpResponse("fail")

	if company_id not in request.session['companies']:
		request.session['companies'].append(company_id)
		request.session.modified = True
	return HttpResponseRedirect(reverse('letter.views.index'))

def delcompany(request, param):
	request.session.setdefault('companies', [])

	try:
		company_id = int(param)
	except (ValueError, TypeError):
		return HttpResponse("fail")

	request.session['companies'].remove(company_id)
	request.session.modified = True

	return HttpResponseRedirect(reverse('letter.views.index'))

def addkeyword(request, param):
	request.session.setdefault('tags', [])

	try:
		tag_id = int(param)
	except (ValueError, TypeError):
		return HttpResponse("fail")

	if not Organisation.tags.filter(pk=tag_id):
		return HttpResponse("fail")

	if tag_id not in request.session['tags']:
		request.session['tags'].append(tag_id)
		request.session.modified = True
	return HttpResponseRedirect(reverse('letter.views.index'))

def delkeyword(request, param):
	request.session.setdefault('tags', [])

	try:
		tag_id = int(param)
	except (ValueError, TypeError):
		return HttpResponse("fail")

	request.session['tags'].remove(tag_id)
	request.session.modified = True

	return HttpResponseRedirect(reverse('letter.views.index'))

def userdata(request):
	request.session.setdefault('companies', [])
	selected_companies = Organisation.objects.filter(pk__in = request.session['companies'])

	if request.method == 'POST':
		form = UserForm(request.POST)
		if form.is_valid():
			request.session['firstname'] = form.cleaned_data['firstname']
			request.session['lastname'] = form.cleaned_data['lastname']
			request.session['street_address'] = form.cleaned_data['street_address']
			request.session['postcode'] = form.cleaned_data['postcode']
			request.session['city'] = form.cleaned_data['city']
			return HttpResponseRedirect(reverse('letter.views.generate'))
	else:
		form = UserForm()

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
		return HttpResponse("fail")

	if company_id not in request.session['companies']:
		return HttpResponse("fail")

	required_keys = (
		'firstname',
		'lastname',
		'street_address',
		'postcode',
		'city'
	)
	# <= means issubset
	if required_keys <= request.session.keys():
		return HttpResponse("fail")

	try:
		organisation = Organisation.objects.get(pk=company_id)
	except Organisation.DoesNotExist:
		return HttpResponse("fail")

	return render_to_response('pim/generatehtml.html',
		{
		'organisation': organisation,
		'firstname': request.session['firstname'],
		'lastname': request.session['lastname'],
		'street_address': request.session['street_address'],
		'postcode': request.session['postcode'],
		'city': request.session['city']
		})

def generatepdf(request, param):
	request.session.setdefault('companies', [])

	try:
		company_id = int(param)
	except (ValueError, TypeError):
		return HttpResponse("fail")

	if company_id not in request.session['companies']:
		return HttpResponse("fail")

	try:
		organisation = Organisation.objects.get(pk=company_id)
	except Organisation.DoesNotExist:
		return HttpResponse("fail")

	response = HttpResponse(mimetype='application/pdf')
	response['Content-Disposition'] = "attachment; filename=brief_bedrijf%d.pdf" % (company_id)

	p = canvas.Canvas(response)
	p.drawString(1, 1, "Onderwerp: Verzoeken in het kader van de Wet bescherming persoonsgegevens (Wbp)")
	p.drawString(2, 2, "Geachte Heer/Mevrouw,")
	p.showPage()
	p.save()

	return response
