
class DocumentData:

    def __init__(self, name=None, ext=None, id_amm=None, desc=None, key=[]):
        self.name = name
        self.ext = ext
        self.id_amm = id_amm
        self.desc = desc
        self.key = key


    def jsonToData(self, json):
        """
        trasforma in oggetto (data) il json che passo assegnando a null i campi che non sono presenti nel json
        :param json: il json da parsare ad oggetto data
        """
        self.name = json['name'] if 'name' in json else None
        self.ext = json['ext'] if 'ext' in json else None
        self.id_amm = json['id_amm'] if 'id_amm' in json else None
        self.desc = json['desc'] if 'desc' in json else None
        self.key = json['key'] if 'key' in json else None

