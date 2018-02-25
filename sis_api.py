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
                             host='127.0.0.1',
                             db=self.config.get('database', 'dbname'))

        cur = db.cursor()
        query = cur.execute("select * from students")
        #result = {'students':[dict(zip(tuple (query.keys()) ,i)) for i in query]}
        return jsonify(query)


api.add_resource(Students, '/students')


if __name__ == '__main__':
     app.run(port=5002)