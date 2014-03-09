import math
import operator
import sys
import MySQLdb as mysql

# Circle class to represent a geometric Circle object
class Circle:
	def __init__(self, lat, lon, rad):
		if lat is not None:
			self.lat = lat
		else:
			self.lat = 0

		if lon is not None:
			self.lon = lon
		else:
			self.lon = 0
		
		# Default search radius is 5 miles
		if rad is not None and rad != 0:
			self.rad = rad
		else:
			self.rad = 10

class Line:
	def __init__(self, start_lat, start_lon, end_lat, end_lon):
		if start_lat is not None:
			self.start_lat = start_lat
		else:
			self.start_lat = 0

		if start_lon is not None:
			self.start_lon = start_lon
		else:
			self.start_lon = 0

		if end_lat is not None:
			self.end_lat = end_lat
		else:
			self.end_lat = 0

		if end_lon is not None:
			self.end_lon = end_lon
		else:
			self.end_lon = 0

		if self.start_lat == self.end_lat:
			self.end_lat += 1
		
		if self.start_lon == self.end_lon:
			self.end_lon += 1

class User:
	# Note: special depends on type.
	#	If typ == 'Request':	special corresponds to radius
	#	If typ == 'Offer':	special corresponds to passengers
	def __init__(self, typ, start_lat, start_lon, end_lat, end_lon, special):
		if typ == 1:
			self.typ = 'Request'
			self.rad = special
		else:
			self.typ = 'Offer'
			self.passengers = special

		if start_lat is not None:
			self.start_lat = start_lat
		else:
			self.start_lat = 0

		if start_lon is not None:
			self.start_lon = start_lon
		else:
			self.start_lon = 0

		if end_lat is not None:
			self.end_lat = end_lat
		else:
			self.end_lat = 0

		if end_lon is not None:
			self.end_lon = end_lon
		else:
			self.end_lon = 0


class Matcher:
	def __init__(self):
		#self.db = mysql.connect('localhost', 'collegecarpool', 'collegecarpool', 'purdue_test')
		self.db = mysql.connect('collegecarpool.us', 'root', 'collegecarpool', 'purdue_test')
	
	def get(self, listings_id):
		cursor = self.db.cursor()
		query = 'SELECT * FROM listings WHERE listings_id=' + listings_id
		cursor.execute(query)
		user = cursor.fetchone()
		cursor.close()
		user = User(user[7], user[2], user[3], user[5], user[6], user[8])
		return user
	
	def match(self, user):
		if user.typ == 'Request':
			matches = self.match_request_to_offer(user)
		else:
			matches = self.match_offer_to_request(user)
		for match in matches:
			print int(match[0] * 100), int(match[1])
	
	def match_request_to_offer(self, request):
		circle = Circle(request.end_lat, request.end_lon, request.rad)
		cursor = self.db.cursor()
		cursor.execute('SELECT * FROM listings WHERE isRequest=0')
		offers = []
		for _ in range(cursor.rowcount):
			row = cursor.fetchone()
			offers.append(row)
		cursor.close()

		scores = []
		for offer in offers:
			line = Line(offer[2], offer[3], offer[5], offer[6])
			score = self.score(self.dist(circle, line), circle.rad)
			scores.append([score, offer[0]])
		return sorted(scores, key=lambda score: score[0], reverse=True)
	
	def match_offer_to_request(self, offer):
		line = Line(offer.start_lat, offer.start_lon, offer.end_lat, offer.end_lon)
		cursor = self.db.cursor()
		cursor.execute('SELECT * FROM listings WHERE isRequest=1')
		requests = []
		for _ in range(cursor.rowcount):
			row = cursor.fetchone()
			requests.append(row)
		cursor.close()

		scores = []
		for request in requests:
			circle = Circle(request[5], request[6], request[8])
			score = self.score(self.dist(circle, line), circle.rad)
			scores.append([score, request[0]])
		return sorted(scores, key=lambda score: score[0], reverse=True)
	
	def dist(self, circle, line):
		px = line.end_lon - line.start_lon
		py = line.end_lat - line.start_lat

		u = ((circle.lon - line.start_lon) * px + (circle.lat - line.start_lat) * py) / float(px ** 2 + py ** 2)

		if u > 1:
			u = 1
		elif u < 0:
			u = 0

		lon = line.start_lon + u * px
		lat = line.start_lat + u * py
		dlon = lon - circle.lon
		dlat = lat - circle.lat

		# Convert a latitude/longitude distance into miles
		a = math.sin(dlat / 2.0) ** 2 + math.cos(line.end_lat) * math.cos(circle.lat) * (math.sin(dlon / 2.0) ** 2)
		c = 2 * math.atan2(math.sqrt(a), math.sqrt(1-a))
		d = 100 * c
		return d
	
	def score(self, distance, desired_radius):
		result = 1 - 0.25 * distance / desired_radius

		if result < 0:
			result = 0
		return result



if len(sys.argv) != 2:
	sys.exit('Usage: python matcher.py <listing_id>')

matcher = Matcher()
user = matcher.get(sys.argv[1])
matcher.match(user)
