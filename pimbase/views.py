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
    """ Search for specific organisationtype, sector or role. """
    
    org_list = Organisation.objects.all()

    otype = request.session['organisationtype']
    if otype:
        org_list = org_list.filter(organisationtype = otype)

    sector_id = request.session['sector']
    if sector_id:
        org_list = org_list.filter(sector = sector_id)

    role_id = request.session['role']
    if role_id:
        org_list = org_list.filter(citizenrole = role_id)

    return org_list

def index(request, param = None):
    # initialize the session
    request.session.setdefault('role', None)
    request.session.setdefault('companies', [])
    request.session.setdefault('sector', None)
    request.session.setdefault('organisationtype', None)
    request.session.setdefault('collectedinfo', None)
    request.session.setdefault('collectedinfo_more', None)
    request.session.setdefault('organisationtype_more', None)
    request.session.setdefault('citizenrole_more', None)
    request.session.setdefault('sector_more', None)

    # URL processing
    page_id = 1
    if param != None:
        try:
            page_id = int(param)
        except (ValueError, TypeError):
            return HttpResponseServerError("Invalid parameter")

    sectors = Sector.objects.all()
    citizenroles = CitizenRole.objects.all()
    orgtypes = OrganisationType.objects.all()
    collectedinfo = CollectedInformation.objects.all()

    selected_orgtype = None
    if request.session['organisationtype']:
        selected_orgtype = OrganisationType.objects.get(pk = \
            request.session['organisationtype'])

    selected_sector = None
    if request.session['sector']:
        selected_sector = Sector.objects.get(pk = \
            request.session['sector'])

    selected_role = None
    if request.session['role']:
        selected_role = CitizenRole.objects.get(pk = \
            request.session['role'])

    selected_collectedinfo = None
    if request.session['collectedinfo']:
        selected_collectedinfo = CollectedInformation.objects.get(pk = \
            request.session['collectedinfo'])

    selected_companies = Organisation.objects.filter(pk__in = request.session['companies'])
    org_list = search(request)
    org_count = org_list.count()
    paginator = Paginator(org_list, 30)

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
        'sectors': sectors,
        'citizenroles': citizenroles,
        'org_count': org_count,
        'organisations': org,
        'orgtypes': orgtypes,
        'collectedinfo': collectedinfo,
        'selected_collectedinfo': selected_collectedinfo,
        'selected_orgtype': selected_orgtype,
        'selected_sector': selected_sector,
        'selected_role': selected_role,
        'selected_companies': selected_companies,
        'search_range': search_range,
        'collectedinfo_more': request.session['collectedinfo_more'],
        'organisationtype_more': request.session['organisationtype_more'],
        'citizenrole_more': request.session['citizenrole_more'],
        'sector_more': request.session['sector_more'],
        },
        context_instance=RequestContext(request))

from simplesite.models import Page, Menu

def start(request):
    try:
        p = Page.objects.get(title='voorpagina')
    except (Page.DoesNotExist):
        return HttpResponseServerError("Object doesn't exist")
        
    menu_list = Menu.objects.filter(visible=True)
    return render_to_response('pim/start.html', {'page_current': p, 'menu_list': menu_list})

def morecollectedinfo(request):
    request.session['collectedinfo_more'] = True;

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def lesscollectedinfo(request):
    request.session['collectedinfo_more'] = False;

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def setcollectedinfo(request, param):
    request.session.setdefault('collectedinfo', [])
    try:
        cinfo_id = int(param)
    except (ValueError, TypeError):
        return HttpResponseServerError("Invalid parameter")

    if not CollectedInformation.objects.filter(pk=cinfo_id):
        return HttpResponseServerError("Object doesn't exist")

    request.session['collectedinfo'] = cinfo_id;

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def delcollectedinfo(request):
    request.session.setdefault('collectedinfo', [])

    del request.session['collectedinfo']

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def moreorganisationtypes(request):
    request.session['organisationtype_more'] = True;

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def lessorganisationtypes(request):
    request.session['organisationtype_more'] = False;

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def setorganisationtype(request, param):
    request.session.setdefault('organisationtype', [])
    try:
        otype_id = int(param)
    except (ValueError, TypeError):
        return HttpResponseServerError("Invalid parameter")

    if not OrganisationType.objects.filter(pk=otype_id):
        return HttpResponseServerError("Object doesn't exist")

    request.session['organisationtype'] = otype_id;

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def delorganisationtype(request, param):
    request.session.setdefault('organisationtype', [])

    try:
        otype_id = int(param)
    except (ValueError, TypeError):
        return HttpResponseServerError("Invalid parameter")

    del request.session['organisationtype']

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def morecitizenroles(request):
    request.session['citizenrole_more'] = True;

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def lesscitizenroles(request):
    request.session['citizenrole_more'] = False;

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def setcitizenrole(request, param):
    request.session.setdefault('role', [])
    try:
        role_id = int(param)
    except (ValueError, TypeError):
        return HttpResponseServerError("Invalid parameter")

    if not CitizenRole.objects.filter(pk=role_id):
        return HttpResponseServerError("Object doesn't exist")

    request.session['role'] = role_id;

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def delcitizenrole(request, param):
    request.session.setdefault('role', [])

    try:
        role_id = int(param)
    except (ValueError, TypeError):
        return HttpResponseServerError("Invalid parameter")

    del request.session['role']

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def moresectors(request):
    request.session['sector_more'] = True;

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def lesssectors(request):
    request.session['sector_more'] = False;

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def setsector(request, param):
    sectors = request.session.setdefault('sector', [])
    try:
        sector_id = int(param)
    except (ValueError, TypeError):
        return HttpResponseServerError("Invalid parameter")

    if not Sector.objects.filter(pk=sector_id):
        return HttpResponseServerError("Object doesn't exist")

    request.session['sector'] = sector_id;

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def delsector(request, param):
    request.session.setdefault('sector', [])

    try:
        sector_id = int(param)
    except (ValueError, TypeError):
        return HttpResponseServerError("Invalid parameter")

    del request.session['sector']

    return HttpResponseRedirect(reverse('pimbase.views.index'))

def cleancompanylist(request):
    request.session['companies'] = []
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

def userdata(request):
    request.session.setdefault('companies', [])
    if len(request.session['companies']) == 0:
        return HttpResponseServerError("No companies selected")
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
    if len(request.session['companies']) == 0:
        return HttpResponseServerError("No companies selected")
    selected_companies = Organisation.objects.filter(pk__in = request.session['companies'])

    return render_to_response('pim/generate.html', {'selected_companies': selected_companies})

def generatehtml(request, param):
    request.session.setdefault('companies', [])
    if len(request.session['companies']) == 0:
        return HttpResponseServerError("No companies selected")

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
    
