import csv,pymongo

from mongo.src import ReadConf, Client, Database, Collection

conf = ReadConf.ReadConf()

#try:
client = Client.Client(conf.conf)
client.showClientDatabase()

database = Database.Database(client.getClient(), conf.conf)
database.showDatabaseCollection()

people = Collection.Collection(database.getDatabase(), 'people')
people.addOne({"name": "Alessandro", "surname": "Pericolo", "age": 28})
list = [
    {"name": "Mauro", "surname": "Giudici"},
    {"name": "Claudio"},
    {"name": "Marco"},
    {"name": "Mauro", "surname": "Zuccato"},
    {"name": "Gabriele", "surname": "Alli", "age": 28},
    {"name": "Matteo", "surname": "Marguerettaz", "age": 29}
]
people.addAll(list)
people.findAll()
people.find('name', 'Mauro')
#people.truncate()

book = Collection.Collection(database.getDatabase(), 'book')
csvfile = open('file/import.csv', 'r')
fieldnames = ("Codice", "Titolo", "Quantita", "EAN")
reader = csv.DictReader(csvfile, fieldnames, delimiter=';')
for row in reader:
    book.addOne(row)
book.findAll()
book.find('Titolo', 'prova')
#book.truncate()


database2 = Database.Database(client.getClient(), 'Mauro')
database2.showDatabaseCollection()

car = Collection.Collection(database2.getDatabase(), 'car')
car.addOne({"model": "suv", "color": "black"})
find = {"color": "black"}
update = {"color": "red", "seats": 5, "brand": "BMW"}
car.update(find, update)
find2 = {'color': "white"}
update2 = {"color": "green", "seats": 2, "brand": "Lamborghini"}
car.update(find2,update2)
car.findAll()
#car.truncate()

# except pymongo.errors.ConnectionFailure as err:
#      print(err)
#      print('connection failed')

