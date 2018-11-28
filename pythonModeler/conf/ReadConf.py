import os, json

class ReadConf:

    def __init__(self):
        #print 'ReadConf'
        with open(os.getcwd()+"/conf/conf.json") as conf:
            self.database = json.load(conf)