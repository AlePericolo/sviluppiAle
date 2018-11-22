from pymongo import MongoClient, errors


class Client:

    def __init__(self, conf):
        try:
            self.client = MongoClient(host=conf['host'])
                                      #username=conf['user'],
                                      #password=conf['password'],
                                      #authSource=conf['authSourc'],
                                      #serverSelectionTimeoutMS=0)
        except errors.PyMongoError:
            # The ChangeStream encountered an unrecoverable error or the
            # resume attempt failed to recreate the cursor.
            print ('error')


    def getClient(self):
        return self.client

    def showClientDatabase(self, dbName=''):
        dblist = self.client.list_database_names()
        if dbName != '':
            if dbName in dblist:
                print("The databese " + dbName + " exist in this client")
        else:
            print("Database list:")
            for db in dblist:
                print db
