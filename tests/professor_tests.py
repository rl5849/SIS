import urllib2
import json
import unittest
import os, sys
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))) + "/API")
import sis_api as api

class TestGetProfs(unittest.TestCase):

	def test_prof_name(self):
		url = "http://127.0.0.1:5002/GetProfs"
		response = urllib2.urlopen(url)
		data = json.load(response)
		profs = data.get("profs")
		self.assertEqual(len(profs), 2,
						 "Expected '2', Found '" + str(len(profs)) + "'")


class TestGetProfessorByID(unittest.TestCase):

	def test_prof_name(self):
		url = "http://127.0.0.1:5002/GetProfessorByID?professor_id=1"
		response = urllib2.urlopen(url)
		data = json.load(response)
		prof_name = data.get("professor_name")
		self.assertEqual(prof_name, "Krutz",
						 "Expected 'false', Found '" + str(prof_name) + "'")


class TestRequestProfessorApproval(unittest.TestCase):

	def test_request_success(self):
		url = "http://127.0.0.1:5002/RequestProfessorApproval"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")

if __name__ == '__main__':
	unittest.main()