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
    extra = 1

class BrandInline(admin.TabularInline):
    model = Brand
    prepopulated_fields = {"slug": ("name",)}
    extra = 1

class IdentifierInline(admin.TabularInline):
    model = Identifier
    extra = 1

class OrganisationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ('name',)
    list_filter = ('collectedinformation', 'consumerrelation', 'country', 'sector')
    search_fields = ['name', 'short_name']
    
    save_as = True
    inlines = [RelationInline, BrandInline, IdentifierInline]

    prepopulated_fields = {"short_name": ("name",)}

    filter_horizontal = ('collectedinformation', 'consumerrelation')


admin.site.register(Organisation, OrganisationAdmin)

class RelationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ('from_organisation', 'to_organisation', 'type')
    list_filter = ('type','from_organisation','to_organisation')
    search_fields = ['from_organsiation__name', 'to_organisation__name']

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


class ConsumerRelationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    #list_display = ()
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    inlines = []

admin.site.register(ConsumerRelation, ConsumerRelationAdmin)

class CollectedInformationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    #list_display = ()
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    inlines = []

admin.site.register(CollectedInformation, CollectedInformationAdmin)
