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
