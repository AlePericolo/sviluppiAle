import MySQLdb

db = MySQLdb.connect(host="127.0.0.1",    # your host, usually localhost
                     user="root",         # your username
                     passwd="",  # your password ToDo check password container mysql portainer
                     db="snake")        # name of the data base

# you must create a Cursor object. It will let
#  you execute all the queries you need
cursor = db.cursor()

# Use all the SQL you like
cursor.execute("SELECT * FROM scores")

# print all the first cell of all the rows
for row in cursor.fetchall():
    print row

db.close()