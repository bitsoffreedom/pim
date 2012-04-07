from pimbase.models import *
from django.contrib import admin

from django.utils.translation import ugettext_lazy as _

class OrganisationAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ('name', 'html_link')
    list_filter = ('citizenrole', 'country', 'city')
    search_fields = [ 'name', 'address', 'postcode', 'city__name',
        'country__name', 'short_name' ]
    
    save_as = True

    prepopulated_fields = {"short_name": ("name",)}

    filter_horizontal = ('citizenrole', )


admin.site.register(Organisation, OrganisationAdmin)

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

class CitizenRoleAdmin(admin.ModelAdmin):
    date_hierarchy = ''
    list_display = ('name', 'label')
    list_filter = ()
    search_fields = []

    fieldsets = ()
    
    save_as = True
    inlines = []

admin.site.register(CitizenRole, CitizenRoleAdmin)

class OrganisationTypeAdmin(admin.ModelAdmin):
    model = OrganisationType
    extra = 1

admin.site.register(OrganisationType, OrganisationTypeAdmin)

