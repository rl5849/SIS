import urllib2
import json
import unittest
import os, sys
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))) + "/API")
import sis_api as api


class SetupGetStudentInfo(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetStudentInfo?student_id=309"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info = self.data.get("student_info")
		#print (json.dumps(self.data, indent=4))

class TestGetStudentInfo(SetupGetStudentInfo):	
	
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

class TestEnrollStudent(unittest.TestCase):

	def test_enroll_success(self):
		url = "http://127.0.0.1:5002/EnrollStudent"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.FAILURE_MESSAGE,
					"Expected '" + str(api.FAILURE_MESSAGE) + 
					"', Found '" + str(data) + "'")
                    
class TestAddStudent(unittest.TestCase):
	
	def test_adding(self):
		url = "http://127.0.0.1:5002/AddUser"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, "SUCCESS",
					"Expected 'SUCCESS', Found '" + str(data) + "'")			
					
class TestDropStudent(unittest.TestCase):

	def test_drop_success(self):
		url = "http://127.0.0.1:5002/DropStudent"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.FAILURE_MESSAGE,
					"Expected '" + str(api.FAILURE_MESSAGE) + 
					"', Found '" + str(data) + "'")

################################## NEW TESTS ####################################

class SetupGetStudentsClassesForSemester(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetStudentsClassesForSemester?user_id=309&semester_id=10"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.classes = self.data.get("classes")
		#print (json.dumps(self.data, indent=4))

class TestGetStudentsClassesForSemester(SetupGetStudentsClassesForSemester):

	def test_atleast_one_class(self):
		self.assertGreater(len(self.classes), 0, 
						"Expected > 0, Found '" + str(len(self.classes)) + "'")
	
	def test_return_format(self):
		self.assertTrue(self.classes[0].get("name"),
						"Expected 'True', Found '" + str(self.classes[0].get("name")))
		self.assertTrue(self.classes[0].get("class_id"),
						"Expected 'True', Found '" + str(self.classes[0].get("class_id")))
		self.assertTrue(self.classes[0].get("section"),
						"Expected 'True', Found '" + str(self.classes[0].get("section")))
		self.assertTrue(self.classes[0].get("room_number"),
						"Expected 'True', Found '" + str(self.classes[0].get("room_number")))
		self.assertTrue(self.classes[0].get("professor_name"),
						"Expected 'True', Found '" + str(self.classes[0].get("professor_name")))
		self.assertFalse(self.classes[0].get("grade"),
						"Expected 'False', Found '" + str(self.classes[0].get("grade")))
		self.assertTrue(self.classes[0].get("time"),
						"Expected 'True', Found '" + str(self.classes[0].get("time")))
		self.assertTrue(self.classes[0].get("course_id"),
						"Expected 'True', Found '" + str(self.classes[0].get("course_id")))

class SetupCheckEnrollmentStatus(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/CheckEnrollmentStatus?user_id=309&class_id=6"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info = self.data.get("enrollment_status")
		self.url = "http://127.0.0.1:5002/CheckEnrollmentStatus?user_id=12345&class_id=6"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info1 = self.data.get("enrollment_status")
		#print (json.dumps(self.data, indent=4))

class TestCheckEnrollmentStatus(SetupCheckEnrollmentStatus):	

	def test_enrolled(self):
		self.assertEquals(self.info, "ENROLLED",
						"Expected 'ENROLLED', Found '" + str(self.info) + "'")
	def test_not_enrolled(self):
		self.assertEquals(self.info1, "NONE",
						"Expected 'NONE', Found '" + str(self.info1) + "'")

class SetupTestCheckOutput(unittest.TestCase):
	
	def setUp(self):
		self.url = "http://127.0.0.1:5002/CheckFavoriteStatus?user_id=309&class_id=6"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info = self.data.get("favorite_status")
		self.url = "http://127.0.0.1:5002/CheckFavoriteStatus?user_id=309&class_id=1"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.info1 = self.data.get("favorite_status")
		#print (json.dumps(self.data, indent=4))

class TestCheckOutput(SetupTestCheckOutput):	
	
	def test_enrolled(self):
		self.assertEquals(self.info, "True",
						"Expected 'TRUE', Found '" + str(self.info) + "'")
	
	def test_not_enrolled(self):
		self.assertEquals(self.info1, "False",
						"Expected 'False', Found '" + str(self.info1) + "'")



if __name__ == '__main__':
	unittest.main()