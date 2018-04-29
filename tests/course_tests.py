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

	def test_unique_ids(self):
		course_ids = []
		for course in self.courses:
			course_ids.append(course.get("course_id"))
		self.assertEqual(len(course_ids), len(set(course_ids)))

	def test_course_name(self):
		course = self.courses[1].get("course_name")
		self.assertEqual(course, "Web Engineering",
						 "Expected 'Web Engineering', Found '" + str(course) + "'")

	def test_course_description(self):
		desc = self.courses[1].get("course_description")
		self.assertEqual(desc, "Lots of work",
						 "Expected 'Lots of work', Found '" + str(desc) + "'")

class TestAddCourse(unittest.TestCase):

	def test_add_success(self):
		url = "http://127.0.0.1:5002/AddCourse"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) +
					"', Found '" + str(data) + "'")

class TestModCourse(unittest.TestCase):

	def test_mod_success(self):
		url = "http://127.0.0.1:5002/ModCourse"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")

################# NEW TESTS ######################
class SetupGetPrereqs(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetPrereqs?course_id=1"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.prereqs = self.data.get("prereqs")
		#print (json.dumps(self.data, indent=4))

class TestGetPrereqs(SetupGetPrereqs):
	
	def test_atleast_one_prereq(self):
		self.assertGreaterEqual(len(self.prereqs), 1,
						"Expected >= 1, Found '" + str(len(self.prereqs)) + "'")
	
	def test_return_format(self):
		pre = self.prereqs[0]
		self.assertTrue("program_of_enrollment" in pre)
		self.assertTrue("year_level" in pre)
		self.assertTrue("prereq_id" in pre)
		self.assertTrue("prereq_course" in pre)
		self.assertTrue("major_name" in pre)
		self.assertTrue("prereq_course_id" in pre)
		self.assertTrue("type" in pre)

class SetupCheckPrereq(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/CheckPrereq?student_id=309&prereq_id=7"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info = self.data.get("meets_prereq")
		#print (json.dumps(self.data, indent=4))

class TestCheckPrereq(SetupCheckPrereq):	
	
	def test_does_not_meet_prereq(self):
		self.assertFalse(self.info)

class SetupGetCourseList(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetCourseList?"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info = self.data.get("classes")
		#print (json.dumps(self.data, indent=4))

class TestGetCourseList(SetupGetCourseList):

	def test_atleast_one_prereq(self):
		self.assertGreaterEqual(len(self.info), 1,
						"Expected >= 1, Found '" + str(len(self.info)) + "'")
	
	def test_return_format(self):
		course = self.info[0]
		self.assertTrue("name" in course)
		self.assertTrue("class_id" in course)
		self.assertTrue("section" in course)
		self.assertTrue("room_number" in course)
		self.assertTrue("professor_name" in course)
		self.assertTrue("time" in course)

class SetupGetCourseInfo(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetCourseInfo?course_id=1"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info = self.data.get("course_info")
		#print (json.dumps(self.data, indent=4))

class TestGetCourseInfo(SetupGetCourseInfo):	
	
	def test_one_course(self):
		self.assertEqual(len(self.info), 1,
						"Expected '1', Found '" + str(len(self.info)) + "'")
	
	def test_return_format(self):
		course = self.info[0]
		self.assertTrue("course_id" in course)
		self.assertTrue("credits" in course)
		self.assertTrue("course_code" in course)
		self.assertTrue("course_name" in course)
		self.assertTrue("course_description" in course)

if __name__ == '__main__':
	unittest.main()