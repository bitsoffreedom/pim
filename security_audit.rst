======================================
BOF's PIM - Privacy Inspectie Machine
======================================
Security audit
--------------------------------------

Hoog risico
--------------------------------------

Datadiefstal persoonlijke gegevens dmv. sessie
**********************************************

**Probleem**

Op dit moment wordt de persoonlijke data die gebruikers invullen op de server bewaard. Nadat deze data is ingevoerd kan ze onbeperkt opnieuw uitgevraagd worden via het downloadadres van de brieven. Dit impliceert dat op die computer ingevulde data eenvoudig te achterhalen is - zolang de sessie geldig is.

**Oplossing**

Naast het automatisch laten vervallen van de sessie verkiest het de voorkeur om het herhaaldelijk opvragen van brieven te beperken. De simpelste manier om dit te bewerkstelligen is door dmv. een cookie bij te houden of een brief al eerder opgevraagd is.

**Referenties**

* `Django sessions <http://docs.djangoproject.com/en/dev/topics/http/sessions/>`_


Gemiddeld risico
--------------------------------------
Vervaldatum sessies
*******************

**Probleem**

Standaard staat Django zo ingesteld dat sessies *twee weken* geldig blijven. Dit impliceert dat de door gebruikers ingevoerde data hoe dan ook twee weken in onze database opgeslagen blijven. Het mag duidelijk zijn dat dit niet de bedoeling is. Daarnaast is het op dit moment mogelijk om gebruikersdata op te vorderen van de 'vorige' gebruiker van PIM, zie hiervoor ook `Datadiefstal persoonlijke gegevens dmv. sessie`.

**Oplossing**

Gelukkig is deze bedreiging betrekkelijk eenvoudig op te lossen door in de Django configuratie de timeout voor sessies te veranderen. Hierbij zijn de essentiële configuratie-opties: `SESSION_COOKIE_AGE` en `SESSION_EXPIRE_AT_BROWSER_CLOSE`. De eerste zou ik op iets van 300 seconden (5 minuten) zetten - de tweede op `True`.

**Referenties**

* `Django session settings <http://docs.djangoproject.com/en/dev/topics/http/sessions/#settings>`_


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
