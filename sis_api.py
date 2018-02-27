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
        result = {'students_classes': query}
        return jsonify(result)
        
api.add_resource(student_class, '/student_class')

class GetStudentInformation(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')
    
    def get(self):
        return jsonify(
                        student_id="123456789",
                        student_name="Betty White",
                        date_of_birth="Jan 17, 1922",
                        profile_pic="https://i0.wp.com/radaronline.com/wp-content/uploads/2017/05/betty-white-secret-suitor-split-pp.jpg?fit=640%2C420&ssl=1",
                        gender="F",
                        graduation_year="2020",
                      )

api.add_resource(GetStudentInformation, '/GetStudentInformation')
if __name__ == '__main__':
     app.run(port=5002)