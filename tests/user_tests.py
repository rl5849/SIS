import urllib2
import json
import unittest
import os, sys
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))) + "/API")
import sis_api as api


class SetupUserExists(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/UserExists?username=lak1044"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.exists = self.data.get("exists")
		self.url1 = "http://127.0.0.1:5002/UserExists?username=thispersonshouldntexist"
		self.response1 = urllib2.urlopen(self.url1)
		self.data1 = json.load(self.response1)
		self.exists1 = self.data1.get("exists")
		#print (json.dumps(self.data1, indent=4))

class TestUserExists(SetupUserExists):
		
		def test_exists(self):
			self.assertEquals(self.exists, "True",
							"Expected 'True', Found '" + str(self.exists) + "'")
		
		def test_doesnt_exist(self):
			self.assertEquals(self.exists1, "False",
							"Expected 'False', Found '" + str(self.exists1) + "'")

class SetupGetUserIDFromLogin(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetUserIDFromLogin?user_name=test&password=test"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.user_id = self.data.get("user_id")
		#print (json.dumps(self.data, indent=4))

class TestGetUserIDFromLogin(SetupGetUserIDFromLogin):
		
		def test_user_id(self):
			self.assertFalse(self.user_id,
							"Expected 'False', Found '" + str(self.user_id) + "'")

class SetupTestCheckOutput(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetUsers?"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.users = self.data.get("users")
		#print (json.dumps(self.data, indent=4))

class TestCheckOutput(SetupTestCheckOutput):

	def test_atleast_one_user(self):
		self.assertGreaterEqual(len(self.users), 1,
						"Expected 'True', Found '" + str(len(self.users)) + "'")
	
	def test_return_format(self):
		self.assertTrue(self.users[0].get("username"),
						"Expected 'True', Found '" + str(self.users[0].get("username")))
		self.assertTrue(self.users[0].get("user_status"),
						"Expected 'True', Found '" + str(self.users[0].get("user_status")))
		self.assertTrue(self.users[0].get("user_id"),
						"Expected 'True', Found '" + str(self.users[0].get("user_id")))
		self.assertTrue(self.users[0].get("name"),
						"Expected 'True', Found '" + str(self.users[0].get("name")))


if __name__ == '__main__':
	unittest.main()