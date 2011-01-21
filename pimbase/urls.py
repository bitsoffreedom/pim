from django.conf.urls.defaults import *

from pimbase.views import fm

urlpatterns = patterns('',
    (r'^$', 'pimbase.views.start'),
    (r'^start/$', 'pimbase.views.index'),

    (r'^', include(fm.urls)),

    (r'^addcompany/(?P<param>\d+)$', 'pimbase.views.addcompany'),
    (r'^delcompany/(?P<param>\d+)$', 'pimbase.views.delcompany'),
    (r'^cleancompanylist$', 'pimbase.views.cleancompanylist'),

    (r'^zoeken/addcompany/(?P<param>\d+)$', 'pimbase.views.addcompany2'),
    (r'^zoeken/delcompany/(?P<param>\d+)$', 'pimbase.views.delcompany2'),
    (r'^zoeken/cleancompanylist$', 'pimbase.views.cleancompanylist2'),

    (r'^gebruikers-gegevens$', 'pimbase.views.userdata'),
    (r'^genereer-brieven$', 'pimbase.views.generate'),
    (r'^generatehtml/(?P<param>\d+)$', 'pimbase.views.generatehtml'),
    (r'^zoeken/$', 'pimbase.views.textsearch'),
)
