from django.conf.urls.defaults import *

urlpatterns = patterns('',
    (r'^$', 'pimbase.views.start'),
    (r'^start/$', 'pimbase.views.index'),

    (r'^morecollectedinfo', 'pimbase.views.morecollectedinfo'),
    (r'^lesscollectedinfo', 'pimbase.views.lesscollectedinfo'),
    (r'^setcollectedinfo/(?P<param>\d+)$', 'pimbase.views.setcollectedinfo'),
    (r'^delcollectedinfo$', 'pimbase.views.delcollectedinfo'),

    (r'^morecitizenroles', 'pimbase.views.morecitizenroles'),
    (r'^lesscitizenroles', 'pimbase.views.lesscitizenroles'),
    (r'^setcitizenrole/(?P<param>\d+)$', 'pimbase.views.setcitizenrole'),
    (r'^delcitizenrole/(?P<param>\d+)$', 'pimbase.views.delcitizenrole'),

    (r'^moreorganisationtypes', 'pimbase.views.moreorganisationtypes'),
    (r'^lessorganisationtypes', 'pimbase.views.lessorganisationtypes'),
    (r'^setcollectedinfo/(?P<param>\d+)$', 'pimbase.views.setcollectedinfo'),
    (r'^setorganisationtype/(?P<param>\d+)$', 'pimbase.views.setorganisationtype'),
    (r'^delorganisationtype/(?P<param>\d+)$', 'pimbase.views.delorganisationtype'),

    (r'^moresectors', 'pimbase.views.moresectors'),
    (r'^lesssectors', 'pimbase.views.lesssectors'),
    (r'^setsector/(?P<param>\d+)$', 'pimbase.views.setsector'),
    (r'^delsector/(?P<param>\d+)$', 'pimbase.views.delsector'),

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
