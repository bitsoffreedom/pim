from letter.models import *
from django.contrib import admin

from django.utils.translation import ugettext_lazy as _

class RelationTypeAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    #list_display = ()
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    inlines = []

    prepopulated_fields = {"slug": ("name",)}


admin.site.register(RelationType, RelationTypeAdmin)

class RelationInline(admin.TabularInline):
	model = Relation
	fk_name = 'from_organisation'


class OrganisationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ('name',)
    list_filter = ('consumerinformation', 'consumerrelation')
    search_fields = ['name', 'short_name']
    
    save_as = True
    inlines = [RelationInline, ]

    prepopulated_fields = {"short_name": ("name",)}


admin.site.register(Organisation, OrganisationAdmin)

class RelationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    #list_display = ('name',)
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    inlines = []

admin.site.register(Relation, RelationAdmin)

class CityAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ('name',)
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    inlines = []

    prepopulated_fields = {"slug": ("name",)}


admin.site.register(City, CityAdmin)

class CountryAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ('name',)
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    inlines = []

    prepopulated_fields = {"slug": ("name",)}


admin.site.register(Country, CountryAdmin)

class SectorAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ('name',)
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    inlines = []

    prepopulated_fields = {"slug": ("name",)}


admin.site.register(Sector, SectorAdmin)

class BrandAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ('name',)
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    inlines = []

    prepopulated_fields = {"slug": ("name",)}


admin.site.register(Brand, BrandAdmin)

class ConsumerRelationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    #list_display = ()
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    inlines = []

admin.site.register(ConsumerRelation, ConsumerRelationAdmin)

class ConsumerInformationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    #list_display = ()
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    inlines = []

admin.site.register(ConsumerInformation, ConsumerInformationAdmin)
