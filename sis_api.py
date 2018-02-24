import mysql.connector
import ConfigParser, os
from flask import Flask, request
from flask_restful import Resource, Api
from sqlalchemy import create_engine
from json import dumps
from flask.ext.jsonpify import jsonify

config = ConfigParser.ConfigParser()
config.read('./API/config.ini')


cnx = mysql.connector.connect(user=config.get('database', 'username'), password=config.get('database', 'password'),
                              host='127.0.0.1',
                              database=config.get('database', 'dbname'))
cnx.close()
app = Flask(__name__)
api = Api(app)