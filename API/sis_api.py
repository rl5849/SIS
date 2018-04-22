from flask import Flask, request
import MySQLdb
import ConfigParser, os
from flask_restful import Resource, Api, reqparse
from flask import jsonify
import datetime

app = Flask(__name__)
api = Api(app)

SUCCESS_MESSAGE = "SUCCESS"
FAILURE_MESSAGE = "FAILURE"


###Add a new course
### url path: /add_course?course_name=NAME&date_of_birth=2001-02-01&profile_pic=www.linked.com&gender=F&graduation_year=2018
class AddCourse(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    #TODO : Make POST request
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
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        cur.close()
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(AddCourse, '/AddCourse')

"""
Add prereq to a course

prereq type: 0 = program_of_enrollment
             1 = year_level
"""
class AddPrereqs(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get course id
        parser = reqparse.RequestParser()
        parser.add_argument('course_id')
        parser.add_argument('type')
        parser.add_argument('program')
        parser.add_argument('year_level')
        course_id = parser.parse_args().get("course_id")
        prereq_type = parser.parse_args().get("type")
        program = parser.parse_args().get("program")
        year_level = parser.parse_args().get("year_level")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))
        cur = db.cursor()

        # insert new prerequisite into table
        cur.execute("INSERT INTO prereqs "
                    "(type, program_of_enrollment, year_level) "
                    "VALUES (%s, %s, %s) ",
                    [prereq_type, program, year_level])

        # link prereq to course
        cur.execute("INSERT INTO course_to_prereqs "
                    "(course_id, prereq_id) "
                    "VALUES (%s, ("
                    "    SELECT MAX(prereq_id) "
                    "    FROM prereqs))",
                    [course_id])
        try:
            db.commit()
        except MySQLdb.IntegrityError:
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        cur.close()
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(AddPrereqs, '/AddPrereqs')

class AddSemester(Resource):

    #TODO: Make POST request
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
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        cur.close()
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(AddSemester, '/AddSemester')

###Add a new class
# TODO: CRITCAL: URL ENCODE THESE ITEMS BEFORE MAKING THE REQUEST
class AddClass(Resource):

    #TODO: Make POST request
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
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        cur.close()
        return jsonify(SUCCESS_MESSAGE)


api.add_resource(AddClass, '/AddClass')


###Add a new student
### url path: /add_student?student_name=NAME&date_of_birth=2001-02-01&profile_pic=www.linked.com&gender=F&graduation_year=2018
# TODO: CRITCAL: URL ENCODE THESE ITEMS BEFORE MAKING THE REQUEST
class AddUser(Resource):

    #TODO: Make POST request
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
            cur.close()
            return jsonify("INSERT FAILED!")

        cur.close()
        return jsonify("SUCCESS")


api.add_resource(AddUser, '/AddUser')

