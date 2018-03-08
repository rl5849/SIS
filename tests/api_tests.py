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


class TestAddCourse(unittest.TestCase):

	def test_add_success(self):
		pass # Pass for now as this has not been implemented
		# url = "http://127.0.0.1:5002/AddCourse"
		# response = urllib2.urlopen(url)
		# data = json.load(response)
		# self.assertEqual(data, api.SUCCESS_MESSAGE,
		# 			"Expected '" + str(api.SUCCESS_MESSAGE) +
		# 			"', Found '" + str(data) + "'")


class TestAddClass(unittest.TestCase):

	def test_add_success(self):
		pass # Pass for now as this has not been implemented
		# url = "http://127.0.0.1:5002/AddClass"
		# response = urllib2.urlopen(url)
		# data = json.load(response)
		# self.assertEqual(data, api.SUCCESS_MESSAGE,
		# 			"Expected '" + str(api.SUCCESS_MESSAGE) +
		# 			"', Found '" + str(data) + "'")


class TestAddUser(unittest.TestCase):

	def test_add_success(self):
		pass # Pass for now as this has not been implemented
		# url = "http://127.0.0.1:5002/AddUser"
		# response = urllib2.urlopen(url)
		# data = json.load(response)
		# self.assertEqual(data, api.SUCCESS_MESSAGE,
		# 			"Expected '" + str(api.SUCCESS_MESSAGE) +
		# 			"', Found '" + str(data) + "'")


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
	

class SetupGetFavoritedClasses(unittest.TestCase):  # Not implemented yet
	
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetFavoritedClasses"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)


class TestGetFavoritedClasses(SetupGetFavoritedClasses):

	# def test_print(self):
	# 	print (json.dumps(self.data, indent=4))

	def test_failure_message(self):
		self.assertEqual(self.data, api.FAILURE_MESSAGE,
					"Expected '" + str(api.FAILURE_MESSAGE) + 
					"', Found '" + str(self.data) + "'")


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


