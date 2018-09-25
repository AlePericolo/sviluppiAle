<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <!--import lib css js-->
    <?php include_once '../grafica/header.html';?>
    <?php
    $color = 'Blue';
    echo '<link href="../grafica/css/color'.$color.'.css" rel="stylesheet" type="text/css" media="all">';
    ?>
    <!-- import angularjs controller-->
    <script type="text/javascript" src="template/controller/loginController.js"></script>
    <title>Login</title>
</head>

<body ng-app="ngApp" ng-controller="loginController" class="background-light">

    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 marginT5percent">
        <div class="panel panel-default text-center">
            <div class="panel-heading baseColor"><label class="font18 padding0 colorWhite"> LOGIN </label></div>
            <div class="panel-body marginB20">
                <div class="form-group">

                </div>
                <div class="form-group input-group col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2" title="Username">
                    <span class="input-group-addon"> <i class="fa fa-user" aria-hidden="true"></i> </span>
                    <input type="text" class="form-control" ng-model="login.username" autocomplete="off" autofocus/>
                </div>
                <div class="form-group input-group col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2" title="Password">
                    <span class="input-group-addon"> <i class="fa fa-lock" aria-hidden="true"></i> </span>
                    <input type="password" class="form-control" ng-model="login.password" autocomplete="off"/>
                </div>
                <button type="button" class="btn btn-default" ng-disabled="!abilitaLogin" ng-click="effettuaLogin()">Login</button>
            </div>
            <div><small>&copy;Peril</small></div>
        </div>
    </div>

</body>

</html>