import math
import operator

class Matcher:
	def Matcher(self):
		pass
		
	def match(self, user):
		#if user.type == request:
			match_request_to_offer(user)
		#else
			match_offer_to_request(user)
			
			
	def match_request_to_offer(self, request):
		#circle = Circle(request.lat, request.long, request.radius)
		
		#offers = get list of offers from database
		#scores = {}
		#for offer in offers:
			#line = Line(offer.startlat, offer.startlong, offer.endlat, offer.endlong)
			#scores[offer] = score(dist_function(circle, line), circle.radius)
		
		#sorted_scores = sorted(scores.items(), key=operator.itemgetter(1))
		#return sorted_scores
	
	def match_offer_to_request(self, offer):
		#line = Line(offer.startlat, offer.startlong, offer.endlat, offer.endlong)
		
		#requests = get list of requests from database
		#scores = {}
		#for request in requests:
			#circle = Circle(request.lat, request.long, request.radius)
			#scores[request] = score(dist_function(circle, line), circle.radius)
		
		#sorted_scores = sorted(scores.items(), key=operator.itemgetter(1))
		#return sorted_scores
	
	# Uses the geometric formula for the minimum distance from a point to a line.
	def dist_function(self, circle, line):
		px = line.x2 - line.x1
		py = line.y2 - line.y1
		
		u = ((circle.x - line.x1) * px + (circle.y - line.y1) * py) / float(px * px + py * py)
		
		if u > 1:
			u = 1
		elif u < 0:
			u = 0
		
		dx = line.x1 + u * px - circle.x
		dy = line.y1 + u * py - circle.y
		
		return math.sqrt(dx * dx + dy * dy)
		
	# Score a result by its distance to a given match
	# TODO: Make this probabilistic rather than have 3 set values hard-coded in
	def score(self, distance, desired_radius):
		# if the distance is within the desired radius, we have a perfect match
		if distance < desired_radius:
			return 1
		
		# if the distance is within double the radius, we have an okay match
		if distance < desired_radius * 2:
			return 0.5
		
		# otherwise, we have a bad match
		return 0
