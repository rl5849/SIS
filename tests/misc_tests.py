import urllib2
import json
import unittest
import os, sys
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))) + "/API")
import sis_api as api

class TestCheckOutput(unittest.TestCase):

	def test_output(self):
		url = "http://127.0.0.1:5002/GetStudentsClassesForSemester?user_id=1&semester_id=10"
		response = urllib2.urlopen(url)
		data = json.load(response)
		status = data.get("enrollment_status")
		print (json.dumps(data, indent=4))
		
# {
#     "student_info": [
#         {
#             "profile_pic": "https://i0.wp.com/radaronline.com/wp-content/uploads/2017/05/betty-white-secret-suitor-split-pp.jpg?fit=640%2C420&ssl=1",
#             "major": 4,
#             "gender": "F",
#             "GPA": 4.12,
#             "date_of_birth": null,
#             "major_name": "Economics",
#             "graduation_year": 2020,
#             "student_name": "Betty White",
#             "student_id": 1
#         }
#     ]
# }
#########################################
# {
#     "current_semester": 10
# }



if __name__ == '__main__':
	unittest.main()