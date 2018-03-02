import MySQLdb
import ConfigParser, os
from flask import Flask, request
from flask_restful import Resource, Api, reqparse
from sqlalchemy import create_engine
from json import dumps
from flask import jsonify


app = Flask(__name__)
api = Api(app)


###Use a student ID to get all their classes currently enrolled
class student_class(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')

    def get(self):
        # Get student id
        parser = reqparse.RequestParser()
        parser.add_argument('student_id', type=int)
        student_id = parser.parse_args().get("student_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host='129.21.208.253',
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT * FROM classes "
                    "LEFT JOIN student_to_class ON (classes.class_id = student_to_class.class_id) "
                    "WHERE student_to_class.student_id = %s",
                    [student_id])
        query = cur.fetchall()
        result = {'students_classes': [dict(zip(["class_id", "name", "room_number", "capacity", "num_enrolled", "time", "course_id", "professor_id", "student_id ", "class_id"], i)) for i in query]}

        return jsonify(result)
        
api.add_resource(student_class, '/student_class')


###Add a new student
### url path: /add_student?student_name=NAME&date_of_birth=2001-02-01&profile_pic=www.linked.com&gender=F&graduation_year=2018
#TODO: CRITCAL: URL ENCODE THESE ITEMS BEFORE MAKING THE REQUEST
class add_student(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')

    def get(self):
        # Get student info
        parser = reqparse.RequestParser()
        parser.add_argument('student_name', type=str)
        parser.add_argument('date_of_birth', type=str)
        parser.add_argument('profile_pic', type=str)
        parser.add_argument('gender', type=str)
        parser.add_argument('graduation_year', type=int)

        parsed = parser.parse_args()
        
        student_name = parsed.get("student_name")
        dob = parsed.get("date_of_birth")
        profile_pic = parsed.get("profile_pic")
        gender = parsed.get("gender")
        grad_year = parsed.get("graduation_year")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host='129.21.208.253',
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("INSERT INTO students"
                    "(student_name, date_of_birth, profile_pic, gender, graduation_year) "
                    "VALUES (%s, %s, %s, %s, %s);",
                    [student_name, dob, profile_pic, gender, grad_year])

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            return jsonify("INSERT FAILED!")

        return jsonify("SUCCESS")

api.add_resource(add_student, '/add_student')


"""
Gets all favorited classes for the student
"""
class GetFavoritedClasses(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')
    
    def get(self):
        return jsonify(
                        message="error",
                      )

api.add_resource(GetFavoritedClasses, '/GetFavoritedClasses')

"""
Get all information about a user
"""
#TODO: Determine if this is useful
class GetUser(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')

    def get(self):
        # Get student id
        parser = reqparse.RequestParser()
        parser.add_argument('student_id', type=int)
        student_id = parser.parse_args().get("student_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host='129.21.208.253',
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT * FROM students "
                    "WHERE student_id = %s",
                    [student_id])
        query = cur.fetchall()
        #Get variable names
        cur.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'sis_data' AND table_name = 'students'")

        column_names = cur.fetchall()
        column_names_clean = [x[0] for x in column_names]

        result = {'student_info': [dict(zip(
            column_names_clean, i)) for i in query]}

        return jsonify(result)

api.add_resource(GetUser, '/GetUser')

"""
Gets all courses in a given semester given other conditions
Specify a course id to get that course only
"""
class GetCourses(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')

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
                             host='129.21.208.253',
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
"""
class GetCourseInfo(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')

    def get(self):
        # Get student id
        parser = reqparse.RequestParser()
        parser.add_argument('course_id', type=int)
        course_id = parser.parse_args().get("course_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host='129.21.208.253',
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
Enrolls a student in a course
"""
class EnrollStudent(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')
    
    def get(self):
        return jsonify(
                        message="success",
                      )

api.add_resource(EnrollStudent, '/EnrollStudent')

"""
Removes a student from a course
"""
class DropStudent(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')
    
    def get(self):
        return jsonify(
                        message="success",
                      )

api.add_resource(DropStudent, '/DropStudent')

"""
Adds a class to a student's list of favorite classes
"""
class FavoriteClass(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')
    
    def get(self):
        return jsonify(
                        message="success",
                      )

api.add_resource(FavoriteClass, '/FavoriteClass')

"""
Removes a class to a student's list of favorite classes
"""
class UnfavoriteClass(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')
    
    def get(self):
        return jsonify(
                        message="success",
                      )

api.add_resource(UnfavoriteClass, '/UnfavoriteClass')

"""
Gets a grade for a class and a student
"""
class GetGrade(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')
    
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
    config.read('./API/config.ini')
    
    def get(self):
        return jsonify(
                        message="success",
                      )

api.add_resource(ModClass, '/ModClass')

"""
Modifies the attributes of a course
"""
class ModCourse(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')
    
    def get(self):
        return jsonify(
                        message="success",
                      )

api.add_resource(ModCourse, '/ModCourse')

"""
Modifies the attributes of a professor
"""
class ModProfessor(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')
    
    def get(self):
        return jsonify(
                        message="success",
                      )

api.add_resource(ModProfessor, '/ModProfessor')

"""
Modifies the attributes of a profile
"""
class ModProfile(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')
    
    def get(self):
        return jsonify(
                        message="success",
                      )

api.add_resource(ModProfile, '/ModProfile')

"""
Requests approval of an admin for a new user, which has requested to be flagged as a professor
"""
class RequestProfessorApproval(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')
    
    def get(self):
        return jsonify(
                        message="success",
                      )

api.add_resource(RequestProfessorApproval, '/RequestProfessorApproval')

if __name__ == '__main__':
     app.run(port=5002)