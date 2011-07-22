from django.conf.urls.defaults import *

from pimbase.views import fm

urlpatterns = patterns('',
    (r'^$', 'pimbase.views.index'),
    (r'^start/$', 'pimbase.views.index'),

    (r'^', include(fm.urls)),

    (r'^addcompany/(?P<param>\d+)$', 'pimbase.views.addcompany'),
    (r'^delcompany/(?P<param>\d+)$', 'pimbase.views.delcompany'),
    (r'^cleancompanylist$', 'pimbase.views.cleancompanylist'),

    (r'^gebruikers-gegevens$', 'pimbase.views.userdata'),
    (r'^genereer-brieven$', 'pimbase.views.generate'),
    (r'^generatehtml/(?P<param>\d+)$', 'pimbase.views.generatehtml'),
    (r'^generatepdf/(?P<param>\d+)$', 'pimbase.views.generatepdf'),
)
