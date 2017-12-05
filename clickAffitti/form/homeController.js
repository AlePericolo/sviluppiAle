var ngApp = angular.module('affittiApp', []);


ngApp.controller('homeController', ['$scope', function ($scope) {

    $scope.params = decodeUrlTest(window.location.href);

    //di default 1 pagina = anagrafica
    $scope.import = "anagrafica/anagrafica.html";

    onload = function(){

        //chiamo la funzione per capire che pagina devo includere
        $scope.getPageIncluded();
    };

    //recupero dall'url il parametro che specifica quale pagina devo includere e lo salvo nella variabile che passo all' ng-include ($scope.pagina)
    $scope.getPageIncluded = function () {

        var newUrl = new URL(window.location.href);
        var pagina = newUrl.searchParams.get("pagina");
        var sezione = newUrl.searchParams.get("sezione");
        if(sezione != null && pagina != null) {
            $scope.import = sezione + '/' + pagina + ".html";
        }
    };

    /**
     * - dalla pagina passo la stringa che identifica la pagina in cui voglio navigare
     * - passo questa variabile all'ng-include
     * - eseguo il redirect al nuovo url per richiamare l'onload e rieseguire il giro di import
     */
    $scope.includePage = function(sezione, pagina){

        $scope.import = sezione + '/' + pagina + ".html";
        window.location.href = $scope.params['baseUrl'] + "?sezione=" + sezione + "&pagina=" + pagina;
    };


    /*===== GESTIONE VISUALIZZAZIONE MENU LATERALE =====*/
    $scope.visible = true;

    $scope.mostraMenu = function() {
        $scope.visible = !$scope.visible;
    };



    $scope.test = "bvnbvnvbnvbvbn";

}]);
