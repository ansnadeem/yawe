import psycopg2
import json
from database import configure

class UnitOfWork:
    def __init__(self):
        configurations = configure.getDatabaseConfigurations()
        self.conn = psycopg2.connect("dbname={} user={} password={}".format(configurations['databaseName'], configurations['userName'], configurations['password']))
        self.cur = self.conn.cursor()

    def execute(self, query):
        self.cur.execute(query)
    
    def executeAndFetch(self, query):
        self.cur.execute(query)
        return self.cur.fetchall()

    def commit(self):
        self.conn.commit()
    
    def __del__(self):
        self.cur.close()
        self.conn.close()