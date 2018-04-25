import urllib2
import json
import unittest
import os, sys
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))) + "/API")
import sis_api as api

class SetupGetCourses(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetCourses"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.courses = self.data.get("courses")


class TestGetCourses(SetupGetCourses):
	"""
	data =
{
    "courses": [
        {
            "course_id": 1,
            "course_description": "Getting hammered w/ Krutz",
            "course_name": "Beers of the World"
        },
        {
            "course_id": 2,
            "course_description": "Lots of work",
            "course_name": "Web Eng."
        },
        {
            "course_id": 3,
            "course_description": "Lame",
            "course_name": "Art"
        }
    ]
}
	"""

	# def test_print(self):
	# 	print (json.dumps(self.data, indent=4))

	def test_unique_ids(self):
		course_ids = []
		for course in self.courses:
			course_ids.append(course.get("course_id"))
		self.assertEqual(len(course_ids), len(set(course_ids)))

	def test_course_name(self):
		course = self.courses[1].get("course_name")
		self.assertEqual(course, "Web Eng.",
						 "Expected 'Web Eng.', Found '" + str(course) + "'")

	def test_course_description(self):
		desc = self.courses[1].get("course_description")
		self.assertEqual(desc, "Lots of work",
						 "Expected 'Lots of work', Found '" + str(desc) + "'")


class SetupGetCourseInfo(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetCourseInfo?course_id=2"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.course = self.data.get("course_info")[0]


class TestGetCourseInfo(SetupGetCourseInfo):

	"""
	data =
{
    "course_info": [
        {
            "course_id": 2,
            "course_description": "Lots of work",
            "course_name": "Web Eng."
        }
    ]
}

	"""

	# def test_print(self):
	# 	print (json.dumps(self.data, indent=4))

	def test_course_id(self):
		id = self.course.get("course_id")
		self.assertEqual(id, 2,
						 "Expected '2', Found '" + str(id) + "'")

	def test_course_description(self):
		desc = self.course.get("course_description")
		self.assertEqual(desc, "Lots of work",
						 "Expected 'Lots of work', Found '" + str(desc) + "'")

	def test_course_name(self):
		name = self.course.get("course_name")
		self.assertEqual(name, "Web Eng.",
						 "Expected 'Web Eng.', Found '" + str(name) + "'")

class TestAddCourse(unittest.TestCase):

	def test_add_success(self):
		pass # Pass for now as this has not been implemented
		# url = "http://127.0.0.1:5002/AddCourse"
		# response = urllib2.urlopen(url)
		# data = json.load(response)
		# self.assertEqual(data, api.SUCCESS_MESSAGE,
		# 			"Expected '" + str(api.SUCCESS_MESSAGE) +
		# 			"', Found '" + str(data) + "'")

class TestModCourse(unittest.TestCase):

	def test_mod_success(self):
		url = "http://127.0.0.1:5002/ModCourse"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")

if __name__ == '__main__':
	unittest.main()