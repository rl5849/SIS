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

class TestWaitlistByClass(unittest.TestCase):

	def test_waitlist(self):
		url = "http://127.0.0.1:5002/WaitlistByClass?class_id=3"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(not data, True,
						 "Expected 'false', Found '" + str(not data) + "'")

class SetupGetClassInfo(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetClassInfo?class_id=6"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info = self.data.get("class_info")
		#print (json.dumps(self.data, indent=4))

class TestGetClassInfo(SetupGetClassInfo):	
	
	def test_one_class(self):
		self.assertEqual(len(self.info), 1,
						"Expected '1', Found '" + str(len(self.info)) + "'")
	
	def test_return_format(self):
		course = self.info[0]
		self.assertTrue("num_enrolled" in course)
		self.assertTrue("semester_id" in course)
		self.assertTrue("capacity" in course)
		self.assertTrue("name" in course)
		self.assertTrue("class_id" in course)
		self.assertTrue("professor_id" in course)
		self.assertTrue("section" in course)
		self.assertTrue("room_number" in course)
		self.assertTrue("credits" in course)
		self.assertTrue("time" in course)
		self.assertTrue("course_id" in course)

class SetupGetStudentsByClassId(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetStudentsByClassId?class_id=6"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info = self.data.get("enrolled")
		#print (json.dumps(self.data, indent=4))

class TestGetStudentsByClassId(SetupGetStudentsByClassId):	
	
	def test_atleast_one_enrolled(self):
		self.assertGreaterEqual(len(self.info), 1,
						"Expected >= 1, Found '" + str(len(self.info)) + "'")
	
	def test_return_format(self):
		list_info = self.info[0]
		self.assertTrue("grade" in list_info)
		self.assertTrue("user_name" in list_info)
		self.assertTrue("user_id" in list_info)

class SetupGetAccessRequests(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetAccessRequests?"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info = self.data.get("requests")
		#print (json.dumps(self.data, indent=4))

class TestGetAccessRequests(SetupGetAccessRequests):	
	
	def test_atleast_one_request(self):
		self.assertGreaterEqual(len(self.info), 1,
						"Expected >= 1, Found '" + str(len(self.info)) + "'")
	
	def test_return_format(self):
		list_info = self.info[len(self.info)-1]
		self.assertTrue("class_name" in list_info)
		self.assertTrue("section" in list_info)
		self.assertTrue("request" in list_info)
		self.assertTrue("time" in list_info)
		self.assertTrue("course_code" in list_info)
		self.assertTrue("user_name" in list_info)

class SetupGetStudentAccess(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetStudentAccess?user_id=309&class_id=6"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info = self.data.get("requests")
		#print (json.dumps(self.data, indent=4))

class TestGetStudentAccess(SetupGetStudentAccess):	
	
	def test_atleast_one_request(self):
		self.assertGreaterEqual(len(self.info), 1,
						"Expected >= 1, Found '" + str(len(self.info)) + "'")


if __name__ == '__main__':
	unittest.main()