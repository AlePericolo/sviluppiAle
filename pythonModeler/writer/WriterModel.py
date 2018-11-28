class WriterModel:

    def __init__(self, table, columns):
        self.table = table
        self.columns = columns
        self.file = '<?php\n'

    def writeFile(self):
        self.file += '\n'
        self.file += self.__importAbstract() + '\n'
        self.file += self.__initClass() + '\n'
        self.file += self.__attributes() + '\n'
        self.file += self.__costructor() + '\n'
        self.file += self.__endClass()
        return self.file

    def __importAbstract(self):
        return "require_once 'AbstractModel.php';\n"

    def __initClass(self):
        return 'class ' + self.table.title() + 'Model extends AbstractModel {\n'

    def __attributes(self):
        app = ''
        for c in self.columns:
            app += 'protected $' + c + ';\n'
        return app

    def __costructor(self):
        app = 'function __construct($pdo){\n'
        app += '\tparent::__construct($pdo);\n'
        app += '\t$this->tableName = "' + self.table +'";\n'
        app += '}\n'
        return app


    def __endClass(self):
        return '}\n'
