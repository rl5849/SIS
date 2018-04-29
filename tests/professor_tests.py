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
		self.assertGreaterEqual(len(profs), 1,
						 "Expected >= 1, Found '" + str(len(profs)) + "'")

class TestGetProfessorByID(unittest.TestCase):

	def test_prof_name(self):
		url = "http://127.0.0.1:5002/GetProfessorByID?professor_id=344"
		response = urllib2.urlopen(url)
		data = json.load(response)
		prof_name = data.get("professor_name")
		self.assertEqual(prof_name, "Dan Krutz",
						 "Expected 'false', Found '" + str(prof_name) + "'")

class SetupRequestProfessorApproval(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/RequestProfessorApproval?user_id=309"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		#print (json.dumps(self.data, indent=4))

class TestRequestProfessorApproval(SetupRequestProfessorApproval):
		
		def test_request_success(self):
			self.assertEqual(self.data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(self.data) + "'")

class SetupGetProfessorRequests(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetProfessorRequests?"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.requests = self.data.get("requests")
		# print (json.dumps(self.data, indent=4))

class TestGetProfessorRequests(SetupGetProfessorRequests):
		
		def test_atleast_one_request(self):
			self.assertGreater(self.requests, 0,
							"Expected > 0, Found '" + str(len(self.requests)) + "'")

class SetupDeleteProfRequest(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/DeleteProfRequest?user_id=309"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		#print (json.dumps(self.data, indent=4))

class TestDeleteProfRequest(SetupDeleteProfRequest):
		
		def test_delete_success(self):
			self.assertEqual(self.data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(self.data) + "'")

class SetupCheckIfProfessor(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/CheckIfProfessor?id=344"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.is_professor = self.data.get("is_prof")
		self.url = "http://127.0.0.1:5002/CheckIfProfessor?id=309"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.is_professor1 = self.data.get("is_prof")
		#print (json.dumps(self.data, indent=4))

class TestCheckIfProfessor(SetupCheckIfProfessor):
		
		def test_is_professor(self):
			self.assertTrue(self.is_professor,
							"Expected 'True', Found '" + str(self.is_professor) + "'")

		def test_is_not_professor(self):
			self.assertFalse(self.is_professor1,
							"Expected 'False', Found '" + str(self.is_professor1) + "'")


if __name__ == '__main__':
	unittest.main()