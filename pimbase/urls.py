from django.conf.urls.defaults import *

urlpatterns = patterns('',
    (r'^$', 'pimbase.views.index'),
    (r'^(?P<param>\d+)$', 'pimbase.views.index'),
    (r'^addcitizenrole/(?P<param>\d+)$', 'pimbase.views.addcitizenrole'),
    (r'^delcitizenrole/(?P<param>\d+)$', 'pimbase.views.delcitizenrole'),
    (r'^addsector/(?P<param>\d+)$', 'pimbase.views.addsector'),
    (r'^delsector/(?P<param>\d+)$', 'pimbase.views.delsector'),
    (r'^addkeyword/(?P<param>\d+)$', 'pimbase.views.addkeyword'),
    (r'^delkeyword/(?P<param>\d+)$', 'pimbase.views.delkeyword'),
    (r'^addcompany/(?P<param>\d+)$', 'pimbase.views.addcompany'),
    (r'^delcompany/(?P<param>\d+)$', 'pimbase.views.delcompany'),
    (r'^userdata$', 'pimbase.views.userdata'),
    (r'^generate$', 'pimbase.views.generate'),
    (r'^generatehtml/(?P<param>\d+)$', 'pimbase.views.generatehtml'),
    (r'^generatepdf/(?P<param>\d+)$', 'pimbase.views.generatepdf'),
    (r'^datadetectives$', 'pimbase.views.datadetectives'),
)
