var ngApp = angular.module('ngApp', ["ngAnimate", "angularjs-dropdown-multiselect", "ngSanitize", "ngCsv", "angularjs-gauge", "angularFileUpload", "ngRoute", "slickCarousel"]);

ngApp.controller('homeController', ['$scope', '$http', function ($scope, $http) {

    $scope.params = decodeUrl(window.location.href);
    console.log($scope.params);

    onload = function () {
        $scope.userLogged();

        //chiamo la funzione per capire che pagina devo includere
        $scope.getPageIncluded();
    };

    $scope.userLogged = function(){
        $http.post($scope.params['form'] + "/template/controller/homeHandler.php",
            {'function': 'getUtilityData'}
        ).then(function (data, status, headers, config) {
            console.log(data.data);
            $scope.username = data.data.username;
            $scope.urlFotoProfilo = data.data.urlFotoProfilo;
            $scope.urlFotoProfiloDefault = data.data.urlFotoProfiloDefault;

        });
    };

    //recupero dall'url il parametro che specifica quale pagina devo includere e lo salvo nella variabile che passo all' ng-include ($scope.pagina)
    $scope.getPageIncluded = function () {
        var newUrl = new URL(window.location.href);
        var pagina = newUrl.searchParams.get("pagina");
        var sezione = newUrl.searchParams.get("sezione");
        if (sezione != null && pagina != null) {
            //importo l'html
            $scope.import = sezione + '/' + pagina + ".html";
        }else{
            $scope.import = "cruscotto/cruscotto.html";
        }
    };

    /**
     * - dalla pagina passo la stringa che identifica la pagina in cui voglio navigare
     * - passo questa variabile all'ng-include
     * - eseguo il redirect al nuovo url per richiamare l'onload e rieseguire il giro di import
     */
    $scope.includePage = function (sezione, pagina) {
        $scope.import = sezione + '/' + pagina + ".html";
        window.location.href = $scope.params['home'] + encodeUrl(sezione, pagina);
    };

    /*================================== GESTIONE VISUALIZZAZIONE MENU LATERALE ======================================*/

    if(localStorage.getItem("menu") == true || localStorage.getItem("menu") == 'true'){
        $scope.visible = true;
    }else{
        $scope.visible = false;
    }

    $scope.mostraMenu = function () {
        if(localStorage.getItem("menu") == true || localStorage.getItem("menu") == 'true'){
            $scope.visible = false;
            localStorage.setItem("menu", false);
        }else{
            $scope.visible = true;
            localStorage.setItem("menu", true);
        }
    };

    /*================================================= LOGOUT =======================================================*/

    $scope.logout = function () {
        localStorage.setItem("menu", true);

        $http.post($scope.params['form'] + "/template/controller/homeHandler.php",
            {'function': 'effettuaLogout'}
        ).then(function (data, status, headers, config) {
            if (data.data == '1') {
                console.log('Sessione pulita');
                location.href = $scope.params['baseurl'];
            } else {
                console.log('Errore session destroy');
            }
        });
    };

    /*================================================= RELOAD =======================================================*/

    $scope.reload = function () {
        window.location.reload();
    };

}]);

//DIRECTIVE VERIFICA PRESENZA IMG: se non trovata carica img default
ngApp.directive('errSrc', function() {
    return {
        link: function(scope, element, attrs) {
            element.bind('error', function() {
                if (attrs.src != attrs.errSrc) {
                    attrs.$set('src', attrs.errSrc);
                }
            });
            attrs.$observe('ngSrc', function(value) {
                if (!value && attrs.errSrc) {
                    attrs.$set('src', attrs.errSrc);
                }
            });
        }
    }
});

ngApp.filter('formatDate', function() {
    return function(data) {
        if(data){
            return formatMySQLdata(data);
        }
    };
});