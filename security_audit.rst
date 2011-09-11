======================================
BOF's PIM - Privacy Inspectie Machine
======================================
Security audit
--------------------------------------

Laag risico
--------------------------------------
Voorkomen caching van brieven
*****************************

**Probleem**

Op dit moment wordt niet expliciet aan de browsers verteld dat de brieven niet
opgeslagen mogen worden. Hierdoor worden ze mogelijk enige tijd in de browsercache bewaard - waardoor ze door (andere) gebruikers van een computer vrij eenvoudig terug te halen zijn.

**Oplossing**

Door het instellgen van een zogenaamde `no-cache` header in de response die Django stuurt kan het cachen door de browser voorkomen worden.

**Referenties**

* `Setting Response headers <http://docs.djangoproject.com/en/dev/ref/request-response/#setting-headers>`_

Verwijderen verlopen sessie-data uit database
*********************************************

**Probleem**

Het verwijderen van de door de gebruiker ingevulde sessie-data wordt niet automatisch verwijderd en wordt dus op dit moment tot in het einde der dagen bewaard.

**Oplossing**

Voor het verwijderen van verlopen sessie-data volstaat het regelmatig aanroepen van een script vanuit een cron-job.

**Referenties**

* `Clearing the session table <http://docs.djangoproject.com/en/dev/topics/http/sessions/#clearing-the-session-table>`_
* `Cron and virtualenv <http://stackoverflow.com/questions/3287038/cron-and-virtualenv>`_
* `How to set virtualenv for a crontab? <http://stackoverflow.com/questions/4150671/python-how-to-set-virtualenv-for-a-crontab>`_
