from django.conf.urls.defaults import *

urlpatterns = patterns('',
    (r'^$', 'letter.views.index'),
    (r'^(?P<param>\d+)$', 'letter.views.index'),
    (r'^addcity/(?P<param>\d+)$', 'letter.views.addcity'),
    (r'^delcity/(?P<param>\d+)$', 'letter.views.delcity'),
    (r'^addkeyword/(?P<param>\d+)$', 'letter.views.addkeyword'),
    (r'^delkeyword/(?P<param>\d+)$', 'letter.views.delkeyword'),
    (r'^delcompany/(?P<param>\d+)$', 'letter.views.delcompany'),
    (r'^userdata$', 'letter.views.userdata'),
    (r'^generate$', 'letter.views.generate'),
    (r'^generatepdf/(?P<param>\d+)$', 'letter.views.generatepdf'),
)
