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


if __name__ == '__main__':
	unittest.main()
