from letter.models import *
from django.contrib import admin

class OrganisationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ()
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    save_on_top = True
    inlines = []

admin.site.register(Organisation, OrganisationAdmin)

class RelationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ()
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    save_on_top = True
    inlines = []

admin.site.register(Relation, RelationAdmin)

class CityAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ()
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    save_on_top = True
    inlines = []

admin.site.register(City, CityAdmin)

class CountryAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ()
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    save_on_top = True
    inlines = []

admin.site.register(Country, CountryAdmin)

class SectorAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ()
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    save_on_top = True
    inlines = []

admin.site.register(Sector, SectorAdmin)

class BrandAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ()
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    save_on_top = True
    inlines = []

admin.site.register(Brand, BrandAdmin)

class ConsumerRelationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ()
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    save_on_top = True
    inlines = []

admin.site.register(ConsumerRelation, ConsumerRelationAdmin)

class ConsumerInformationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ()
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    save_on_top = True
    inlines = []

admin.site.register(ConsumerInformation, ConsumerInformationAdmin)
