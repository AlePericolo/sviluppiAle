import ReadConf, MySQLdb

def check_db_connection(conf, dbname = None):
    try:
        if dbname is None:
            connection = MySQLdb.connect(conf['host'], conf['user'], conf['password'], conf['database'], connect_timeout=10)
        else:
            connection = MySQLdb.connect(conf['host'], conf['user'], conf['password'], dbname, connect_timeout=10)
    except:
        return False
    else:
        return connection


conf = ReadConf.ReadConf()
conn = check_db_connection(conf.database, 'ale_test')

if conn is False:
    print 'SUKA'
else:
    print 'CONNESSO'
    cursor = conn.cursor()

    cursor.execute("SHOW TABLES")  # execute 'SHOW TABLES' (but data is not returned)

    tables = cursor.fetchall()  # return data from last query

    for (table_name,) in tables:
        print(table_name)