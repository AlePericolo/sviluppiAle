<?php
/**
 * Created by PhpStorm.
 * User: clickale
 * Date: 04/04/17
 * Time: 16.48
 */
?>

<head>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
    <!-- import angularjs -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js"></script>
    <!-- import angularjs controller-->
    <script type="text/javascript" src="controller/stampaControllerSelezionaColonne.js"></script>
    <!--jspdf-->
    <script type="text/javascript" src="../testStampaPDFCustom/lib/jspdf.min.js"></script>
    <script type="text/javascript" src="../testStampaPDFCustom/lib/jspdf.plugin.autotable.js"></script>
    <!--bootstrap-->
    <link rel="stylesheet" href="../testStampaPDFCustom/css/bootstrap.css">
    <title>Stampa PDF 3</title>
</head>

<body ng-app="myApp" ng-controller="stampaControllerSelezionaColonne">
    <div class="container-fluid">
        <h3>Export JsPdf select columns</h3>
        <div class="panel panel-default">
            <div class="panel-heading" ng-click="mostraImpostazioniStampa()" style="cursor: pointer">
                <h3 class="panel-title">IMPOSTAZIONI STAMPA</h3>
            </div>
            <div class="panel-body" ng-if="showImpostazioniStampa">
                <div class="form-group form-inline">
                    <label>GENERALI:</label><br/>
                    <!--nome-->
                    <label>Nome file:</label>
                    <input type="text" class="form-control" ng-model="stampa.nomeFile"/>
                    <!-- foglio -->
                    <label>&nbsp;&nbsp;&nbsp;Foglio:</label>
                    <select class="form-control" ng-model="stampa.foglio">
                        <option value="A3">A3</option>
                        <option value="A4">A4</option>
                    </select>
                    <!--orientamento-->
                    <label>&nbsp;&nbsp;&nbsp;Orientamento:</label>
                    <select class="form-control" ng-model="stampa.orientamento">
                        <option value="L">Orizzontale</option>
                        <option value="P">Verticale</option>
                    </select>
                    <!--tema-->
                    <label>&nbsp;&nbsp;&nbsp;Tema:</label>
                    <select class="form-control" ng-model="stampa.tema">
                        <option value="grid">Griglia</option>
                        <option value="striped">Strisce</option>
                        <option value="plain">Vuoto</option>
                    </select>
                </div>
                <!--margini-->
                <div class="form-group form-inline">
                    <label>MARGINI:</label><br/>
                    <label>Sinistra:</label>
                    <input type="number" class="form-control" ng-model="stampa.margineSinistro" style="width: 5%"/> px
                    <label>&nbsp;&nbsp;Alto:</label>
                    <input type="number" class="form-control" ng-model="stampa.margineAlto" style="width: 5%"/> px
                    <label>&nbsp;&nbsp;Destra:</label>
                    <input type="number" class="form-control" ng-model="stampa.margineDestro" style="width: 5%"/> px
                    <label>&nbsp;&nbsp;Basso:</label>
                    <input type="number" class="form-control" ng-model="stampa.margineBasso" style="width: 5%"/> px
                </div>
                <!-- font-->
                <div class="form-group form-inline">
                    <label>CARATTERE:</label><br/>
                    <!--font-->
                    <label>Font:</label>
                    <select class="form-control" ng-model="stampa.tipoFont">
                        <option value="helvetica">Helvetica</option>
                        <option value="times">Times New Roman</option>
                        <option value="courier">Courier</option>
                    </select>
                    <!--dimensione font-->
                    <label>&nbsp;&nbsp;&nbsp;Dimensione:</label>
                    <input type="number" class="form-control" ng-model="stampa.dimensioneFont" style="width: 5%"/> px
                    <!-- stile font-->
                    <label>&nbsp;&nbsp;&nbsp;Stile:</label>
                    <select class="form-control" ng-model="stampa.stileFont">
                        <option value="normal">Normale</option>
                        <option value="bold">Grassetto</option>
                        <option value="italic">Corsivo</option>
                        <option value="bolditalic">Grassetto- Corsivo</option>
                    </select>
                </div>
                <!--celle-->
                <div class="form-group form-inline">
                    <label>CELLE:</label><br/>
                    <!-- cell padding-->
                    <label>Padding:</label>
                    <input type="number" class="form-control" ng-model="stampa.paddingCella" style="width: 5%"/> px
                    <!-- stile testo cella-->
                    <label>&nbsp;&nbsp;&nbsp;Testo cella:</label>
                    <select class="form-control" ng-model="stampa.testoCella">
                        <option value="ellipsize">ellipsize</option>
                        <option value="visible">visible</option>
                        <option value="hidden">hidden</option>
                        <option value="linebreak">linebreak</option>
                    </select>
                    <!-- larghezza cella
                    <label>&nbsp;&nbsp;&nbsp;Larghezza cella:</label>
                    <input type="number" class="form-control" ng-model="stampa.larghezzaCella" style="width: 5%"/> px-->
                </div>
                <div class="form-group form-inline">
                    <label>HEADER & FOOTER:</label> &nbsp;&nbsp;
                    <input type="checkbox" ng-model="headerfooter" ng-true-value='true' ng-false-value='false' ng-change="showSetHeaderFooter(headerfooter)" >

                    <div ng-show="hf">
                        <label>Dimensione Header:</label>
                        <input type="number" class="form-control" ng-model="stampa.dimensioneFontHeader" style="width: 5%"/> px
                        <label>&nbsp;&nbsp;&nbsp;Dimensione Footer:</label>
                        <input type="number" class="form-control" ng-model="stampa.dimensioneFontFooter" style="width: 5%"/> px
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="container" class="col-md-11">
        <button class="btn btn-success" ng-click="scaricaPDF()">Scarica PDF</button>
        <br/><br/>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>
                        Nome
                        <input type="checkbox" ng-true-value="true" ng-false-value="false" ng-model="visualizzaNome">
                    </th>
                    <th>
                        Cognome
                        <input type="checkbox" ng-true-value="true" ng-false-value="false" ng-model="visualizzaCognome">
                    </th>
                    <th>
                        Eta
                        <input type="checkbox" ng-true-value="true" ng-false-value="false" ng-model="visualizzaEta">
                    </th>
                    <th>
                        Citta
                        <input type="checkbox" ng-true-value="true" ng-false-value="false" ng-model="visualizzaCitta">
                    </th>
                    <th>
                        Sesso
                        <input type="checkbox" ng-true-value="true" ng-false-value="false" ng-model="visualizzaSesso">
                    </th>
                    <th>
                        Note
                        <input type="checkbox" ng-true-value="true" ng-false-value="false" ng-model="visualizzaNote">
                    </th>
                    <th>
                        Millesimi
                        <input type="checkbox" ng-true-value="true" ng-false-value="false" ng-model="visualizzaMillesimi">
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="d in data">
                    <td>
                        <span ng-show="visualizzaNome">{{d.nome}}</span>
                    </td>
                    <td>
                        <span ng-show="visualizzaCognome">{{d.cognome}}</span>
                    </td>
                    <td>
                        <span ng-show="visualizzaEta">{{d.eta}}</span>
                    </td>
                    <td>
                        <span ng-show="visualizzaCitta">{{d.citta}}</span>
                    </td>
                    <td>
                        <span ng-show="visualizzaSesso">{{d.sesso}}</span>
                    </td>
                    <td>
                        <span ng-show="visualizzaNote">{{d.note}}</span>
                    </td>
                    <td>
                        <span ng-show="visualizzaMillesimi">{{d.millesimi}}</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html