import urllib2
import json
import unittest
import os, sys
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))) + "/API")
import sis_api as api

class SetupCheckIfAdmin(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/CheckIfAdmin?id=67"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.is_admin = self.data.get("is_admin")
		self.url = "http://127.0.0.1:5002/CheckIfAdmin?id=309"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.is_admin1 = self.data.get("is_admin")
		#print (json.dumps(self.data, indent=4))

class TestCheckIfAdmin(SetupCheckIfAdmin):

		def test_is_admin(self):
			self.assertTrue(self.is_admin,
							"Expected 'True', Found '" + str(self.is_admin) + "'")

		def test_is_not_admin(self):
			self.assertFalse(self.is_admin1,
							"Expected 'False', Found '" + str(self.is_admin1) + "'")

class SetupMakeAdmin(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/MakeAdmin?id=67"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		#print (json.dumps(self.data, indent=4))

class TestMakeAdmin(SetupMakeAdmin):
		
		def delete_success(self):
			self.assertEqual(self.data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(self.data) + "'")



if __name__ == '__main__':
	unittest.main()