from pimbase.models import *
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
    fields = ('type', 'to_organisation')

class IdentifierInline(admin.TabularInline):
    model = Identifier
    extra = 1

class CBPRegistrationInline(admin.TabularInline):
    model = CBPRegistration
    extra = 1

class OrganisationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ('name', 'html_link')
    list_filter = ('collectedinformation', 'citizenrole', 'country', 'sector', 'comments')
    search_fields = ['name', 'short_name' , 'collectedinformation__type__name' ]
    
    save_as = True
    inlines = [RelationInline, IdentifierInline, CBPRegistrationInline]

    prepopulated_fields = {"short_name": ("name",)}

    filter_horizontal = ('collectedinformation', 'citizenrole')


admin.site.register(Organisation, OrganisationAdmin)

class RelationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ('from_organisation', 'to_organisation', 'type')
    list_filter = ('type', 'from_organisation', 'to_organisation')
    search_fields = ['from_organisation__name', 'to_organisation__name']
    fields = ('from_organisation', 'type', 'to_organisation')
    
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


class CitizenRoleAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ('name', 'label')
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    inlines = []

admin.site.register(CitizenRole, CitizenRoleAdmin)

class CollectedInformationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    #list_display = ()
    list_filter = ('type',)
    search_fields = []

    fieldsets = ()
    
    save_as = True
    inlines = []

admin.site.register(CollectedInformation, CollectedInformationAdmin)

class OrganisationTypeAdmin(admin.ModelAdmin):
    model = OrganisationType
    extra = 1

admin.site.register(OrganisationType, OrganisationTypeAdmin)

class CollectedInformationTypeAdmin(admin.ModelAdmin):
    model = CollectedInformationType
    extra = 1

admin.site.register(CollectedInformationType, CollectedInformationTypeAdmin)

admin.site.register(OrganisationTag)
admin.site.register(Identifier)


