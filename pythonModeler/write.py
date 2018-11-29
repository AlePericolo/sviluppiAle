from conf import ReadConf
from connection import Connection
from writer import WriterClass, WriterModel

conf = ReadConf.ReadConf()
#print conf
conn = Connection.Connection(conf.database, 'ale_test')

if conn.connection is False:
    print 'SUKA'
else:
    print 'CONNESSO'
    if conf.output['formatted']:
        print 'Formattato'
    print 'Sto scrivendo..'


    tables = conn.getAllTables()

    for (table,) in tables:

        try:
            with open('writtenClasses/' + table.title() + '.php', 'w') as outfile:

                columns = conn.getColumnsByTable(table)
                c = WriterClass.WriterClass(table, conf.output['formatted'])
                outfile.write(c.writeFile())

        except IOError:
            print 'Errore Scrittura Classi'

        try:
            with open('writtenClasses/' + table.title() + 'Model.php', 'w') as outfile:

                columns = conn.getColumnsByTable(table)
                m = WriterModel.WriterModel(table, columns, conf.output['formatted'])
                outfile.write(m.writeFile())

        except IOError:
            print 'Errore Scrittura Model'

    print 'Finito!'