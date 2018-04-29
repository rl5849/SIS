import urllib2
import json
import unittest
import os, sys
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))) + "/API")
import sis_api as api

class TestCheckEnrollmentStatus(unittest.TestCase):

	def test_enrollment(self):
		url = "http://127.0.0.1:5002/CheckEnrollmentStatus?class_id=3&user_id=1"
		response = urllib2.urlopen(url)
		data = json.load(response)
		status = data.get("enrollment_status")
		self.assertEqual(status, "NONE",
						 "Expected 'True', Found '" + str(status) + "'")

class TestGetCurrentSemester(unittest.TestCase):

	def test_semester(self):
		url = "http://127.0.0.1:5002/GetCurrentSemester"
		response = urllib2.urlopen(url)
		data = json.load(response)
		current_semester = data.get("current_semester")
		self.assertEqual(current_semester, 10,
						 "Expected '10', Found '" + str(current_semester) + "'")

class SetupEnrollFromWaitlist(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/EnrollFromWaitlist?"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		#print (json.dumps(self.data, indent=4))

class TestEnrollFromWaitlist(SetupEnrollFromWaitlist):	
	
	def test_delete_success(self):
		if self.data == "NO STUDENTS TO ENROLL":
			self.assertEqual(self.data, "NO STUDENTS TO ENROLL",
				"Expected '" + str("NO STUDENTS TO ENROLL") + 
				"', Found '" + str(self.data) + "'")
		else:
			self.assertEqual(self.data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(self.data) + "'")

class SetupGetSemesters(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetSemesters?"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info = self.data.get("semesters")
		#print (json.dumps(self.data, indent=4))

class TestGetSemesters(SetupGetSemesters):	
	
	def test_atleast_one_semester(self):
		self.assertGreaterEqual(len(self.info), 1,
						"Expected >= 1, Found '" + str(len(self.info)) + "'")
	
	def test_return_format(self):
		list_info = self.info[len(self.info)-1]
		self.assertEquals(list_info[0], 1)
		self.assertEquals(list_info[1], "Fall 2013")

class SetupGetMajors(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetMajors?"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info = self.data.get("majors")
		#print (json.dumps(self.data, indent=4))

class TestGetMajors(SetupGetMajors):	
	
	def test_atleast_one_request(self):
		self.assertGreaterEqual(len(self.info), 1,
						"Expected >= 1, Found '" + str(len(self.info)) + "'")
	
	def test_return_format(self):
		list_info = self.info[len(self.info)-1]
		self.assertTrue("major_id" in list_info)
		self.assertTrue("major_name" in list_info)


if __name__ == '__main__':
	unittest.main()
