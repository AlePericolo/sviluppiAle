
ngApp.controller('relazioniController', ["$scope", "$http", function ($scope, $http) {

    $scope.params = decodeUrl(window.location.href);

    $scope.caricamentoCompletato = false;
    $scope.mostraAmici = true;
    $scope.filtraRicercaAmici = false;

        //caricaDati
    $scope.init = function(){
        $scope.caricaDati();
    };

    /*============================================= CARICO DATI PAGINA ===============================================*/

    $scope.caricaDati = function(){
        $http.post($scope.params['form'] + '/profilo/controller/relazioniHandler.php',
            {'function': 'getDatiPagina'}
        ).then(function (data) {
            console.log(data.data);

            $scope.caricamentoCompletato = true;
        });
    };

    $scope.gestioneAmici = function () {

        $scope.mostraAmici = !$scope.mostraAmici;

        if(!$scope.mostraAmici){
            $scope.find = {"nome": "", "cognome": "", "etaDa": 18, "etaA" : 99};
            $scope.slider = { options: { floor: 0, ceil: 100, step: 1, noSwitching: true }};
        }
    };

    $scope.filtraRicerca = function () {
        $scope.filtraRicercaAmici = !$scope.filtraRicercaAmici;
    };

    $scope.cercaAmici = function (tipo) {

        $scope.caricamentoCompletato = false;
        $scope.filtro = null;

        if(tipo === 'F'){
            $scope.filtro = $scope.find;
            console.log($scope.filtro);
        }

        $http.post($scope.params['form'] + '/profilo/controller/relazioniHandler.php',
            {'function': 'cercaAmici', 'filtro': $scope.filtro}
        ).then(function (data) {
            console.log(data.data);

            $scope.ricercaAmici = data.data.ricercaAmici;
            $scope.caricamentoCompletato = true;
        });
    }



}]); //CLOSE APP