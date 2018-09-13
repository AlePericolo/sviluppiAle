from mongo.src import ReadConf, Document

conf = ReadConf.ReadConf()

doc = Document.Document(conf)

doc.data.name = 'file'
doc.data.ext = 'pdf'
doc.data.id_amm = 1
doc.data.key = ['pdf', 'suka', 'click']
print(doc.insertDocument())

doc = Document.Document(conf)
doc.data.name = 'file2'
doc.data.ext = 'doc'
doc.data.id_amm = 2
doc.data.key = ['ciao', 'doc', 'word']
print(doc.insertDocument())

doc = Document.Document(conf)
doc.data.name = 'file3'
doc.data.ext = 'txt'
doc.data.id_amm = 3
doc.data.key = ['xxx', 'testo', 'prova']
print(doc.insertDocument())

doc = Document.Document(conf)
file = {"name": "file4", "ext": "csv", "id_amm": 3}
print(doc.insertDocumentJson(file))

print(doc.findByIdAmm(3))
print(doc.findByKeyValue('xxx'))
print(doc.updateKeyArrayValue('5b9a3c59bac5682bd0d166e3', 'A', ['ale', 'test', 'suka']))
print(doc.updateKeyArrayValue('5b9a3c59bac5682bd0d166e3', 'D', ['ale']))