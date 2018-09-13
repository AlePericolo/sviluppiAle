import os, json


class ReadConf:

    def __init__(self):
        with open(os.getcwd() + "/conf/conf.json") as conf:
            c = json.load(conf)
            self.conf = c['mongo']

    def getConf(self):
        return self.conf
