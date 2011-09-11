from django import template
from django.template.defaultfilters import stringfilter

register = template.Library()

# truncate after a certain number of characters
@register.filter(name='truncchar')
@stringfilter
def truncchar(value, arg):
    if len(value) < arg:
        return value
    else:
        return value[:arg] + '...'

@register.inclusion_tag('pim/filter_status.html')
def show_filter_status(fm):
    selected = []
    for f in fm:
        if f.is_selected():
            o = f.definition.model.objects.get(pk=f.get_selected())
            selected_filter = {
                'url': '/start/unset_%s' % (f.definition.name, ),
                'name': getattr(o, f.definition.attr),
            }
            selected.append(selected_filter)

    context = {
        'selected': selected,
        'show_status': len(selected),
    }

    return context

@register.inclusion_tag('pim/filter.html')
def show_filter(filter_data):
    from pimbase.models import Organisation

    filter_context = { filter_data.definition.name: None }
    undefined = Organisation.objects.filter(**filter_context).count()

    show_more = filter_data.is_more()
    items = [
        {
            'name': getattr(i, filter_data.definition.attr),
            'id': i.pk,
            'count': i.organisation_set.count()
        }
    for i in filter_data.definition.model.objects.all()]

    context = {
        'title': filter_data.definition.title,
        'show_more': show_more,
        'items': items,
        'undefined': undefined,

        'show_less_url': ('/start/show_less_%s' % (filter_data.definition.name, )),
        'show_more_url': ('/start/show_more_%s' % (filter_data.definition.name, )),
        'set_url': ('/start/set_%s' % (filter_data.definition.name, )),
        'unset_url': ('/start/unset_%s' % (filter_data.definition.name, )),
    }

    return context;
