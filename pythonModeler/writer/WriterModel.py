from datetime import datetime

class WriterModel:

    def __init__(self, table, columns):
        self.table = table
        self.columns = columns
        self.file = '<?php\n'

    def writeFile(self):
        self.file += self.__developedBy() + '\n'
        self.file += '\n'
        self.file += self.__importAbstract() + '\n'
        self.file += self.__initClass() + '\n'
        self.file += self.__attributes() + '\n'
        self.file += self.__costructor() + '\n'
        self.file += self.__pkFunctions() + '\n'
        self.file += self.__endClass()
        return self.file

    #===================================================================================================================
    # PRIVATE FUNCTION
    #===================================================================================================================

    def __developedBy(self):
        signature = '/**\n'
        signature += '* Developed by: Alessandro Pericolo\n'
        signature += '* Date: ' + datetime.now().strftime('%d/%m/%Y') + '\n'
        signature += '* Time: ' + datetime.now().strftime('%H:%M') + '\n'
        signature += '* Version: 0.1\n'
        signature += '**/'
        return signature

    # ------------------------------------------------------------------------------------------------------------------
    def __importAbstract(self):
        return "require_once 'AbstractModel.php';\n"

    # ------------------------------------------------------------------------------------------------------------------

    def __initClass(self):
        return 'class ' + self.table.title() + 'Model extends AbstractModel {\n'

    # ------------------------------------------------------------------------------------------------------------------

    def __attributes(self):
        app = ''
        for element in self.columns:
            type = self.__checkAttributeType(element)
            #signature
            if type == None:
                app += '/**@var '
            if type == 1:
                app += '/**@var integer'
            if type == 2:
                app += '/**@var string'
            if type == 3:
                app += '/**@var DateTime'
            if element[3] == 'PRI':
                app += ' PrimaryKey'
            app += '*/\n'
            #attribute
            app += 'protected $' + element[0]
            #default value
            if element[4]:
                if type == 1:
                    app += ' = ' + element[4] + ';'
                else:
                    app += ' = "' + element[4] + '"; '
            else:
                app += ';'
            app += '\n'
        return app

    def __checkAttributeType(self, element):
        if "(" in element[1]:
            check = element[1].split("(")[0]
        else:
            check = element[1]

        if check in ['int', 'tinyint', 'float', 'double', 'decimal']:
            return 1
        if check in ['varchar', 'blob', 'text', 'enum', 'tinytext']:
            return 2
        if check in ['date', 'datetime', 'timestamp']:
            return 3

    # ------------------------------------------------------------------------------------------------------------------

    def __costructor(self):
        app = '\n/*CONSTRUCTOR*/\n'
        app += 'function __construct($pdo){\n'
        app += '\tparent::__construct($pdo);\n'
        app += '\t$this->tableName = "' + self.table +'";\n'
        app += '}\n\n'
        return app

    # ------------------------------------------------------------------------------------------------------------------

    def __pkFunctions(self):
        #recupero la PK
        pk = '$id'
        for element in self.columns:
            if element[3] == 'PRI':
                pk = '$' + element[0]
                continue
        #find byPK
        find = '/** \n* find by PrimaryKey: \n* @return ' + self.table.title() + '|array|string|null\n**/'
        app = find + '\n'
        app += 'public function findByPk(' + pk + ', $typeResult = self::FETCH_OBJ){\n'
        app += '\t$query = "SELECT * FROM $this->tableName USE INDEX(PRIMARY) WHERE ID=?";\n'
        app += '\treturn $this->createResult($query, array(' + pk + '), $typeResult);\n'
        app += '}\n'
        # delete byPK
        delete = '\n/** \n* delete by PrimaryKey: \n**/'
        app += delete + '\n'
        app += 'public function deleteByPk(' + pk + '){\n'
        app += '\t$query = "DELETE FROM $this->tableName WHERE ID=?";\n'
        app += '\treturn $this->createResultValue($query, array(' + pk + '));\n'
        app += '}\n'
        return app

    # ------------------------------------------------------------------------------------------------------------------

    def __endClass(self):
        return '}\n'
