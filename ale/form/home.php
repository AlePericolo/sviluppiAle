<!DOCTYPE html>
<html>

<head>
    <!--import lib css js-->
    <?php include_once '../grafica/header.html';?>
    <?php //grafica custom per cliente
    $color = 'Blue';
    echo '<link href="../grafica/css/color'.$color.'.css" rel="stylesheet" type="text/css" media="all">';
    ?>
    <!--import controller-->
    <script type="text/javascript"  src="template/controller/homeController.js"></script>
    <!--funzione php per l'import dinamico dei controller delle pagine di cui faccio l'include-->
    <?php
    $sezione = 'cruscotto';
    $controller = 'cruscottoController.js';
    $cartella = 'controller';

    if(isset($_GET['sezione']))
        $sezione = $_GET['sezione'];
    if(isset($_GET['pagina']))
        $controller = $_GET['pagina'].'Controller.js';

    echo '<script type="text/javascript" src="'.$sezione.'/'.$cartella.'/'.$controller.'"></script>';
    ?>
</head>

<body ng-app="ngApp" ng-controller="homeController" class="bgColor">

<div class="horizontalBar">
    <div class="mostraMenuContainer">
        <?php echo'<img src="../grafica/img/'.$logo.'" alt="Logo" width="35%" class="marginT5 marginL5p containerLogo">' ?>
    </div>

    <div class="nomeClienteContainer">
        <label> {{descrizioneDitta}}: </label>
        <span> {{ragioneSociale}} </span>
    </div>

    <div id="gestioneUtente" class="containerUtenteHeader">
        <div class="horizontalBarButtonContainer">
            <button type="button" class="btn btn-sm horizontalBarErrorButton pointer" title="Configurazioni" ng-show="mostraAvvisoCambioPwd" ng-click="cambiaPassword()">
                <i class="fa fa-key fa-2x" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-sm horizontalBarButton pointer" title="Logout" ng-click="logout()">
                <i class="fa fa-power-off fa-2x" aria-hidden="true"></i>
            </button>
            <div class="infoUtente">Benvenuto: {{nomeUtente}}</div>
        </div>
    </div>
</div>

<div ng-class="{'sidebarOpened' : visible, 'sidebarClosed' : !visible}">

    <div class="menu-container" id="containerMostraMenu">
        <div class="mostraMenu pointer" ng-click="mostraMenu()" title="Menu">
            <i class="glyphicon glyphicon-th-list fa-fw fa-lg" aria-hidden="true"></i>
            <span class="menu-testo">&nbsp;&nbsp;&nbsp;Nascondi menu</span>
        </div>
    </div>

    <div class="menu-container">
        <div class="primoLivello pointer" ng-click="includePage('cruscotto','cruscotto')" title="Home">
            <i class="glyphicon glyphicon-home fa-fw fa-lg" aria-hidden="true"></i>
            <span class="menu-testo">&nbsp;&nbsp;&nbsp;Home</span>
        </div>
    </div>

    <div class="menu-container">
        <div class="primoLivello pointer" ng-click="includePage('elements','elements')" title="Elements">
            <i class="glyphicon glyphicon-list-alt fa-fw fa-lg" aria-hidden="true"></i>
            <span class="menu-testo">&nbsp;&nbsp;&nbsp;Elements</span>
        </div>
    </div>

</div>

<div ng-class="{'contentPart' : visible, 'contentFull' : !visible}">
    <!--
     pagina: variabile di scope contente la pagina da includere
     ng-init: attiva l'ng-include al termine della funzione, in getPageIncluded definisco quale sarà la variabile 'pagina' poi la importo
     -->
    <div id="contenitorePagina">
        <div ng-include src="import" ng-init="getPageIncluded()"></div>
    </div>
</div>

</body>
</html>