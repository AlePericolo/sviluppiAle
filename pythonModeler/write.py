from conf import ReadConf
from connection import Connection
from writer import WriterModel


conf = ReadConf.ReadConf()
conn = Connection.Connection(conf.database, 'ale_test')

if conn.connection is False:
    print 'SUKA'
else:
    print 'CONNESSO'

    tables = conn.getAllTables()

    for (table,) in tables:

        try:
            with open('writtenClasses/' + table.title() + 'Model.php', 'w') as outfile:

                columns = conn.getColumnsByTable(table)
                w = WriterModel.WriterModel(table, columns)
                outfile.write(w.writeFile())

        except IOError:
            print 'Errore Scrittura'