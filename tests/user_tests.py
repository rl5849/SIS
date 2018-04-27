import urllib2
import json
import unittest
import os, sys
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))) + "/API")
import sis_api as api

class TestAddUser(unittest.TestCase):

	def test_add_success(self):
		pass # Pass for now as this has not been implemented
		# url = "http://127.0.0.1:5002/AddUser"
		# response = urllib2.urlopen(url)
		# data = json.load(response)
		# self.assertEqual(data, api.SUCCESS_MESSAGE,
		# 			"Expected '" + str(api.SUCCESS_MESSAGE) +
		# 			"', Found '" + str(data) + "'")


class TestGetUserIDFromLinkedInID(unittest.TestCase):

	def test_prof_name(self):
		url = "http://127.0.0.1:5002/GetUserIDFromLinkedInID"
		pass  # Currently not implemented
# 		response = urllib2.urlopen(url)
# 		data = json.load(response)
# 		print (json.dumps(data, indent=4))
# 		profs = data.get("profs")
# 		self.assertEqual(len(profs), 2,
# 						 "Expected '2', Found '" + str(len(profs)) + "'")

if __name__ == '__main__':
	unittest.main()