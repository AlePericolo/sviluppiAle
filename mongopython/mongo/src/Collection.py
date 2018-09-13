from bson.objectid import ObjectId

class Collection:

    def __init__(self, database, collection):
        self.database = database
        self.collection = self.database[collection]

    ## FUNCTIONS -------------------------------------------------------------------------------------------------------

    def addOne(self, e):
        """
        elemento da inserire a db
        :param e: elemento da aggiungere
        :return: id dell'elemento aggiunto
        """
        i = self.collection.insert_one(e)
        return i._InsertOneResult__inserted_id


    def addAll(self, l):
        """
        lista di elementi da inserire a db
        :param l: lista di elemento da aggiungere
        :return: lista di id degli elementi aggiunti
        """
        il =self.collection.insert_many(l)
        return il._InsertManyResult__inserted_ids


    def findById(self, id):
        """
        recupero l'elemento di cui passo l'id
        :param id: id dell'elemento da recuperare
        :return: l'elemento
        """
        for r in self.collection.find({"_id": ObjectId(id)}):
            return r


    def deleteById(self, id):
        """
        elimino da db l'elemento di cui passo l'id
        :param id: id dell'elemento da eliminare
        :return:
        """
        self.collection.delete_one({"_id": ObjectId(id)})


    def find(self, attribute, value):
        """
        recupero gli elementi il cui attribute che passo hanno il value che passo
        :param attribute: chiave dell'elemto su cui cercare il value
        :param value: valore che deve avere la chiave per soddisfare la ricerca
        :return: array degli elementi che soddisfano la ricerca
        """
        result = []
        for r in self.collection.find({attribute: value}):
            result.append(r)
        return result


    def findInAttributeArray(self, keyArray, value):
        """
        se uno degli attributi dell'elemnto (un array), recupero gli elementi che hanno in questo keyArray che viene passato il value che viene passato
        :param keyArray: l'attributo (array) dell'elemento in cui cercare il value
        :param value: il value da cercare nel keyArray
        :return: array degli elementi che tra un loro attributo (array) contengono il value che viene passato
        """
        result = []
        for r in self.collection.find():
            if r[keyArray] is None:
                continue
            if value in r[keyArray]:
                result.append(r)
        return result


    def findAll(self):
        """
        recupero tutti gli elementi di una collection
        :return: array di tutti gli elementi della collection
        """
        result = []
        for r in self.collection.find():
            result.append(r)
        return result


    def update(self, find=None, update=None, upsert=False, multi=False):
        """
        aggiorno gli elementi i cui dati soddisfano la find
        :param find: json di ricerca per trovare gli elementi da aggiornare
        :param update: json con i nuovi valori da assegnare agli elementi trovati dalla find
        :param upsert:
        :param multi:
        :return:
        """
        self.collection.update(
            find,
            update,
            upsert,
            multi
        )

    def updateElementAttribute(self, element, key, newValue):
        """
        aggiorno sull'elemento che passo, il valore (newValue) in corrispondenza della chiave (key) che passo
        :param element: l'elemento su cui fare l'aggiornamento
        :param key: la chiave dell'elemto il cui valore voglio modificare
        :param newValue: il nuovo valore che deve avere la chiave che ho passato
        :return: risultato aggiornamento
        """
        return self.collection.update({"_id": (element['_id'])}, {"$set": {key: newValue}})


    def truncate(self):
        """
        elimina tutti gli elementi di una collection
        :return: numero di elementi eliminati
        """
        n = self.collection.delete_many({})
        return n.deleted_count



