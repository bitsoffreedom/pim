from django.conf import settings

class CookieExpireMiddelware(object):
    """ Use a different cookie expire date for staff so they can mess around in
    the administrative interface """

    def process_request(self, request):
        user = request.user

        if user.is_staff:
            request.session.set_expiry(settings.STAFF_SESSION_COOKIE_AGE)

        return None
