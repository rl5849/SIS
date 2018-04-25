import urllib2
import json
import unittest
import os, sys
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))) + "/API")
import sis_api as api


class SetupGetStudentsClasses(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetStudentsClasses?student_id=1"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.classes = self.data.get("students_classes")

class TestGetStudentsClasses(SetupGetStudentsClasses):

	#def test_print(self):
		#print (json.dumps(self.data, indent=4))

	def test_course_id(self):
		self.assertEqual(self.classes[0].get("course_id"), 1,
						 "Expected '1', Found '" + str(self.classes[0].get("course_id")) + "'")

	def test_course_name(self):
		self.assertEqual(self.classes[0].get("name"), "Beers of the World",
						 "Expected 'Beers of the World', Found '" + str(self.classes[0].get("name")) + "'")

	def test_number_of_courses(self):
		self.assertEqual(len(self.classes), 5,
						 "Expected '5', Found '" + str(len(self.classes)) + "'")

	def test_same_courseId_different_classId(self):
		self.assertEqual(self.classes[0].get("course_id"), self.classes[1].get("course_id"))
		self.assertNotEqual(self.classes[0].get("class_id"), self.classes[1].get("class_id"))

	def test_no_same_class_ID(self):
		class_ids = []
		for sClass in self.classes:
			class_ids.append(sClass.get("class_id"))
		self.assertEqual(len(class_ids), len(set(class_ids)))

class SetupGetStudentInfo(unittest.TestCase):

	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetStudentInfo?student_id=1"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.student_info = self.data.get("student_info")[0]


class TestGetStudentInfo(SetupGetStudentInfo):

	"""Returned info for id=1
	{
            "profile_pic": null,
            "major": "Software Engineering",
            "gender": "M",
            "graduation_year": 2100,
            "date_of_birth": 1996-02-11,
            "student_id": 1,
            "student_name": "Betty White"
        }
"""

	# def test_print(self):
	# 	print (json.dumps(self.data, indent=4))

	def test_major(self):
		self.assertEqual(self.student_info.get("major"), "Software Engineering",
						 "Expected 'Software Engineering, Found '" + str(self.student_info.get("major")) + "'")

	def test_gender(self):
		self.assertEqual(self.student_info.get("gender"), "M",
						 "Expected 'M', Found '" + str(self.student_info.get("gender")) + "'")

	def test_graduation_year(self):
		self.assertEqual(self.student_info.get("graduation_year"), 2100,
						 "Expected '2100', Found '" + str(self.student_info.get("graduation_year")) + "'")

	def test_date_of_birth(self):
		self.assertEqual(self.student_info.get("date_of_birth"), "Sat, 02 Nov 1996 00:00:00 GMT",
						 "Expected 'Sat, 02 Nov 1996 00:00:00 GMT', Found '" + str(self.student_info.get("date_of_birth")) + "'")

	def test_student_id(self):
		self.assertEqual(self.student_info.get("student_id"), 1,
						 "Expected '1', Found '" + str(self.student_info.get("student_id")) + "'")

	def test_student_name(self):
		self.assertEqual(self.student_info.get("student_name"), "Betty White",
						 "Expected 'Betty White', Found '" + str(self.student_info.get("student_name")) + "'")

class TestEnrollStudent(unittest.TestCase):

	def test_enroll_success(self):
		url = "http://127.0.0.1:5002/EnrollStudent"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")
                    
class TestAddStudent(unittest.TestCase):
	
	def test_adding(self):
		url = "http://127.0.0.1:5002/AddUser"
		name = "?&student_name=Lucas%20Kretvix"
		dob = "&date_of_birth=07-30-1996"
		prof_pic = "&profile_pic=https://i0.wp.com/radaronline.com/wp-content/uploads/2017/05/betty-white-secret-suitor-split-pp.jpg?fit=640%2C420&ssl=1"
		gender = "&gender=M"
		grad_year = "&graduation_year=2020"
		#response = urllib2.urlopen(url+name+dob+prof_pic+gender+grad_year)
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, "SUCCESS",
					"Expected 'SUCCESS', Found '" + str(data) + "'")
					
					
class TestDropStudent(unittest.TestCase):

	def test_drop_success(self):
		url = "http://127.0.0.1:5002/DropStudent"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")

if __name__ == '__main__':
	unittest.main()