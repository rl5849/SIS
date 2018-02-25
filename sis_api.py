import MySQLdb
import ConfigParser, os
from flask import Flask, request
from flask_restful import Resource, Api
from sqlalchemy import create_engine
from json import dumps
from flask import jsonify


app = Flask(__name__)
api = Api(app)

class Students(Resource):
    config = ConfigParser.ConfigParser()
    config.read('./API/config.ini')

    def get(self):
        db = MySQLdb.connect(user=self.config.get('database', 'username'),
                             passwd=self.config.get('database', 'password'),
                             host='129.21.208.253',
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()
        query = cur.execute("select * from students")
        #result = {'students':[dict(zip(tuple (query.keys()) ,i)) for i in query]}
        return jsonify(query)
        
api.add_resource(Students, '/students')

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