class SetupGetCourses(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetCourses"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.courses = self.data.get("courses")


class TestGetCourses(SetupGetCourses):
	"""
	data =
{
    "courses": [
        {
            "course_id": 1,
            "course_description": "Getting hammered w/ Krutz",
            "course_name": "Beers of the World"
        },
        {
            "course_id": 2,
            "course_description": "Lots of work",
            "course_name": "Web Eng."
        },
        {
            "course_id": 3,
            "course_description": "Lame",
            "course_name": "Art"
        }
    ]
}
	"""

	# def test_print(self):
	# 	print (json.dumps(self.data, indent=4))

	def test_unique_ids(self):
		course_ids = []
		for course in self.courses:
			course_ids.append(course.get("course_id"))
		self.assertEqual(len(course_ids), len(set(course_ids)))

	def test_course_name(self):
		course = self.courses[1].get("course_name")
		self.assertEqual(course, "Web Eng.",
						 "Expected 'Web Eng.', Found '" + str(course) + "'")

	def test_course_description(self):
		desc = self.courses[1].get("course_description")
		self.assertEqual(desc, "Lots of work",
						 "Expected 'Lots of work', Found '" + str(desc) + "'")


class SetupGetCourseInfo(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetCourseInfo?course_id=2"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.course = self.data.get("course_info")[0]


class TestGetCourseInfo(SetupGetCourseInfo):

	"""
	data =
{
    "course_info": [
        {
            "course_id": 2,
            "course_description": "Lots of work",
            "course_name": "Web Eng."
        }
    ]
}

	"""

	# def test_print(self):
	# 	print (json.dumps(self.data, indent=4))

	def test_course_id(self):
		id = self.course.get("course_id")
		self.assertEqual(id, 2,
						 "Expected '2', Found '" + str(id) + "'")

	def test_course_description(self):
		desc = self.course.get("course_description")
		self.assertEqual(desc, "Lots of work",
						 "Expected 'Lots of work', Found '" + str(desc) + "'")

	def test_course_name(self):
		name = self.course.get("course_name")
		self.assertEqual(name, "Web Eng.",
						 "Expected 'Web Eng.', Found '" + str(name) + "'")


class SetupGetClasses(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetClasses"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.classes = self.data.get("classes")  # Change to 'classes'


class TestGetClasses(SetupGetClasses):

	"""
	data =
{
    "classs": [
        {
            "num_enrolled": 0,
            "capacity": 23,
            "name": "Beers of the World",
            "class_id": 3,
            "professor_id": 1,
            "section": 1,
            "room_number": 1,
            "credits": null,
            "time": null,
            "course_id": 1
        },
        {
            "num_enrolled": 0,
            "capacity": 23,
            "name": "Beers of the World",
            "class_id": 4,
            "professor_id": 1,
            "section": 2,
            "room_number": 1,
            "credits": null,
            "time": null,
            "course_id": 1
        },
        {
            "num_enrolled": 4,
            "capacity": 54,
            "name": "Web Eng.",
            "class_id": 5,
            "professor_id": 1,
            "section": 1,
            "room_number": 3,
            "credits": null,
            "time": null,
            "course_id": 2
        },
        {
            "num_enrolled": 4,
            "capacity": 54,
            "name": "Art",
            "class_id": 6,
            "professor_id": 1,
            "section": 1,
            "room_number": 3,
            "credits": null,
            "time": null,
            "course_id": 3
        }
    ]
}
	"""
	
	#def test_print(self):
	#	print (json.dumps(self.data, indent=4))

	def test_unique_ids(self):
		ids = []
		for class_id in self.classes:
			ids.append(class_id.get("class_id"))
		self.assertEqual(len(class_id), len(set(class_id)))


class SetupGetClassInfo(unittest.TestCase):
	def setUp(self):
		self.url = "http://127.0.0.1:5002/GetClassInfo?class_id=5"
		self.response = urllib2.urlopen(self.url)
		self.data = json.load(self.response)
		self.class_info = self.data.get("class_info")[0]


class TestGetClassInfo(SetupGetClassInfo):

	"""
	data =?
{
	class_info[
	 {
            "num_enrolled": 4,
            "capacity": 54,
            "name": "Web Eng.",
            "class_id": 5,
            "professor_id": 1,
            "section": 1,
            "room_number": 3,
            "credits": null,
            "time": null,
            "course_id": 2
        }
	]
	"""
	#def test_print(self):
	#	print (json.dumps(self.data, indent=4))

	def test_class_id(self):
		id = self.class_info.get("class_id")
		self.assertEqual(id, 5,
						 "Expected '5', Found '" + str(id) + "'")

	def test_class_course_id(self):
		course_id = self.class_info.get("course_id")
		self.assertEqual(course_id, 2,
						 "Expected '2', Found '" + str(course_id) + "'")
						 
	def test_num_enrolled_lt_capacity(self):
		enrolled = self.class_info.get("num_enrolled")
		capacity = self.class_info.get("capacity")
		self.assertGreaterEqual(capacity, enrolled,
						"Capacity: '" + str(capacity) + "' should be >= Enrolled: '" + str(enrolled) + "'")
						
	def test_class_name(self):
		name = self.class_info.get("name")
		self.assertEqual(name, "Web Eng.",
					"Expected 'Web Eng.', Found '" + str(name) + "'")

					
class TestEnrollStudent(unittest.TestCase):

	def test_enroll_success(self):
		url = "http://127.0.0.1:5002/EnrollStudent"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")
					
					
class TestDropStudent(unittest.TestCase):

	def test_drop_success(self):
		url = "http://127.0.0.1:5002/DropStudent"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")


class TestCheckEnrollmentStatus(unittest.TestCase):

	def test_enrollment(self):
		url = "http://127.0.0.1:5002/CheckEnrollmentStatus?class_id=3&user_id=1"
		response = urllib2.urlopen(url)
		data = json.load(response)
		status = data.get("enrollment_status")
		self.assertEqual(status, "True",
						 "Expected 'True', Found '" + str(status) + "'")


class TestFavoriteClass(unittest.TestCase):

	def test_favorite_success(self):
		url = "http://127.0.0.1:5002/FavoriteClass"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")


class TestUnfavoriteClass(unittest.TestCase):

	def test_unfavorite_success(self):
		url = "http://127.0.0.1:5002/UnfavoriteClass"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")


class TestGetGrade(unittest.TestCase):

	def test_get_success(self):
		url = "http://127.0.0.1:5002/GetGrade"
		response = urllib2.urlopen(url)
		data = json.load(response)
		grade = data.get("grade")
		self.assertEqual(grade, "A",
					"Expected 'A', Found '" + str(grade) + "'")


class TestModClass(unittest.TestCase):

	def test_mod_success(self):
		url = "http://127.0.0.1:5002/ModClass"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")


class TestModCourse(unittest.TestCase):

	def test_mod_success(self):
		url = "http://127.0.0.1:5002/ModCourse"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")


class TestModProfessor(unittest.TestCase):

	def test_mod_success(self):
		url = "http://127.0.0.1:5002/ModProfessor"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")


class TestModProfile(unittest.TestCase):

	def test_mod_success(self):
		url = "http://127.0.0.1:5002/ModProfile"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")


class TestRequestProfessorApproval(unittest.TestCase):

	def test_request_success(self):
		url = "http://127.0.0.1:5002/RequestProfessorApproval"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(data, api.SUCCESS_MESSAGE,
					"Expected '" + str(api.SUCCESS_MESSAGE) + 
					"', Found '" + str(data) + "'")


class TestCheckIfAdmin(unittest.TestCase):

	def test_admin_check_fail(self):
		url = "http://127.0.0.1:5002/CheckIfAdmin?id=1"
		response = urllib2.urlopen(url)
		data = json.load(response)
		is_admin = data.get("is_admin")
		self.assertEqual(is_admin, False,
					"Expected 'false', Found '" + str(is_admin) + "'")


class TestGetProfessorByID(unittest.TestCase):

	def test_prof_name(self):
		url = "http://127.0.0.1:5002/GetProfessorByID?professor_id=1"
		response = urllib2.urlopen(url)
		data = json.load(response)
		prof_name = data.get("professor_name")
		self.assertEqual(prof_name, "Krutz",
						 "Expected 'false', Found '" + str(prof_name) + "'")


class TestWaitlistByClass(unittest.TestCase):

	def test_waitlist(self):
		url = "http://127.0.0.1:5002/WaitlistByClass?class_id=3"
		response = urllib2.urlopen(url)
		data = json.load(response)
		self.assertEqual(not data, True,
						 "Expected 'false', Found '" + str(not data) + "'")


class TestGetCurrentSemester(unittest.TestCase):

	def test_prof_name(self):
		url = "http://127.0.0.1:5002/GetCurrentSemester"
		response = urllib2.urlopen(url)
		data = json.load(response)
		current_semester = data.get("current_semester")
		self.assertEqual(current_semester, 1,
						 "Expected 'false', Found '" + str(current_semester) + "'")


class TestGetProfs(unittest.TestCase):

	def test_prof_name(self):
		url = "http://127.0.0.1:5002/GetProfs"
		response = urllib2.urlopen(url)
		data = json.load(response)
		profs = data.get("profs")
		self.assertEqual(len(profs), 2,
						 "Expected '2', Found '" + str(len(profs)) + "'")


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
