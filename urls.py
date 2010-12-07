from django.conf.urls.defaults import *
from django.conf import settings

from django.contrib import admin
admin.autodiscover()

if settings.DEBUG:
    from staticfiles.urls import staticfiles_urlpatterns
    
    urlpatterns = staticfiles_urlpatterns()
    
    from os import path
    urlpatterns += patterns('django.views', (r'^%s(?P<path>.*)$' % settings.MEDIA_URL,
                                              'static.serve', 
                                             {'document_root': settings.MEDIA_ROOT }))
    
else:
    urlpatterns = patterns('')

urlpatterns += patterns('',
    (r'^', include('letter.urls')),

    # Django Admin
    (r'^admin/doc/', include('django.contrib.admindocs.urls')),
    (r'^admin/', include(admin.site.urls)),
    (r'^password_reset/', 'django.contrib.auth.views.password_reset'),
    (r'^password_reset_done/', 'django.contrib.auth.views.password_reset_done'),
)
