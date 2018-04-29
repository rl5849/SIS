import urllib2
import json
import unittest
import os, sys
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))) + "/API")
import sis_api as api


class SetupTestCheckOutput(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetStudentInfo?student_id=309"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info = self.data.get("student_info")
		#print (json.dumps(self.data, indent=4))

class TestCheckOutput(SetupTestCheckOutput):	
	
	def test_atleast_one_info(self):
		self.assertGreaterEqual(len(self.info), 1,
						"Expected >= 1, Found '" + str(len(self.info)) + "'")
	
	def test_return_format(self):
		list_info = self.info[len(self.info)-1]
		self.assertTrue("profile_pic" in list_info)
		self.assertTrue("major" in list_info)
		self.assertTrue("gender" in list_info)
		self.assertTrue("GPA" in list_info)
		self.assertTrue("prof_requested" in list_info)
		self.assertTrue("date_of_birth" in list_info)
		self.assertTrue("major_name" in list_info)
		self.assertTrue("graduation_year" in list_info)
		self.assertTrue("student_name" in list_info)
		self.assertTrue("student_id" in list_info)

	
	# def test_delete_success(self):
	# 	self.assertEqual(self.data, api.SUCCESS_MESSAGE,
	# 			"Expected '" + str(api.SUCCESS_MESSAGE) + 
	# 			"', Found '" + str(self.data) + "'")
	# def test_auto(self):
	# 	self.assertTrue(True)

if __name__ == '__main__':
	unittest.main()
#Class id 6, Course id 3
# {
#     "current_semester": 10
# }

#         {
#             "username": "lak1044",
#             "user_status": 0,
#             "user_id": 309,
#             "name": "Lucas Kretvix"
#         },

# 	{
#     "users": [
#         {
#             "username": "admin",
#             "user_status": 2,
#             "user_id": 67,
#             "name": "Admin"
#         },
#         {
#             "username": "chris",
#             "user_status": 2,
#             "user_id": 76,
#             "name": "Chris"
#         },
#         {
#             "username": "benchristians",
#             "user_status": 2,
#             "user_id": 77,
#             "name": "Ben Christians"
#         },
#         {
#             "username": "robertliedka",
#             "user_status": 0,
#             "user_id": 88,
#             "name": "Robert Liedka"
#         },
#         {
#             "username": "johnmurray",
#             "user_status": 0,
#             "user_id": 90,
#             "name": "John P. Murray"
#         },
#         {
#             "username": "TestUser",
#             "user_status": 0,
#             "user_id": 134,
#             "name": null
#         },
#         {
#             "username": "TestUser1",
#             "user_status": 2,
#             "user_id": 135,
#             "name": null
#         },
#         {
#             "username": "TestUser2",
#             "user_status": 2,
#             "user_id": 136,
#             "name": null
#         },
#         {
#             "username": "Test123",
#             "user_status": 2,
#             "user_id": 159,
#             "name": "Test User"
#         },
#         {
#             "username": "test1",
#             "user_status": 0,
#             "user_id": 229,
#             "name": "test1"
#         },
#         {
#             "username": "lak1044",
#             "user_status": 0,
#             "user_id": 309,
#             "name": "Lucas Kretvix"
#         },
#         {
#             "username": "benstudent",
#             "user_status": 0,
#             "user_id": 312,
#             "name": "Ben Christians (student account)"
#         },
#         {
#             "username": "prof1",
#             "user_status": 2,
#             "user_id": 314,
#             "name": "Professor 1"
#         },
#         {
#             "username": "student1",
#             "user_status": 0,
#             "user_id": 316,
#             "name": "student 1"
#         },
#         {
#             "username": "testprof",
#             "user_status": 1,
#             "user_id": 321,
#             "name": "testProf"
#         },
#         {
#             "username": "testProf2",
#             "user_status": 1,
#             "user_id": 323,
#             "name": "prof2"
#         },
#         {
#             "username": "captcha_test",
#             "user_status": 0,
#             "user_id": 325,
#             "name": null
#         },
#         {
#             "username": "test",
#             "user_status": 0,
#             "user_id": 327,
#             "name": null
#         },
#         {
#             "username": "captcha",
#             "user_status": 0,
#             "user_id": 329,
#             "name": null
#         },
#         {
#             "username": "salt",
#             "user_status": 0,
#             "user_id": 332,
#             "name": null
#         },
#         {
#             "username": "test_new",
#             "user_status": 0,
#             "user_id": 335,
#             "name": null
#         },
#         {
#             "username": "dsfasdf",
#             "user_status": 0,
#             "user_id": 337,
#             "name": null
#         },
#         {
#             "username": "student2",
#             "user_status": 0,
#             "user_id": 339,
#             "name": null
#         },
#         {
#             "username": "penis1234",
#             "user_status": 0,
#             "user_id": 342,
#             "name": "Adolf Stalin"
#         }
#     ]
# }
