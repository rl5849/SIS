import urllib2
import json
import unittest
import os, sys
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))) + "/API")
import sis_api as api

class TestCheckIfAdmin(unittest.TestCase):

	def test_admin_check_fail(self):
		url = "http://127.0.0.1:5002/CheckIfAdmin?id=1"
		response = urllib2.urlopen(url)
		data = json.load(response)
		is_admin = data.get("is_admin")
		self.assertEqual(is_admin, False,
					"Expected 'false', Found '" + str(is_admin) + "'")

if __name__ == '__main__':
	unittest.main()