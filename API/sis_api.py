import MySQLdb
import ConfigParser, os
from flask import Flask, request
from flask_restful import Resource, Api, reqparse
from flask import jsonify


app = Flask(__name__)
api = Api(app)

SUCCESS_MESSAGE = "SUCCESS"
FAILURE_MESSAGE = "FAILURE"

###Use a student ID to get all their classes currently enrolled
class GetStudentsClasses(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get student id
        parser = reqparse.RequestParser()
        parser.add_argument('student_id', type=int)
        student_id = parser.parse_args().get("student_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT * FROM classes "
                    "LEFT JOIN student_to_class ON (classes.class_id = student_to_class.class_id) "
                    "WHERE student_to_class.student_id = %s "
                    "ORDER BY classes.name",
                    [student_id])
        query = cur.fetchall()
        # Get variable names
        cur.execute(
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'sis_data' AND table_name = 'classes'")

        column_names = cur.fetchall()
        column_names_clean = [x[0] for x in column_names]

        result = {'students_classes': [dict(zip(
            column_names_clean, i)) for i in query]}
        return jsonify(result)
        
api.add_resource(GetStudentsClasses, '/GetStudentsClasses')


###Add a new course
### url path: /add_course?course_name=NAME&date_of_birth=2001-02-01&profile_pic=www.linked.com&gender=F&graduation_year=2018
class AddCourse(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get course info
        parser = reqparse.RequestParser()
        parser.add_argument('course_name', type=str)
        parser.add_argument('course_code', type=str)
        parser.add_argument('course_credits', type=int)
        parser.add_argument('course_description', type=str)

        parsed = parser.parse_args()
        
        course_name = parsed.get("course_name")
        course_code = parsed.get("course_code")
        course_credits = parsed.get("course_credits")
        course_description = parsed.get("course_description")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("INSERT INTO courses"
                    "(course_name, course_description, course_code, credits) "
                    "VALUES (%s, %s, %s, %s);",
                    [course_name, course_description, course_code, course_credits])

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            return jsonify(FAILURE_MESSAGE)

        return jsonify(SUCCESS_MESSAGE)

api.add_resource(AddCourse, '/AddCourse')


class AddSemester(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get course info
        parser = reqparse.RequestParser()
        parser.add_argument('semester_code', type=str)

        parsed = parser.parse_args()

        semester_code = parsed.get("semester_code")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("INSERT INTO semesters"
                    "(description) "
                    "VALUES (%s);",
                    [semester_code])

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            return jsonify(FAILURE_MESSAGE)

        return jsonify(SUCCESS_MESSAGE)

api.add_resource(AddSemester, '/AddSemester')

###Add a new class
# TODO: CRITCAL: URL ENCODE THESE ITEMS BEFORE MAKING THE REQUEST
class AddClass(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get course info
        parser = reqparse.RequestParser()
        parser.add_argument('time', type=str)
        parser.add_argument('room_number', type=int)
        parser.add_argument('course_id', type=int)
        parser.add_argument('prof_id', type=int)
        parser.add_argument('capacity', type=int)

        parsed = parser.parse_args()

        time = parsed.get("time")
        room_number = parsed.get("room_number")
        course_id = parsed.get("course_id")
        prof_id = parsed.get("prof_id")
        capacity = parsed.get("capacity")


        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("INSERT INTO classes"
                    "(name, room_number, capacity, time, course_id, professor_id, credits, semester_id, section) "
                    "VALUES ("
                    "(SELECT course_name FROM courses WHERE course_id = %s), "
                    "%s, %s, %s, %s, %s, "
                    "(SELECT credits FROM courses WHERE course_id = %s), "
                    "(SELECT MAX(id) FROM semesters),"
                    "1)",
                    [course_id, room_number, capacity, time, course_id, prof_id, course_id])#TODO something to increment sections

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            return jsonify(FAILURE_MESSAGE)

        return jsonify(SUCCESS_MESSAGE)


api.add_resource(AddClass, '/AddClass')


###Add a new student
### url path: /add_student?student_name=NAME&date_of_birth=2001-02-01&profile_pic=www.linked.com&gender=F&graduation_year=2018
# TODO: CRITCAL: URL ENCODE THESE ITEMS BEFORE MAKING THE REQUEST
class AddUser(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get student info
        parser = reqparse.RequestParser()
        parser.add_argument('name', type=str)
        parser.add_argument('linkedin_id', type=str)
        parser.add_argument('profile_pic', type=str)

        parsed = parser.parse_args()

        name = parsed.get("name")
        linkedin_id = parsed.get("linkedin_id")
        profile_pic = parsed.get("profile_pic")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("INSERT INTO students"
                   "(student_name, profile_pic) "
                   "VALUES (%s, %s);",
                   [name, profile_pic])

        cur.execute("INSERT INTO users"
                    "(user_id, name, linkedin) "
                    "VALUES (LAST_INSERT_ID(), %s, %s);",
                    [name, linkedin_id])

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            return jsonify("INSERT FAILED!")

        return jsonify("SUCCESS")


api.add_resource(AddUser, '/AddUser')


"""
Gets all favorited classes for the student
"""
class GetFavoritedClasses(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')
    
    """
    Expected return from API Docs:
    ```JSON
      favorited_classes
        class1
        class2
        .
        .
        .
        classN
      ```
    """
    
    
    def get(self):
        return jsonify(FAILURE_MESSAGE)

api.add_resource(GetFavoritedClasses, '/GetFavoritedClasses')

"""
Get all information about a user
"""
#TODO: Determine if this is useful
class GetStudentInfo(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get student id
        parser = reqparse.RequestParser()
        parser.add_argument('student_id', type=int)
        student_id = parser.parse_args().get("student_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host='129.21.208.224',
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT * FROM students "
                    "WHERE student_id = %s",
                    [student_id])
        query = cur.fetchall()
        # Get variable names
        cur.execute(
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'sis_data' AND table_name = 'students'")

        column_names = cur.fetchall()
        column_names_clean = [x[0] for x in column_names]

        result = {'student_info': [dict(zip(
            column_names_clean, i)) for i in query]}

        return jsonify(result)

api.add_resource(GetStudentInfo, '/GetStudentInfo')

"""
Gets all courses in a given semester given other conditions
Specify a course id to get that course only
"""
class GetCourses(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get student id
        course_id = None
        try:
            parser = reqparse.RequestParser()
            parser.add_argument('course_id', type=int)
            course_id = parser.parse_args().get("course_id")
        except: #didnt specify one, so what
            pass

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        if course_id:
            cur.execute("SELECT * FROM courses "
                        "WHERE course_id = %s",
                        [course_id])
        else:
            cur.execute("SELECT * FROM courses")

        query = cur.fetchall()
        # Get variable names
        cur.execute(
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'sis_data' AND table_name = 'courses'")

        column_names = cur.fetchall()
        column_names_clean = [x[0] for x in column_names]

        result = {'courses': [dict(zip(
            column_names_clean, i)) for i in query]}

        return jsonify(result)

api.add_resource(GetCourses, '/GetCourses')


"""
Gets all information about a course

Note: GetCourses can do the same thing if given a specific ID,
may be unneccessary
"""
class GetCourseInfo(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get course id
        parser = reqparse.RequestParser()
        parser.add_argument('course_id', type=int)
        course_id = parser.parse_args().get("course_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT * FROM courses "
                    "WHERE course_id = %s",
                    [course_id])
        query = cur.fetchall()
        # Get variable names
        cur.execute(
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'sis_data' AND table_name = 'courses'")

        column_names = cur.fetchall()
        column_names_clean = [x[0] for x in column_names]

        result = {'course_info': [dict(zip(
            column_names_clean, i)) for i in query]}

        return jsonify(result)

api.add_resource(GetCourseInfo, '/GetCourseInfo')

"""
Gets all classes in a given semester given other conditions
Specify a course id to get that course only
"""
class GetClasses(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get class id
        class_id = None
        try:
            parser = reqparse.RequestParser()
            parser.add_argument('class_id', type=int)
            class_id = parser.parse_args().get("class_id")
        except: #didnt specify one, so what
            pass

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        if class_id:
            cur.execute("SELECT * FROM classes "
                        "WHERE class_id = %s "
                        "ORDER BY name",
                        [class_id])
        else:
            cur.execute("SELECT * FROM classes "
                        "ORDER BY name")

        query = cur.fetchall()
        # Get variable names
        cur.execute(
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'sis_data' AND table_name = 'classes'")

        column_names = cur.fetchall()
        column_names_clean = [x[0] for x in column_names]

        result = {'classes': [dict(zip(
            column_names_clean, i)) for i in query]}

        return jsonify(result)

api.add_resource(GetClasses, '/GetClasses')

"""
Gets all information about a section of a course

Note: GetClasses can do the same thing if given a specific ID,
may be unneccessary
"""
class GetClassInfo(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get class id
        parser = reqparse.RequestParser()
        parser.add_argument('class_id', type=int)
        class_id = parser.parse_args().get("class_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))
        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT * FROM classes "
                    "WHERE class_id = %s "
                    "ORDER BY name",
                    [class_id])
        query = cur.fetchall()
        # Get variable names
        cur.execute(
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'sis_data' AND table_name = 'classes'")

        column_names = cur.fetchall()
        column_names_clean = [x[0] for x in column_names]

        result = {'class_info': [dict(zip(
            column_names_clean, i)) for i in query]}

        return jsonify(result)

api.add_resource(GetClassInfo, '/GetClassInfo')

"""
Enrolls a student in a course
"""
class EnrollStudent(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get class id
        parser = reqparse.RequestParser()
        parser.add_argument('class_id', type=int)
        parser.add_argument('user_id', type=int)
        class_id = parser.parse_args().get("class_id")
        user_id = parser.parse_args().get("user_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        #TODO check if student meets prereqs

        # check if the class is full
        cur.execute("SELECT num_enrolled, capacity FROM classes" 
                    "WHERE class_id = %s",
                    [class_id])
        
        capacity = cur.fetchall()

        if len(capacity) == 0:
            return jsonify(FAILURE_MESSAGE)
        
        #compare num_enrolled to capacity
        if capacity[0][0] >= capacity[0][1]:

            #check if the waitlist is full
            cur.execute("SELECT MAX(position) FROM waitlist" 
                    "WHERE class_id = %s",
                    [class_id])
            
            waitlist = cur.fetchall()

            if len(waitlist) == 0:
                position = 0
            else:
                position = waitlist[0][0] + 1 #waitlist[0][0] == MAX(position) for class
                #TODO add waitlist capacity to database
                #if position >= waitlist_capacity:
                #   return jsonify(FAILURE_MESSAGE)

            cur.execute("INSERT IGNORE INTO waitlist (student_id, class_id, position)"
                    "VALUES (%s, %s, %s)",
                    [user_id, class_id, position])
            
            # TODO send notification that user was waitlisted and not enrolled
            return jsonify(SUCCESS_MESSAGE)

        # Select data from table using SQL query.
        cur.execute("INSERT IGNORE INTO student_to_class (student_id, class_id344)"
                    "VALUES (%s, %s)",
                    [user_id, class_id])

        cur.execute("UPDATE classes "
                    "SET num_enrolled=num_enrolled+1 "
                    "WHERE class_id = %s",
                    [class_id])

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            return jsonify(FAILURE_MESSAGE)

        return jsonify(SUCCESS_MESSAGE)

api.add_resource(EnrollStudent, '/EnrollStudent')

"""
Removes a student from a course
"""
class DropStudent(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get class id
        parser = reqparse.RequestParser()
        parser.add_argument('class_id', type=int)
        parser.add_argument('user_id', type=int)
        class_id = parser.parse_args().get("class_id")
        user_id = parser.parse_args().get("user_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("DELETE FROM student_to_class "
                    "WHERE student_id = %s "
                    "AND class_id = %s",
                    [user_id, class_id])

        cur.execute("UPDATE classes "
                    "SET num_enrolled=num_enrolled-1 "
                    "WHERE class_id = %s",
                    [class_id])

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            return jsonify(FAILURE_MESSAGE)

        return jsonify(SUCCESS_MESSAGE)

api.add_resource(DropStudent, '/DropStudent')

"""
Check users enrollment status in a course
"""
class CheckEnrollmentStatus(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get student id
        parser = reqparse.RequestParser()
        parser.add_argument('class_id', type=int)
        parser.add_argument('user_id', type=int)
        class_id = parser.parse_args().get("class_id")
        user_id = parser.parse_args().get("user_id")


        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT count(*) FROM student_to_class "
                    "WHERE student_id = %s "
                    "AND class_id = %s",
                    [user_id, class_id])

        query = cur.fetchall()

        result = {'enrollment_status': str(query[0][0] > 0)}

        return jsonify(result)

api.add_resource(CheckEnrollmentStatus, '/CheckEnrollmentStatus')


"""
Adds a class to a student's list of favorite classes
"""
class FavoriteClass(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')
    
    def get(self):
        # Get class id
        parser = reqparse.RequestParser()
        parser.add_argument('class_id', type=int)
        parser.add_argument('user_id', type=int)
        class_id = parser.parse_args().get("class_id")
        user_id = parser.parse_args().get("user_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("INSERT IGNORE INTO favorites (student_id, class_id)"
                    "VALUES (%s, %s)",
                    [user_id, class_id])
        try:
            db.commit()
        except MySQLdb.IntegrityError:
            return jsonify(FAILURE_MESSAGE)

        return jsonify(SUCCESS_MESSAGE)

api.add_resource(FavoriteClass, '/FavoriteClass')

"""
Removes a class to a student's list of favorite classes
"""
class UnfavoriteClass(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')
    
    def get(self):
        # Get class id
        parser = reqparse.RequestParser()
        parser.add_argument('class_id', type=int)
        parser.add_argument('user_id', type=int)
        class_id = parser.parse_args().get("class_id")
        user_id = parser.parse_args().get("user_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("DELETE FROM favorites "
                    "WHERE student_id = %s "
                    "AND class_id = %s",
                    [user_id, class_id])
        try:
            db.commit()
        except MySQLdb.IntegrityError:
            return jsonify(FAILURE_MESSAGE)

        return jsonify(SUCCESS_MESSAGE)

api.add_resource(UnfavoriteClass, '/UnfavoriteClass')


"""
Check if a user favorited a class
"""
class CheckFavoriteStatus(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get student id
        parser = reqparse.RequestParser()
        parser.add_argument('class_id', type=int)
        parser.add_argument('user_id', type=int)
        class_id = parser.parse_args().get("class_id")
        user_id = parser.parse_args().get("user_id")


        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT count(*) FROM favorites "
                    "WHERE student_id = %s "
                    "AND class_id = %s",
                    [user_id, class_id])

        query = cur.fetchall()

        result = {'favorite_status': str(query[0][0] > 0)}

        return jsonify(result)

api.add_resource(CheckFavoriteStatus, '/CheckFavoriteStatus')

"""
Gets a grade for a class and a student
"""
class GetGrade(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')
    
    def get(self):
        return jsonify(
                        grade="A",
                      )

api.add_resource(GetGrade, '/GetGrade')

"""
Modifies the attributes of a class
"""
class ModClass(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')
    
    def get(self):
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(ModClass, '/ModClass')

"""
Modifies the attributes of a course
"""
class ModCourse(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')
    
    def get(self):
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(ModCourse, '/ModCourse')

"""
Modifies the attributes of a professor
"""
class ModProfessor(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')
    
    def get(self):
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(ModProfessor, '/ModProfessor')

"""
Modifies the attributes of a profile

All users can modify the following attributes:
    name
    date_of_birth
    profile_pic

Additional user specific attributes may be added later
"""
"""
class ModProfile(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    #get arguments
    parser = reqparse.RequestParser()
        parser.add_argument('user_id', type=int)
        parser.add_argument('name')
        parser.add_argument('date_of_birth')
        parser.add_argument('profile_pic')
        id = parser.parse_args().get("user_id")
        name = parser.parse_args().get("name")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                        passwd=self.config.get('database', 'password'),
                        host=self.config.get('database', 'host'),
                        db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # update the name in the users table if it was changed
        if not (name is None):
            cur.execute("UPDATE users "
                    "SET name= %s "
                    "WHERE user_id = %s ",
                    [name, id])
        

        result = {'favorite_status': str(query[0][0] > 0)}

    

    if user_type.upper() == "STUDENT":
        table = "students"
    elif user_type.upper() == "PROFESSOR":
        table = "professors"
    elif user_type.upper() == "ADMIN":
        table = "admins"
    else:
        return jsonify(FAILURE_MESSAGE)
        
    
    def get(self):
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(ModProfile, '/ModProfile')
"""
"""
Requests approval of an admin for a new user, which has requested to be flagged as a professor
"""
class RequestProfessorApproval(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')
    
    def get(self):
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(RequestProfessorApproval, '/RequestProfessorApproval')


"""
Check if student/user has admin privilegs
"""
class CheckIfAdmin(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get student id
        parser = reqparse.RequestParser()
        parser.add_argument('id', type=int)
        id = parser.parse_args().get("id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT is_admin FROM users "
                    "WHERE user_id = %s",
                    [id])
        query = cur.fetchall()
        if query[0][0] == 1:
            result = {'is_admin': True}
        else:
            result = {'is_admin': False}
        return jsonify(result)
api.add_resource(CheckIfAdmin, '/CheckIfAdmin')





"""
Get Professor name by id, use to get professor name from id associated with a class
"""
class GetProfessorByID(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get class id
        parser = reqparse.RequestParser()
        parser.add_argument('professor_id', type=int)
        professor_id = parser.parse_args().get("professor_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT professor_name FROM professors "
                    "WHERE professor_id = %s",
                    [professor_id])
        query = cur.fetchall()

        result = {'professor_name': query[0][0]}

        return jsonify(result)


api.add_resource(GetProfessorByID, '/GetProfessorByID')


"""
Get the waitlist for a class given a class ID
"""
class WaitlistByClass(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get class id
        parser = reqparse.RequestParser()
        parser.add_argument('class_id', type=int)
        class_id = parser.parse_args().get("class_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT student_name FROM waitlist "
                    "INNER JOIN students  "
                    "ON (students.student_id = waitlist.student_id) "
                    "WHERE waitlist.class_id = %s "
                    "ORDER BY waitlist.position ASC",
                    [class_id])



        query = cur.fetchall()
        # Get variable names
        cur.execute(
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'sis_data' AND table_name = 'classes'")

        column_names = cur.fetchall()
        column_names_clean = [x[0] for x in column_names]

        result = [dict(zip(
            column_names_clean, i)) for i in query]

        return jsonify(result)

api.add_resource(WaitlistByClass, '/WaitlistByClass')


class GetCurrentSemester(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT MAX(id) FROM semesters ")
        query = cur.fetchall()

        result = {'current_semester': query[0][0]}

        db.close()

        return jsonify(result)

api.add_resource(GetCurrentSemester, '/GetCurrentSemester')

"""
Gets all Profs
"""
class GetProfs(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT professor_name, professor_id FROM professors")

        query = cur.fetchall()

        result = {'profs': [[i[0], i[1]] for i in query]}

        return jsonify(result)

api.add_resource(GetProfs, '/GetProfs')


"""
GetUserIdFromLinkedinID
"""
class GetUserIDFromLinkedInID(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        parser = reqparse.RequestParser()
        parser.add_argument('linkedin_id', type=str)
        linkedin_id = parser.parse_args().get("linkedin_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT user_id FROM users "
                    "WHERE linkedin = %s",
                    [linkedin_id])

        query = cur.fetchall()

        result = {'user_id': (query[0][0] if query else None) }

        return jsonify(result)
api.add_resource(GetUserIDFromLinkedInID, '/GetUserIDFromLinkedInID')


"""
GetUserIdFromLogin
"""
class GetUserIDFromLogin(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        parser = reqparse.RequestParser()
        parser.add_argument('username', type=str)
        parser.add_argument('password', type=str)
        username = parser.parse_args().get("username")
        password = parser.parse_args().get("password")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT user_id FROM logins "
                    "WHERE username = %s "
                    "AND password = %s",
                    [username, password])

        query = cur.fetchall()

        result = {'user_id': (query[0][0] if query else None)}

        return jsonify(result)
api.add_resource(GetUserIDFromLogin, '/GetUserIDFromLogin')


"""
GetUserIdFromLogin
"""
class UserExists(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        parser = reqparse.RequestParser()
        parser.add_argument('username', type=str)
        username = parser.parse_args().get("username")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT user_id FROM logins "
                    "WHERE username = %s",
                    [username])

        query = cur.fetchall()

        result = {'exists': ('True' if query else 'False')}

        return jsonify(result)
api.add_resource(UserExists, '/UserExists')

"""
Enrolls a student in a course
"""


class CreateLogin(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get class id
        parser = reqparse.RequestParser()
        parser.add_argument('username', type=str)
        parser.add_argument('password', type=str)
        username = parser.parse_args().get("username")
        password = parser.parse_args().get("password")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("INSERT INTO students"
                    "(student_name)"
                    "VALUES (NULL)")

        cur.execute("INSERT INTO users"
                    "(user_id) "
                    "VALUES (LAST_INSERT_ID());")

        # Select data from table using SQL query.
        cur.execute("INSERT IGNORE INTO logins "
                    "(user_id, username, password)"
                    "VALUES (LAST_INSERT_ID(), %s, %s)",
                    [username, password])

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            return jsonify(FAILURE_MESSAGE)

        return jsonify(SUCCESS_MESSAGE)


api.add_resource(CreateLogin, '/CreateLogin')


"""
Delete a class
"""
class DeleteClass(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get class id
        parser = reqparse.RequestParser()
        parser.add_argument('class_id', type=int)
        class_id = parser.parse_args().get("class_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()
        try:
            cur.execute("DELETE FROM classes "
                        "WHERE class_id = %s",
                        [class_id])

            db.commit()
        except MySQLdb.IntegrityError:
            return jsonify(FAILURE_MESSAGE)

        return jsonify(SUCCESS_MESSAGE)


api.add_resource(DeleteClass, '/DeleteClass')


if __name__ == '__main__':
     app.run(port=5002)