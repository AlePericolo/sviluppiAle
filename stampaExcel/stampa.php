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
    <!--import bootstrap-->
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- import angularjs -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.15/angular.min.js"></script>
    <!-- import angularjs sanitize -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.15/angular-sanitize.min.js"></script>
    <!-- import ng-csv -->
    <script src="lib/ng-csv.js"></script>
    <!-- import angularjs controller-->
    <script type="text/javascript" src="controller/stampaController.js"></script>
    <title>Export csv-xls</title>
</head>

<body ng-app="myApp" ng-controller="stampaController">

    <div id="container-fluid" class="col-md-10 col-md-offset-1">
        <h3>Export csv-xls</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Eta</th>
                    <th>Citta</th>
                    <th>Sesso</th>
                    <th>Note</th>
                    <th>Millesimi</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="d in data">
                    <td>{{d.nome}}</td>
                    <td>{{d.cognome}}</td>
                    <td>{{d.eta}}</td>
                    <td>{{d.citta}}</td>
                    <td>{{d.sesso}}</td>
                    <td>{{d.note}}</td>
                    <td>{{d.millesimi}}</td>
                </tr>
            </tbody>
        </table>
        <span>Export table's data as csv</span>
        <button class="btn" ng-csv="creaFileDaScaricare()" csv-header="getHeaderTable()" filename="elenco.csv" field-separator=";" decimal-separator=".">Export CSV</button>
        <br><br>
        <span>Create file xls</span>
        <button class="btn" ng-click="scarica()">Export Excel</button>
    </div>
</body>
</html

