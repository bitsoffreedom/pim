import binascii

def cbphex(c):
	CBPHEX = { '0': '8', '1': 'c', '2': '9', '3': 'a', '4': '0', '5': '1',
			'6': 'f', '7': 'd', '8': '3', '9': '4', 'a': 'e',
			'b': '5', 'c': 'b', 'd': '2', 'e': '6', 'f': '7', } 
	r = ""
	for l in c:
		r += CBPHEX[l]

	return r


def url_decode(url):
	url = url.replace("http://www.cbpweb.nl/asp/ORMelding.asp?id=", "")
	s =  binascii.unhexlify(cbphex(url))
	return s.split("!!")

