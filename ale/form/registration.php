<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <!--import lib css js-->
    <?php
        include_once '../conf/conf.php';
        include_once '../grafica/header.html';
        echo '<link href="../grafica/css/color'.COLOR.'.css" rel="stylesheet" type="text/css" media="all">';
    ?>
    <!-- import angularjs controller-->
    <script type="text/javascript" src="template/controller/registrationController.js"></script>
    <title>Registration</title>
</head>

<body ng-app="ngApp" ng-controller="registrationController" class="background-light">

<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 marginT5percent">
    <div class="panel panel-default text-center">
        <div class="panel-heading baseColor3"><label class="font18 padding0 colorWhite"> REGISTRATION </label></div>
        <div class="panel-body">
            <div class="form-group input-group col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2" title="Username">
                <span class="input-group-addon"> <i class="fa fa-user" aria-hidden="true"></i> </span>
                <input type="text" class="form-control" ng-model="registration.username" autocomplete="off" placeholder="Username" autofocus/>
            </div>
            <div class="form-group input-group col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2" title="Password">
                <span class="input-group-addon"> <i class="fa fa-lock" aria-hidden="true"></i> </span>
                <input type="password" class="form-control" ng-model="registration.password" autocomplete="off" placeholder="Password"/>
            </div>
            <div class="row text-center" ng-show="livelloSicurezza > 0">
                <ng-gauge size="100" type="semi" thick="5"
                          min="0" max="100" value="livelloSicurezza"
                          cap="round" label="Security" append="%" duration="500"
                          thresholds="thresholdSecurity">
                </ng-gauge>
            </div>
            <div class="form-group input-group col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2" title="Repeat Password">
                <span class="input-group-addon"> <i class="fa fa-lock" aria-hidden="true"></i> </span>
                <input type="password" class="form-control" ng-model="registration.repeatpassword" autocomplete="off" placeholder="Repeat Password"/>
            </div>
            <div class="row text-center"> <small ng-show="messagePassword" class="red"><strong>Attenzione!</strong> Le Password devono essere identiche.</small> </div>
            <div class="row"> <button type="button" class="btn btn-default" ng-disabled="!abilitaSalvataggio" ng-click="registraUtente()">Registra Utente</button> </div>
            <div class="row text-left marginL10"> <u class="pointer" ng-click="goToLogin()">Login</u> </div>
        </div>
        <div class="panel-footer text-center">
            <small>&copy;Developed by Peril - 2018</small>
        </div>
    </div>
</div>

</body>

</html>