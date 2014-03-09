import math
import operator
import sys
import MySQLdb as mysql

# Circle class to represent a geometric Circle object
class Circle:
	def __init__(self, x, y, radius):
		if x is not None:
			self.x = x
			self.longitude = x
		else:
			self.x = 0
			self.longitude = 0
		if y is not None:
			self.y = y
			self.latitude = y
		else:
			self.y = 0
			self.latitude = 0

		# Default search radius of 5 miles
		if radius is not None and radius != 0:
			self.radius = radius
		else:
			self.radius = 5
		
# Line class to represent a geometric Line object
class Line:
	def __init__(self, x1, y1, x2, y2):
		if x1 is not None:
			self.x1 = x1
			self.start_lat = x1
		else:
			self.x1 = 0
			self.start_lat = 0
		if x2 is not None:
			self.x2 = x2
			self.end_lat = x2
		else:
			self.x2 = 0
			self.end_lat = 0
		if y1 is not None:
			self.y1 = y1
			self.start_long = y1
		else:
			self.y1 = 0
			self.start_long = 0
		if y2 is not None:
			self.y2 = y2
			self.end_long = y2
		else:
			self.y2 = 0
			self.end_long = y2
			
		if self.x1 == self.x2:
			self.x2 += 1
			self.end_lat += 1
		if self.y1 == self.y2:
			self.y2 += 1
			self.end_long += 1
	
class Matcher:
	def __init__(self):
		self.db = mysql.connect('localhost', 'collegecarpool', 'collegecarpool', 'purdue_test')

	# Return a User object corresponding to the given listings_id
	def get(self, listings_id):
		cursor = self.db.cursor()
		query = 'SELECT * FROM listings WHERE listings_id=' + listings_id
		cursor.execute(query)
		user = cursor.fetchone()
		cursor.close()
		user = User(user[7], user[2], user[3], user[5], user[6], user[8])
		return user
		
	# Match a user to a list of rides of the opposite type
	def match(self, user):
		if user.type == 'Request':
			matches = self.match_request_to_offer(user)
		else:
			matches = self.match_offer_to_request(user)
		for match in matches:
			print int(match[0] * 100), int(match[1])
			
	# Match a request to a list of scored offers
	def match_request_to_offer(self, request):
		circle = Circle(request.end_long, request.end_lat, request.radius)
		
		#offers = get list of offers from database
		cursor = self.db.cursor()
		cursor.execute('SELECT * FROM listings WHERE isRequest=0')
		offers = []
		listing_ids = {}
		for _ in range(cursor.rowcount):
			row = cursor.fetchone()
			offers.append(row)
			listing_ids[row] = row[0]

		cursor.close()
		scores = []
		for offer in offers:
			line = Line(offer[2], offer[3], offer[5], offer[6])
			scores.append([self.score(self.dist_function(circle, line), circle.radius), listing_ids[offer]])
		
		sorted_scores = sorted(scores, key=lambda score: -score[0])
		return sorted_scores
	
	# Match an offer to a list of scored requests
	def match_offer_to_request(self, offer):
		line = Line(offer.start_long, offer.start_lat, offer.end_long, offer.end_lat)
		
		#requests = get list of requests from database
		cursor = self.db.cursor()
		cursor.execute('SELECT * FROM listings WHERE isRequest=1')
		result = self.db.use_result()
		requests = []
		listing_ids = {}
		for _ in range(cursor.rowcount):
			row = cursor.fetchone()
			requests.append(row)
			listing_ids[row] = row[0]

		cursor.close()
		scores = []
		for request in requests:
			circle = Circle(request[5], request[6], request[8])
			print 'Scoring id', listing_ids[request]
			scores.append([self.score(self.dist_function(circle, line), circle.radius), listing_ids[request]])
		
		sorted_scores = sorted(scores, key=lambda score: -score[0])
		return sorted_scores
	
	# Uses the geometric formula for the minimum distance from a point to a line.
	def dist_function(self, circle, line):
		RADIUS = 3961
		try:
			px = line.end_long - line.start_long
			py = line.end_lat - line.start_lat
			
			u = ((circle.longitude - line.start_long) * px + (circle.latitude - line.start_lat) * py) / float(px * px + py * py)
			
			if u > 1:
				u = 1
			elif u < 0:
				u = 0

			x = line.start_long + u * px
			y = line.start_lat + u * py
			
			dlon = x - circle.longitude
			dlat = y - circle.latitude

			a = math.sin(dlat / 2.0) ** 2 + math.cos(x) * math.cos(circle.longitude) * (math.sin(dlon / 2.0) ** 2)
			c = 2 * math.atan2(math.sqrt(a), math.sqrt(1-a))
			d = RADIUS * c

			print 'Distance in miles:', d
			
			return d
		except TypeError as err:
			return 5
		
	# Score a result by its distance to a given match
	# Currently uses a naive probabilistic function where...
	#	if distance == 0: 100% match
	#	if distance is at boundary of desired_radius: 75% match
	#	if distance is twice the desired_radius: 50% match
	#	if distance is 3x the desired_radius: 25% match
	#	if distance is 4+ times the desired radius: 0% match
	# TODO: this is probably a bad scoring function
	def score(self, distance, desired_radius):
		result = 1 - 0.25 * distance / desired_radius

		if result < 0:
			result = 0
		
		return result

class User:
	# Note: special_field depends on type.
	#	If type == 'Request':	special_field corresponds to radius
	#	If type == 'Offer':		special_field corresponds to number of passengers
	def __init__(self, type, start_lat, start_long, end_lat, end_long, special_field):
		if type == 1:
			self.type = 'Request'
			self.radius = special_field
		else:
			self.type = 'Offer'
			self.num_passengers = special_field
		
		if start_lat is not None:
			self.start_lat = start_lat
		else:
			self.start_lat = 0
		if start_long is not None:
			self.start_long = start_long
		else:
			self.start_long = 0
		if end_lat is not None:
			self.end_lat = end_lat
		else:
			self.end_lat = 0
		if end_long is not None:
			self.end_long = end_long
		else:
			self.end_long = 0

		
		
		
if len(sys.argv) != 2:
	sys.exit(1)

matcher = Matcher()
try:
	user = matcher.get(sys.argv[1])
	matcher.match(user)
except:
	pass