"""
Remove a prereq from a course
"""
class DeletePrereq(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get course id
        parser = reqparse.RequestParser()
        parser.add_argument("prereq_id")
        prereq_id = parser.parse_args().get("prereq_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))
        cur = db.cursor()

        # remove from prereq table
        cur.execute("DELETE FROM prereqs "
                    "WHERE prereq_id=%s ",
                    [prereq_id])

        # remove from prereq to course table
        cur.execute("DELETE FROM course_to_prereqs "
                    "WHERE prereq_id=%s ",
                    [prereq_id])

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        cur.close()
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(DeletePrereq, '/DeletePrereq')


"""
Gets all favorited classes for the student
"""
class GetFavoritedClasses(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        parser = reqparse.RequestParser()
        parser.add_argument('user_id', type=str)
        parsed = parser.parse_args()
        user_id = parsed.get("user_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute(
            "SELECT classes.class_id, classes.course_id, classes.name, classes.section, classes.time, classes.room_number, professors.professor_name FROM classes  "
            "RIGHT JOIN professors ON "
            "(professors.professor_id = classes.professor_id) "
            "INNER JOIN favorites "
            "ON (favorites.class_id = classes.class_id) "
            "WHERE classes.semester_id = (SELECT MAX(id) FROM semesters) "
            "AND favorites.student_id=%s "
            "ORDER BY classes.name",
            [user_id])

        query = cur.fetchall()

        column_names = ["class_id", "course_id", "name", "section", "time", "room_number", "professor_name"]

        result = {'classes': [dict(zip(
            column_names, i)) for i in query]}

        cur.close()
        return jsonify(result)


api.add_resource(GetFavoritedClasses, '/GetFavoritedClasses')

"""
Get all information about a user
"""
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
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT * FROM students "
                    "WHERE student_id = %s",
                    [student_id])

        query = cur.fetchall()

        cur.execute("SELECT major_name FROM majors "
                    "INNER JOIN students ON (majors.major_id = students.major)"
                    "WHERE student_id = %s",
                    [student_id])

        major = cur.fetchall()

        # Get variable names
        cur.execute(
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'sis_data' AND table_name = 'students'")

        column_names = cur.fetchall()
        column_names_clean = [x[0] for x in column_names]


        if major:
            major = major[0][0]
        else:
            major = None



        result = {'student_info': [dict(zip(
            column_names_clean, i)) for i in query],
                  'major': major}

        cur.close()
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

        cur.close()
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

        cur.close()
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
            cur.execute(
                "SELECT classes.class_id, classes.course_id, classes.name, classes.section, classes.time, classes.room_number, professors.professor_name FROM classes  "
                "RIGHT JOIN professors ON "
                "(professors.professor_id = classes.professor_id) "
                "WHERE classes.semester_id = (SELECT MAX(id) FROM semesters) "
                "AND classes.class_id = %s "
                "ORDER BY classes.name",
                [class_id])
        else:
            cur.execute(
                "SELECT classes.class_id, classes.course_id, classes.name, classes.section, classes.time, classes.room_number, professors.professor_name FROM classes  "
                "INNER JOIN professors ON "
                "(professors.professor_id = classes.professor_id) "
                "WHERE classes.semester_id = (SELECT MAX(id) FROM semesters) "
                "ORDER BY classes.name")


        query = cur.fetchall()
        # Get variable names

        column_names_clean = ["class_id", "course_id", "name", "section", "time", "room_number", "professor_name"]

        result = {'classes': [dict(zip(column_names_clean, i)) for i in query]}

        cur.close()
        return jsonify(result)

api.add_resource(GetClasses, '/GetClasses')

"""
Gets all information about a section of a course

Note: GetClasses can do the same thing if given a specific ID,
may be unneccessary

TODO: LOW : Get rid of this
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

        cur.close()
        return jsonify(result)

api.add_resource(GetClassInfo, '/GetClassInfo')

"""
Gets all of the prereqs for a given course
"""
class GetPrereqs(Resource):
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
        cur.execute("SELECT p.prereq_id, "
                    "       p.type, "
                    "       p.program_of_enrollment, "
                    "       p.year_level "
                    "FROM prereqs p "
                    "JOIN course_to_prereqs cp ON "
                    "cp.prereq_id = p.prereq_id "
                    "WHERE cp.course_id = %s ",
                    [course_id])
        query = cur.fetchall()
        # Get variable names

        column_names = ["prereq_id", "type", "program_of_enrollment", "year_level"]

        result = {'prereqs': [dict(zip(
            column_names, i)) for i in query]}

        cur.close()
        return jsonify(result)

api.add_resource(GetPrereqs, '/GetPrereqs')


"""
checks if a student fulfills a specific prereq
"""
class CheckPrereq(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get course id
        parser = reqparse.RequestParser()
        parser.add_argument('student_id', type=int)
        student_id = parser.parse_args().get("student_id")
        parser.add_argument('prereq_id', type=int)
        prereq_id = parser.parse_args().get("prereq_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))
        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT * "
                    "FROM prereqs "
                    "WHERE prereq_id = %s ",
                    [prereq_id])

        query = cur.fetchall()

        prereq_type = query[0][1]
        if prereq_type == 0: #program of enrollment
            #check if student is enrolled in the correct program
            program = query[0][2]
            cur.execute("SELECT major "
                        "FROM students "
                        "WHERE student_id = %s",
                        [student_id])
            
            query = cur.fetchall()
            if program == query[0][0]:
                result = {'meets_prereq' : True}
            else:
                result = {'meets_prereq' : False}

        elif prereq_type == 1: #year level
            #check if student is enrolled in the correct year level
            year_level = query[0][3]
            cur.execute("SELECT graduation_year "
                        "FROM students "
                        "WHERE student_id = %s",
                        [student_id])
            
            query = cur.fetchall()
            grad_year = query[0][0]
            now = datetime.datetime.now()

            # no field for year level, must be calculated,
            # TODO consider changing 'year_level' in prereqs to 'grad_year'
            if grad_year == None:
                result = {'meets_prereq' : False}
            elif year_level <= (5 - (grad_year - now.year)):
                result = {'meets_prereq' : True}
            else:
                result = {'meets_prereq' : False}
        else:
            #prereq cannot be determined
            result = {'meets_prereq' : None}

        cur.close()
        return jsonify(result)

api.add_resource(CheckPrereq, '/CheckPrereq')

"""
Enrolls a student in a course
"""
class EnrollStudent(Resource):

    #TODO: Make POST request
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

        #check if student is already enrolled
        cur.execute("SELECT COUNT(*) FROM student_to_class "
            "WHERE student_id = %s "
            "AND class_id = %s",
            [user_id, class_id])

        query = cur.fetchall()
        # if student is enrolled return a failure
        if query[0][0] > 0:
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        #check if student is already waitlisted
        cur.execute("SELECT COUNT(*) FROM waitlist "
            "WHERE student_id = %s "
            "AND class_id = %s",
            [user_id, class_id])

        query = cur.fetchall()
        # if student is already waitlisted, return a failure
        if query[0][0] > 0:
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        # check if the class is full
        cur.execute("SELECT num_enrolled, capacity FROM classes "
                    "WHERE class_id = %s",
                    [class_id])

        capacity = cur.fetchall()

        if len(capacity) == 0:
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        #compare num_enrolled to capacity
        if capacity[0][0] >= capacity[0][1]:

            #get next spot on waitlist
            cur.execute("SELECT MAX(position) FROM waitlist "
                    "WHERE class_id = %s",
                    [class_id])

            waitlist = cur.fetchall()

            if waitlist[0][0] == None:
                position = 0
            else:

                position = waitlist[0][0] + 1 #waitlist[0][0] == MAX(position) for class
                #TODO add waitlist capacity to database
                #if position >= waitlist_capacity:
                #   return jsonify(FAILURE_MESSAGE)
            cur.execute("INSERT IGNORE INTO waitlist (student_id, class_id, position) "
                    "VALUES (%s, %s, %s)",
                    [user_id, class_id, position])

            try:
                db.commit()
            except MySQLdb.IntegrityError:
                cur.close()
                return jsonify(FAILURE_MESSAGE)

            cur.close()
            return jsonify("WAITLISTED")


        # Select data from table using SQL query.
        cur.execute("INSERT IGNORE INTO student_to_class (student_id, class_id) "
                    "VALUES (%s, %s)",
                    [user_id, class_id])

        cur.execute("UPDATE classes "
                    "SET num_enrolled=num_enrolled+1 "
                    "WHERE class_id = %s",
                    [class_id])

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        cur.close()
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(EnrollStudent, '/EnrollStudent')

"""
Removes a student from a course
"""
class DropStudent(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    #TODO: Make POST request
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

        # check if student is enrolled
        cur.execute("SELECT COUNT(*) FROM student_to_class "
            "WHERE student_id = %s "
            "AND class_id = %s",
            [user_id, class_id])

        query = cur.fetchall()
        # if student is enrolled, unenroll them
        if query[0][0] > 0:
            # drop student from class using SQL Query
            cur.execute("DELETE FROM student_to_class "
                        "WHERE student_id = %s "
                        "AND class_id = %s",
                        [user_id, class_id])

            cur.execute("UPDATE classes "
                        "SET num_enrolled=num_enrolled-1 "
                        "WHERE class_id = %s",
                        [class_id])
        else:
            # check if student is waitlisted
            cur.execute("SELECT position FROM waitlist "
                        "WHERE student_id = %s "
                        "AND class_id = %s",
                        [user_id, class_id])
            
            query = cur.fetchall()
            # if student is enrolled, unenroll them
            if len(query) > 0:

                # Drop student from waitlist using sql query
                cur.execute("DELETE FROM waitlist "
                            "WHERE student_id = %s "
                            "AND class_id = %s",
                            [user_id, class_id])
                
                #shift up the remaining members of the waitlist
                cur.execute("UPDATE waitlist "
                            "SET position=position-1 "
                            "WHERE position > %s "
                            "AND class_id = %s",
                            [query[0][0], class_id])
            
            else:
                cur.close()
                return jsonify(FAILURE_MESSAGE)
            

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        cur.close()
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
        if query[0][0] > 0:
            status = "ENROLLED"
        else:
            cur.execute("SELECT count(*) FROM waitlist "
                        "WHERE student_id = %s "
                        "AND class_id = %s",
                        [user_id, class_id])
            query = cur.fetchall()
            if query[0][0] > 0:
                status = "WAITLISTED"
            else:
                status = "NONE"

        result = {'enrollment_status': status}

        cur.close()
        return jsonify(result)

api.add_resource(CheckEnrollmentStatus, '/CheckEnrollmentStatus')


"""
Adds a class to a student's list of favorite classes
"""
class FavoriteClass(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    #TODO: Make POST request
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
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        cur.close()
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(FavoriteClass, '/FavoriteClass')

"""
Request special access for a class
"""
class RequestSpecialAccess(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    #TODO: Make POST request
    def get(self):
        # Get class id
        parser = reqparse.RequestParser()
        parser.add_argument('class_id', type=int)
        parser.add_argument('user_id', type=int)
        parser.add_argument('requests', type=str)

        class_id = parser.parse_args().get("class_id")
        user_id = parser.parse_args().get("user_id")
        requests = parser.parse_args().get("requests")


        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        if requests == "none":
            cur.execute("DELETE FROM access_requests "
                        "WHERE user_id = %s "
                        "AND class_id = %s",
                        [user_id, class_id])
        else:
            for req in set(requests.split(",")):
                if req == '':
                    continue
                cur.execute("INSERT IGNORE INTO access_requests "
                        "(user_id, class_id, request_type) "
                        "VALUES (%s, %s, %s)",
                        [user_id, class_id, req])

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        cur.close()
        return jsonify(SUCCESS_MESSAGE)


api.add_resource(RequestSpecialAccess, '/RequestSpecialAccess')

"""
Removes a class to a student's list of favorite classes
"""
class UnfavoriteClass(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    #TODO: Make POST request
    
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
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        cur.close()
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

        cur.close()
        return jsonify(result)

api.add_resource(CheckFavoriteStatus, '/CheckFavoriteStatus')


"""
Gets a grade for a class and a student

TODO: MEDIUM : Change to number value, implement in PHP
"""
class GetGrade(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')
    
    def get(self):
        #cur.close()
        return jsonify(
                        grade="A",
                      )

api.add_resource(GetGrade, '/GetGrade')

#TODO : MEDIUM : add assign grade

"""
Calculates and stores students GPA in db
"""
class SetGPA(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    #TODO: Make POST request

    def get(self):

        # Get student id
        parser = reqparse.RequestParser()
        parser.add_argument('user_id', type=int)
        user_id = parser.parse_args().get("user_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                     passwd=self.config.get('database', 'password'),
                     host=self.config.get('database', 'host'),
                     db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        #gets and sets student GPA
        #not sure how this responds if a student has no classes
        cur.execute("UPDATE students "
                    "SET GPA = AVG((SELECT grade "
                    "FROM past_student_to_class_grade "
                    "WHERE past_student_to_class_grade.student_id = "
                    "      students.student_id))",
                    [user_id])

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        cur.close()
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(SetGPA, '/SetGPA')

"""
Modifies the attributes of a class

TODO: HIGH : implement this
"""
class ModClass(Resource):

    #TODO: Make POST request
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')
    
    def get(self):
        #cur.close()
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(ModClass, '/ModClass')

"""
Modifies the attributes of a course

TODO: HIGH : Implement this
"""
class ModCourse(Resource):

    #TODO: Make POST request
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')
    
    def get(self):
        #cur.close()
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(ModCourse, '/ModCourse')


"""
Modifies the attributes of a profile

TODO: add php call to update account
"""
class ModProfile(Resource):

    #TODO: Make POST request
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')
    
    def get(self):

        #get arguments
        parser = reqparse.RequestParser()
        parser.add_argument('user_id', type=int)
        parser.add_argument('name')
        parser.add_argument('user_type')
        parser.add_argument('date_of_birth')
        parser.add_argument('profile_pic')
        parser.add_argument('gender')
        parser.add_argument('graduation_year')
        parser.add_argument('major')
        parser.add_argument('gpa')
        user_type = parser.parse_args().get("user_type")
        id = parser.parse_args().get("user_id")
        name = parser.parse_args().get("name")

        args = {}
        #set the table name and id name, and populate a dictionary of values
        #to be changed based on user type
        if user_type.upper() == "STUDENT":
            table = "students"
            id_type = "student_id"
            args["student_name"] = name
            args["gender"] = parser.parse_args().get("gender")
            args["graduation_year"] = parser.parse_args().get("graduation_year")
            args["major"] = parser.parse_args().get("major")
            args["gpa"] = parser.parse_args().get("gpa")         
        elif user_type.upper() == "PROFESSOR":
            table = "professors"
            id_type = "professor_id"
            args["professor_name"] = name
        elif user_type.upper() == "ADMIN":
            table = "admins"
            id_type = "admin_id"
            args["admin_name"] = name
        else:
            return jsonify(FAILURE_MESSAGE)

        args["date_of_birth"] = parser.parse_args().get("date_of_birth")
        args["profile_pic"] = parser.parse_args().get("profile_pic")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                        passwd=self.config.get('database', 'password'),
                        host=self.config.get('database', 'host'),
                        db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # update the name in the users table if it was changed
        if name:
            cur.execute("UPDATE users "
                    "SET name= %s "
                    "WHERE user_id = %s ",
                    [name, id])

        #create an update statement that will only update the fields provided
        statement = "UPDATE " + table + " SET "
        values = []
        for key in args.keys():
            if args[key]:
                statement += key + " = %s, "
                values += [args[key]]
        statement = statement.rstrip(', ')
        statement += " WHERE " + id_type + " = '%s'"
        values += [id]
        print(statement % tuple(values))
        #if nothing was updated, the statement is not executed
        if len(values) >= 2:
            cur.execute(statement, values)

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        cur.close()
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(ModProfile, '/ModProfile')

"""
Requests approval of an admin for a new user, which has requested to be flagged as a professor
"""
class RequestProfessorApproval(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    #TODO: Make POST request
    def get(self):

        parser = reqparse.RequestParser()
        parser.add_argument('user_id', type=int)
        user_id = parser.parse_args().get("user_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        cur.execute("INSERT INTO prof_requests"
                    "(user_id)"
                    "VALUES (%s);",
                    [user_id])

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        cur.close()
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(RequestProfessorApproval, '/RequestProfessorApproval')



class MakeAdmin(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get course info
        parser = reqparse.RequestParser()
        parser.add_argument('user_id', type=int)

        parsed = parser.parse_args()

        user_id = parsed.get("user_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("UPDATE users "
                    "SET user_status = 2 "
                    "WHERE user_id = %s",
                    [user_id])

        try:
            db.commit()
        except MySQLdb.IntegrityError:
            return jsonify(FAILURE_MESSAGE)
        finally:
            cur.close()

        return jsonify(SUCCESS_MESSAGE)

api.add_resource(MakeAdmin, '/MakeAdmin')




"""
Check if student/user has admin privileges
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
        cur.execute("SELECT user_status FROM users "
                    "WHERE user_id = %s",
                    [id])
        query = cur.fetchall()
        if query[0][0] == 2:
            result = {'is_admin': True}
        else:
            result = {'is_admin': False}

        cur.close()
        return jsonify(result)
api.add_resource(CheckIfAdmin, '/CheckIfAdmin')


"""
Check if user has professor privileges
"""
class CheckIfProfessor(Resource):
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
        cur.execute("SELECT user_status FROM users "
                    "WHERE user_id = %s",
                    [id])
        query = cur.fetchall()


        if query[0][0] == 1:
            result = {'is_prof': True}
        else:
            result = {'is_prof': False}

        cur.close()
        return jsonify(result)
api.add_resource(CheckIfProfessor, '/CheckIfProfessor')

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
        cur.close()
        return jsonify(result)


api.add_resource(GetProfessorByID, '/GetProfessorByID')

"""
Get Professor status requestsd
"""
class GetProfessorRequests(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT users.user_id, users.name FROM users "
                    "INNER JOIN prof_requests "
                    "ON (users.user_id = prof_requests.user_id)")
        query = cur.fetchall()

        result = {'requests': [[i[1], i[0]] for i in query]}
        cur.close()
        return jsonify(result)


api.add_resource(GetProfessorRequests, '/GetProfessorRequests')



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
        cur.close()
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
        cur.close()
        return jsonify(result)

api.add_resource(GetCurrentSemester, '/GetCurrentSemester')

"""
Gets all Profs, for making dropdown of profs when making new classes
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
        cur.close()
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
        cur.close()
        return jsonify(result)
api.add_resource(GetUserIDFromLinkedInID, '/GetUserIDFromLinkedInID')

"""
GetUsers
"""
class GetUsers(Resource):
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
        cur.execute("SELECT logins.username, users.user_id, users.name, users.user_status "
                    "FROM users "
                    "INNER JOIN logins ON "
                    "(users.user_id = logins.user_id)")

        columns = ["username", "user_id", 'name', 'user_status']


        query = cur.fetchall()

        result = {'users': [dict(zip(columns, i)) for i in query] }
        cur.close()
        return jsonify(result)
api.add_resource(GetUsers, '/GetUsers')


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
        cur.close()
        return jsonify(result)
api.add_resource(GetUserIDFromLogin, '/GetUserIDFromLogin')


"""
UserExists
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
        cur.close()
        return jsonify(result)
api.add_resource(UserExists, '/UserExists')


class CreateLogin(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    #TODO: Make POST request

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

        # Store the ID of the newly created user to return later in the query
        new_user_id = cur.lastrowid

        # Store the ID to return later
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
        cur.close()
        return jsonify({"message": SUCCESS_MESSAGE,
                        "user_id": new_user_id})



api.add_resource(CreateLogin, '/CreateLogin')


"""
Delete a class
"""
class DeleteClass(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    #TODO: Make POST request

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
        cur.close()
        return jsonify(SUCCESS_MESSAGE)


api.add_resource(DeleteClass, '/DeleteClass')


"""
Delete a course
"""
class DeleteCourse(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    #TODO: Make POST request

    def get(self):
        # Get class id
        parser = reqparse.RequestParser()
        parser.add_argument('course_id', type=int)
        course_id = parser.parse_args().get("course_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()
        try:
            cur.execute("DELETE FROM courses "
                        "WHERE course_id = %s",
                        [course_id])

            db.commit()
        except MySQLdb.IntegrityError:
            return jsonify(FAILURE_MESSAGE)
        cur.close()
        return jsonify(SUCCESS_MESSAGE)


api.add_resource(DeleteCourse, '/DeleteCourse')


"""
Delete prof request
"""
class DeleteProfRequest(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    #TODO: Make POST request

    def get(self):
        # Get class id
        parser = reqparse.RequestParser()
        parser.add_argument('user_id', type=int)
        user_id = parser.parse_args().get("user_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()
        try:
            cur.execute("DELETE FROM prof_requests "
                        "WHERE user_id = %s",
                        [user_id])

            db.commit()
        except MySQLdb.IntegrityError:
            return jsonify(FAILURE_MESSAGE)
        cur.close()
        return jsonify(SUCCESS_MESSAGE)


api.add_resource(DeleteProfRequest, '/DeleteProfRequest')


"""
Approve prof request
"""
class ApproveProfRequest(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    #TODO: Make POST request

    def get(self):
        # Get class id
        parser = reqparse.RequestParser()
        parser.add_argument('user_id', type=int)
        user_id = parser.parse_args().get("user_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()
        try:
            cur.execute("UPDATE users "
                        "SET user_status=1 "
                        "WHERE user_id=%s",
                        [user_id])

            cur.execute("DELETE FROM prof_requests "
                        "WHERE user_id = %s",
                        [user_id])

            db.commit()
        except MySQLdb.IntegrityError:
            cur.close()
            return jsonify(FAILURE_MESSAGE)
        cur.close()
        return jsonify(SUCCESS_MESSAGE)


api.add_resource(ApproveProfRequest, '/ApproveProfRequest')



"""
Get students in class
"""
class GetStudentsByClassId(Resource):
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
            cur.execute("SELECT users.user_id, users.name, student_to_class.grade  "
                        "FROM student_to_class, users "
                        # "INNER JOIN users "
                        # "ON (users.user_id = student_to_class.student_id) "
                        "WHERE users.user_id = student_to_class.student_id "
                        "AND student_to_class.class_id=%s",
                        [class_id])
            enrolled = cur.fetchall()

            cur.execute("SELECT users.user_id, users.name, waitlist.position "
                        "FROM users, waitlist "
                        "WHERE waitlist.student_id = users.user_id "
                        "AND waitlist.class_id=%s",
                        [class_id])
            waitlisted = cur.fetchall()

        except MySQLdb.IntegrityError:
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        enrolled_schema = ['user_id', 'user_name', 'grade']
        waitlist_schema = ['user_id', 'user_name', 'position']

        result = { 'enrolled' : [dict(zip(enrolled_schema, i)) for i in enrolled], 'waitlisted' : [dict(zip(waitlist_schema, i)) for i in  waitlisted]}
        cur.close()
        return jsonify(result)

api.add_resource(GetStudentsByClassId, '/GetStudentsByClassId')


"""
Get Student class by student_id and semeseter code
"""
class GetStudentsClassesForSemester(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get student id
        parser = reqparse.RequestParser()
        parser.add_argument('user_id', type=int)
        user_id = parser.parse_args().get("user_id")
        parser.add_argument('semester_id', type=int)
        semester_id = parser.parse_args().get("semester_id")

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT classes.class_id, classes.course_id, classes.name, classes.section, classes.time, classes.room_number, professors.professor_name FROM classes  "
                    "RIGHT JOIN professors ON "
                    "(professors.professor_id = classes.professor_id) "
                    "INNER JOIN student_to_class "
                    "ON (student_to_class.class_id = classes.class_id) "
                    "WHERE classes.semester_id = %s "
                    "AND student_to_class.student_id=%s "
                    "ORDER BY classes.name",
                    [semester_id, user_id])

        query = cur.fetchall()


        column_names= ["class_id", "course_id", "name", "section", "time", "room_number", "professor_name"]

        result = {'classes': [dict(zip(
            column_names, i)) for i in query]}
        cur.close()
        return jsonify(result)


api.add_resource(GetStudentsClassesForSemester, '/GetStudentsClassesForSemester')

"""
GetSemesters
"""
class GetSemesters(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT * FROM semesters "
                    "ORDER BY id DESC")

        query = cur.fetchall()

        result = {'semesters': (query if query else None)}
        cur.close()
        return jsonify(result)
api.add_resource(GetSemesters, '/GetSemesters')


"""
Get course list, significantly reduce calls for getting the course list
"""
class GetCourseList(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        # Get student id
        parser = reqparse.RequestParser()

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT classes.class_id, classes.name, classes.section, classes.time, classes.room_number, professors.professor_name FROM classes  "
                    "RIGHT JOIN professors ON "
                    "(professors.professor_id = classes.professor_id) "
                    "WHERE classes.semester_id = (SELECT MAX(id) FROM semesters) "
                    "ORDER BY classes.name",
                    )
        query = cur.fetchall()

        column_names= ["class_id", "name", "section", "time", "room_number", "professor_name"]

        result = {'classes': [dict(zip(
            column_names, i)) for i in query]}
        cur.close()
        return jsonify(result)


api.add_resource(GetCourseList, '/GetCourseList')


"""
Get majors
"""
class GetMajors(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):
        parser = reqparse.RequestParser()

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT * FROM majors")
        query = cur.fetchall()

        column_names= ["major_id", "major_name"]

        result = {'majors': [dict(zip(
            column_names, i)) for i in query]}
        cur.close()
        return jsonify(result)


api.add_resource(GetMajors, '/GetMajors')


"""
Enroll students to classes from the waitlist if there is any room in the class
"""
class EnrollFromWaitlist(Resource):

    #TODO: Make POST request
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        #gets all students who are eligableto be enrolled from the waitlist
        cur.execute("SELECT waitlist.student_id, waitlist.class_id "
                    "FROM waitlist "
                    "INNER JOIN classes ON "
                    "waitlist.class_id = classes.class_id "
                    "WHERE classes.num_enrolled < classes.capacity "
                    "AND waitlist.position <= (classes.capacity - classes.num_enrolled)")

        query = cur.fetchall()

        #if there are no students eligable to be enrolled, return
        if len(query) == 0:
            cur.close()
            return jsonify("NO STUDENTS TO ENROLL")

        #enroll students
        statement = "INSERT IGNORE INTO student_to_class (student_id, class_id) " \
                    "VALUES " + ("(%s, %s), " * (len(query)-1)) + "(%s, %s)"

        values = []
        for row in query:
            print(row)
            values+= row

        cur.execute(statement, values)

        #update number enrolled for all classes
        cur.execute(" UPDATE classes "
                    " SET num_enrolled=("
                    "   SELECT COUNT(*) FROM student_to_class"
                    "   WHERE student_to_class.class_id = classes.class_id)")

        #remove enrolled students from waitlist
        cur.execute("DELETE FROM waitlist "
                    " WHERE student_id IN ("
                    "   SELECT student_id FROM "
                    "   student_to_class WHERE "
                    "   student_to_class.class_id = waitlist.class_id)")

        #move remaining students up in waitlist position
        cur.execute("UPDATE waitlist a "
                    "SET a.position = a.position - ("
                    "   SELECT MIN(position) "
                    "   FROM ( select * from waitlist ) b "
                    "   WHERE b.class_id = a.class_id) + 1")

        try:
                    db.commit()
        except MySQLdb.IntegrityError:
            cur.close()
            return jsonify(FAILURE_MESSAGE)

        cur.close()
        return jsonify(SUCCESS_MESSAGE)

api.add_resource(EnrollFromWaitlist, '/EnrollFromWaitlist')


"""
Get a students requested access for a class
"""
class GetStudentAccess(Resource):
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
        cur.execute("SELECT request_type FROM access_requests "
                    "WHERE user_id = %s "
                    "AND class_id = %s",
                    [user_id, class_id])

        query = cur.fetchall()
        result = {"requests" : [i[0] for i in query]}
        cur.close()
        return jsonify(result)

api.add_resource(GetStudentAccess, '/GetStudentAccess')

"""
Get a students requested access for a class
"""
class GetAccessRequests(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./config.ini')

    def get(self):

        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host=self.config.get('database', 'host'),
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()

        # Select data from table using SQL query.
        cur.execute("SELECT classes.time, courses.course_code, classes.section, classes.name, users.name, access_requests.request_type "
                    "FROM access_requests "
                    "INNER JOIN classes "
                    "ON (classes.class_id = access_requests.class_id) "
                    "INNER JOIN courses "
                    "ON (classes.course_id = courses.course_id) "
                    "INNER JOIN users "
                    "ON (users.user_id = access_requests.user_id) "
                    "ORDER BY classes.name")


        query = cur.fetchall()

        titles = ['time', 'course_code', 'section', 'class_name', 'user_name', 'request']

        result = {"requests" : [dict(zip(titles, i)) for i in query]}
        cur.close()
        return jsonify(result)

api.add_resource(GetAccessRequests, '/GetAccessRequests')



if __name__ == '__main__':
     app.run(port=5002)