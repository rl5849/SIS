import urllib2
import json
import unittest
import os, sys
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))) + "/API")
import sis_api as api

class SetupGetClasses(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetClasses"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.classes = self.data.get("classes")  # Change to 'classes'


class TestGetClasses(SetupGetClasses):

	"""
	data =
{
    "classs": [
        {
            "num_enrolled": 0,
            "capacity": 23,
            "name": "Beers of the World",
            "class_id": 3,
            "professor_id": 1,
            "section": 1,
            "room_number": 1,
            "credits": null,
            "time": null,
            "course_id": 1
        },
        {
            "num_enrolled": 0,
            "capacity": 23,
            "name": "Beers of the World",
            "class_id": 4,
            "professor_id": 1,
            "section": 2,
            "room_number": 1,
            "credits": null,
            "time": null,
            "course_id": 1
        },
        {
            "num_enrolled": 4,
            "capacity": 54,
            "name": "Web Eng.",
            "class_id": 5,
            "professor_id": 1,
            "section": 1,
            "room_number": 3,
            "credits": null,
            "time": null,
            "course_id": 2
        },
        {
            "num_enrolled": 4,
            "capacity": 54,
            "name": "Art",
            "class_id": 6,
            "professor_id": 1,
            "section": 1,
            "room_number": 3,
            "credits": null,
            "time": null,
            "course_id": 3
        }
    ]
}
	"""
	
	#def test_print(self):
	#	print (json.dumps(self.data, indent=4))

	def test_unique_ids(self):
		ids = []
		for class_id in self.classes:
			ids.append(class_id.get("class_id"))
		self.assertEqual(len(class_id), len(set(class_id)))


class SetupGetClassInfo(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetClassInfo?class_id=5"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.class_info = self.data.get("class_info")[0]


class TestGetClassInfo(SetupGetClassInfo):

	"""
	data =?
{
	class_info[
	 {
            "num_enrolled": 4,
            "capacity": 54,
            "name": "Web Eng.",
            "class_id": 5,
            "professor_id": 1,
            "section": 1,
            "room_number": 3,
            "credits": null,
            "time": null,
            "course_id": 2
        }
	]
	"""
	#def test_print(self):
	#	print (json.dumps(self.data, indent=4))

	def test_class_id(self):
		id = self.class_info.get("class_id")
		self.assertEqual(id, 5,
						 "Expected '5', Found '" + str(id) + "'")

	def test_class_course_id(self):
		course_id = self.class_info.get("course_id")
		self.assertEqual(course_id, 2,
						 "Expected '2', Found '" + str(course_id) + "'")
						 
	def test_num_enrolled_lt_capacity(self):
		enrolled = self.class_info.get("num_enrolled")
		capacity = self.class_info.get("capacity")
		self.assertGreaterEqual(capacity, enrolled,
						"Capacity: '" + str(capacity) + "' should be >= Enrolled: '" + str(enrolled) + "'")
						
	def test_class_name(self):
		name = self.class_info.get("name")
		self.assertEqual(name, "Web Engineering",
					"Expected 'Web Engineering', Found '" + str(name) + "'")


class TestAddClass(unittest.TestCase):

	def test_add_success(self):
		pass # Pass for now as this has not been implemented
		# url = "http://127.0.0.1:5002/AddClass"
		# response = urllib2.urlopen(url)
		# data = json.load(response)
		# self.assertEqual(data, api.SUCCESS_MESSAGE,
		# 			"Expected '" + str(api.SUCCESS_MESSAGE) +
		# 			"', Found '" + str(data) + "'")

class TestModClass(unittest.TestCase):

	def test_mod_success(self):
		url = "http://127.0.0.1:5002/ModClass"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")

class TestFavoriteClass(unittest.TestCase):

	def test_favorite_success(self):
		url = "http://127.0.0.1:5002/FavoriteClass"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")


class TestUnfavoriteClass(unittest.TestCase):

	def test_unfavorite_success(self):
		url = "http://127.0.0.1:5002/UnfavoriteClass"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")

class SetupGetFavoritedClasses(unittest.TestCase):  # Not implemented yet
	
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetFavoritedClasses"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)


class TestGetFavoritedClasses(SetupGetFavoritedClasses):

	# def test_print(self):
	# 	print (json.dumps(self.data, indent=4))

	def test_failure_message(self):
		self.assertEqual(self.data, api.FAILURE_MESSAGE,
					"Expected '" + str(api.FAILURE_MESSAGE) + 
					"', Found '" + str(self.data) + "'")

class TestWaitlistByClass(unittest.TestCase):

	def test_waitlist(self):
		url = "http://127.0.0.1:5002/WaitlistByClass?class_id=3"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(not data, True,
						 "Expected 'false', Found '" + str(not data) + "'")

if __name__ == '__main__':
	unittest.main()