from os import path

# Set PROJECT_ROOT to the dir of the current file
PROJECT_ROOT = path.dirname(__file__)

DEBUG = True
TEMPLATE_DEBUG = DEBUG

SITE_ID = 1

# If you set this to False, Django will make some optimizations so as not
# to load the internationalization machinery.
USE_I18N = True

# Absolute path to the directory that holds media.
# Example: "/home/media/media.lawrence.com/"
MEDIA_ROOT = path.join(PROJECT_ROOT, 'static', 'media')

# URL that handles the media served from MEDIA_ROOT. Make sure to use a
# trailing slash if there is a path component (optional in other cases).
# Examples: "http://media.lawrence.com", "http://example.com/media/"
MEDIA_URL = '/static/media/'

# URL prefix for admin media -- CSS, JavaScript and images. Make sure to use a
# trailing slash.
# Examples: "http://foo.com/media/", "/media/".
ADMIN_MEDIA_PREFIX = '/static/global/admin/'

# django-staticfiles
STATIC_ROOT = path.join(PROJECT_ROOT, 'static', 'global')
STATIC_URL = '/static/global/'

# List of callables that know how to import templates from various sources.
TEMPLATE_LOADERS = (
    'django.template.loaders.filesystem.load_template_source',
    'django.template.loaders.app_directories.load_template_source',
#     'django.template.loaders.eggs.load_template_source',
)

MIDDLEWARE_CLASSES = [
    'django.middleware.common.CommonMiddleware',
    'django.contrib.sessions.middleware.SessionMiddleware',
    'django.contrib.auth.middleware.AuthenticationMiddleware',
]

# This is the default lis of context processors from Django
TEMPLATE_CONTEXT_PROCESSORS = [
    "django.contrib.auth.context_processors.auth",
    # "django.core.context_processors.debug", Not used
    #"django.core.context_processors.i18n", Not used
    "django.core.context_processors.media",
    #"django.core.context_processors.static", Django 1.3
    #"django.contrib.messages.context_processors.messages",
]

ROOT_URLCONF = 'urls'

TEMPLATE_DIRS = (
    path.join(PROJECT_ROOT, 'templates')
)

# Configuration for TinyMCE WYSIWYG editor
TINYMCE_DEFAULT_CONFIG = {
    'mode' : "textareas",
    'theme' : "advanced",
    'plugins' : "paste,table",
    
    'theme_advanced_buttons1' : "formatselect,|,undo,redo,|,bold,italic,underline,|,charmap,|,bullist,numlist,|,pastetext, pasteword,cleanup,code",
    'theme_advanced_buttons2' : "link,unlink",
    'theme_advanced_buttons3' : "",
    'theme_advanced_toolbar_location' : "top",
    'theme_advanced_toolbar_align' : "left",
    'theme_advanced_blockformats': "h1,h2,h3,h4,p",
    #'theme_advanced_path_location' : "bottom",
    
    'extended_valid_elements': 'a[name|href|target|title|onclick|rel]',

    'paste_create_paragraphs' : True,
    'paste_create_linebreaks' : True,
    'paste_use_dialog' : True,
    'paste_auto_cleanup_on_paste' : True,

    'theme_advanced_resizing' : True,
    'force_p_newlines' : True,
    
    'apply_source_formatting' : True,

    'verify_html' : True,

    'browsers' : "msie,gecko,opera",
    'entity_encoding' : "raw",

    'width' : 450,
    'height' : 350
}

# Log debug messages to standard output
if DEBUG:
    import logging
    logging.basicConfig(level=logging.DEBUG,
                        format='%(asctime)s %(levelname)-8s %(message)s',
                        datefmt='[%d/%b/%Y %H:%M:%S]')
    
    DEBUG_TOOLBAR_CONFIG = {
        'INTERCEPT_REDIRECTS' : False,
    }
    
    # Consider ourself as internal IP
    from socket import gethostname, gethostbyname
    INTERNAL_IPS = ( '127.0.0.1', 
                     gethostbyname(gethostname()),)
    
    # Default to SQLite database for debugging
    DATABASE_ENGINE = 'sqlite3'
    DATABASE_NAME = path.join(PROJECT_ROOT, 'database.sqlite')

INSTALLED_APPS = [
    'django.contrib.auth',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'django.contrib.sites',
    'django.contrib.messages',
    'django.contrib.admin',
    'django.contrib.admindocs',
    'django_extensions',
    'indexer',
    'paging',
    'sentry',
    'sentry.client',
    'staticfiles',
    'taggit',
    'pim.pimbase'
]
