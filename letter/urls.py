from django.conf.urls.defaults import *

urlpatterns = patterns('',
    (r'^$', 'letter.views.index'),
    (r'^(?P<param>\d+)$', 'letter.views.index'),
    (r'^addcitizenrole/(?P<param>\d+)$', 'letter.views.addcitizenrole'),
    (r'^delcitizenrole/(?P<param>\d+)$', 'letter.views.delcitizenrole'),
    (r'^addsector/(?P<param>\d+)$', 'letter.views.addsector'),
    (r'^delsector/(?P<param>\d+)$', 'letter.views.delsector'),
    (r'^addkeyword/(?P<param>\d+)$', 'letter.views.addkeyword'),
    (r'^delkeyword/(?P<param>\d+)$', 'letter.views.delkeyword'),
    (r'^addcompany/(?P<param>\d+)$', 'letter.views.addcompany'),
    (r'^delcompany/(?P<param>\d+)$', 'letter.views.delcompany'),
    (r'^userdata$', 'letter.views.userdata'),
    (r'^generate$', 'letter.views.generate'),
    (r'^generatehtml/(?P<param>\d+)$', 'letter.views.generatehtml'),
    (r'^generatepdf/(?P<param>\d+)$', 'letter.views.generatepdf'),
)
