import urllib2
import json
import unittest
import os, sys
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))) + "/API")
import sis_api as api

class TestCheckOutput(unittest.TestCase):

	def test_enrollment(self):
		url = "http://127.0.0.1:5002/GetStudentsClassesForSemester?user_id=1&semester_id=10"
		response = urllib2.urlopen(url)
		data = json.load(response)
		status = data.get("enrollment_status")
		print (json.dumps(data, indent=4))

if __name__ == '__main__':
	unittest.main()