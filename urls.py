from django.conf.urls.defaults import *
from django.conf import settings

from django.contrib import admin
admin.autodiscover()

if settings.DEBUG:
    from staticfiles.urls import staticfiles_urlpatterns
    
    urlpatterns = staticfiles_urlpatterns()    
else:
    urlpatterns = patterns('')

urlpatterns += patterns('',
    (r'^start/', include('pimbase.urls')),

    # Sentry for catching exceptions in staging/production
    (r'^sentry/', include('sentry.urls')),

    # Django Admin
    (r'^admin/doc/', include('django.contrib.admindocs.urls')),
    (r'^admin/', include(admin.site.urls)),
    
    # Password management. Consider using django-registration for this stuff, it's great!
    (r'^password_reset/$', 'django.contrib.auth.views.password_reset'),
    (r'^password_reset_done/$', 'django.contrib.auth.views.password_reset_done'),
    (r'^password_reset_confirm/(?P<uidb36>[0-9A-Za-z]+)-(?P<token>.+)/$$', 'django.contrib.auth.views.password_reset_confirm'),
    (r'^password_reset_done/$', 'django.contrib.auth.views.password_reset_complete'),

)
