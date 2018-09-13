class Database:

    def __init__(self, client, database):
        if type(database) is str:
            self.database = client[database]
        else:
            self.database = client[database['database']]

    def getDatabase(self):
        return self.database

    def showDatabaseCollection(self, colName=''):
        collist = self.database.list_collection_names()
        if colName != '':
            if colName in collist:
                print("The collection " + colName + " exist")
        else:
            print("Collection list " + self.database._Database__name + ":")
            for col in collist:
                print col
