from django import template
from django.utils.http import urlencode
from django.utils.safestring import mark_safe
from django.utils.html import escape

register = template.Library()

# From: http://djangosnippets.org/snippets/556/ Author:pytechd
@register.filter()
def htmlentities(s):
    return mark_safe(escape(s).encode('ascii', 'xmlcharrefreplace'))

@register.simple_tag
def paginator_url(query, page_id):
    context = {}
    if query:
        context['q'] = query
    if page_id:
        context['p'] = page_id
        
    return mark_safe('?%s' % (escape(urlencode(context))))

