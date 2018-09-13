import DocumentData
from mongopython.Mongo import Database, Client, Collection


class Document:

    def __init__(self, conf, data = None):
        self.client = Client.Client(conf.conf)
        self.database = Database.Database(self.client.getClient(), conf.conf)
        self.documents = Collection.Collection(self.database.getDatabase(), 'documents')

        if data is not None:
            self.data = data
        else:
            self.data = DocumentData.DocumentData()


    def checkData(self):
        """
        controllo dati obbligatori:
            - name: nome del file
            - ext: estensione file
            - id_amm: id riferimento amministratore a cui appartiene il file
        :return: True/False
        """
        if self.data.name == None or self.data.ext == None or self.data.id_amm == None:
            return False
        return True


    def insertDocument(self):
        """
        inserimento file (oggetto) a db, se supera il controllo della checkData
        :return: object_id dell'elemento inserito
        """
        if self.checkData():
            return self.documents.addOne(self.data.__dict__)

    def insertDocumentJson(self, json):
        """
        inserimento file (json) a db, se supera il controllo della checkData
        :param json: il json da parasare ad oggetto
        :return: object_id dell'elemento inserito
        """
        self.data.jsonToData(json)
        return self.insertDocument()


    def deleteDocument(self, id):
        """
        rimuove da db l'elemento di cui viene passato l'id ed elimina fisicamente il file
        :param id: identificativo dell'elemento da eliminare
        :return: percorso file da eliminare
        """
        document = self.documents.findById(id)
        # remove object
        print "%s/%s.%s" %(document['id_amm'], document['_id'], document['ext'])
        self.documents.deleteById(id)


    def findByIdAmm(self, id_amm):
        """
        recupero i file appartenenti all'amministratore di cui passo l'id
        :param id_amm: identificativo dell'amministratore di cui devo recuperare i file
        :return: array di file dell'amministratore di cui passo l'id
        """
        return self.documents.find('id_amm', id_amm)


    def findByKeyValue(self, value):
        """
        recupero i file che contengono tra le chiavi il valore passato
        :param value: parametro da cercare tra le chiavi dei file
        :return: i file che tra le chiavi contengono il parametro passato
        """
        return self.documents.findInAttributeArray('key', value)


    def updateKeyArrayValue(self, id, type, value):
        """
        aggiorno le chiavi dell'elemento di cui passo l'id
        :param id: id del file di cui si vogliono modificare le chiavi
        :param type: tipo di modifica [A: add - D: delete]
        :param value: array contenente le chiavi da aggiungere/rimuovere
        :return: risultato aggiornamento
        """
        element = self.documents.findById(id)

        if element is None:
            return False

        newValue = []
        if type == 'A':
            newValue = element['key'] + [x for x in value if x not in element['key']]
        if type == 'D':
            newValue = [x for x in element['key'] if x not in value]

        return self.documents.updateElementAttribute(element, 'key', newValue)

