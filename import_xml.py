#!/usr/bin/env python

from lxml import etree
from import_utils import create_organisation, normalise_city

f = open('20120301220000.xml', 'r')

xml_tree = etree.parse(f)

namespaces={'p': 'http://almanak.overheid.nl/schema/export/2.0',}
gemeenten = xml_tree.xpath('/p:overheidsorganisaties/p:gemeenten/p:gemeente[p:type=\'Gemeente\']', namespaces=namespaces)

for g in gemeenten:
    # XXX: Currently deelgementen and stadsdelen (rotterdam, amsterdam) aren't processed.
    def f(name):
        node = g.xpath(name, namespaces=namespaces)
        assert len(node) == 1, "not a single item"
        return node[0].text

    context = {
    'name': f('p:naam'),
    'website': f('p:contact/p:internet'),
    'citizenrole': 'overheid',
    'organisationtype': 'overheid',
    }

    if g.xpath('.//p:contact/p:postAdres', namespaces=namespaces):
        print "postAdres found for gemeente: %s" % context['name'].encode('ascii', 'replace')
        context.update({
        'address': f('p:contact/p:postAdres/p:adres/p:postbus') ,
        'postcode': f('p:contact/p:postAdres/p:adres/p:postcode') ,
        'city': normalise_city(f('p:contact/p:postAdres/p:adres/p:plaats')),
        })
    elif g.xpath('.//p:contact/p:bezoekAdres', namespaces=namespaces):
        print "postAdres not found falling back on bezoekAdres for gemeente: %s" % context['name'].encode('ascii', 'replace')
        context.update({
        'address': "%s %s" %(f('p:contact/p:bezoekAdres/p:adres/p:straat'), f('p:contact/p:bezoekAdres/p:adres/p:huisnummer')) ,
        'postcode': f('p:contact/p:bezoekAdres/p:adres/p:postcode') ,
        'city': normalise_city(f('p:contact/p:bezoekAdres/p:adres/p:plaats')),
        })
    else:
        assert 0, "No address found in the tree"

    create_organisation(**context)
