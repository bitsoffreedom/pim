from django.utils.http import urlencode
from django.utils.safestring import mark_safe
from django.utils.html import escape
from django.template import Library
from django.template.defaultfilters import stringfilter

register = Library()

@register.simple_tag
def paginator_url(query, page_id):
    context = {}
    if query:
        context['q'] = query
    if page_id:
        context['p'] = page_id
        
    return mark_safe('?%s' % (escape(urlencode(context))))

