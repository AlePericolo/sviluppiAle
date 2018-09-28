
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

            $scope.pathIcone = data.data.pathIcone;
            console.log($scope.pathIcone);

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
        $scope.ricercaAmici = [];
    };

    $scope.annullaFiltraRicerca = function () {
        $scope.filtraRicercaAmici = false;
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
    };

    $scope.aggiungiAmico = function (utente) {

        $http.post($scope.params['form'] + '/profilo/controller/relazioniHandler.php',
            {'function': 'aggiungiAmico', 'idAmico': utente.id}
        ).then(function (data) {
            console.log(data.data);
            if(data.data.response == "OK"){
                swal({
                    title: "Nuova amicizia",
                    text: "Richiesta di amicizia inviata",
                    showCancelButton: false,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "OK",
                    closeOnConfirm: true,
                    imageUrl: $scope.pathIcone + 'handshake.png'
                },function () {
                    window.location.reload();
                });
            }else{
                swal("Errore", data.data.message, "error");
            }
        });
    };

    $scope.richiesteInAttesa = function () {

        $scope.caricamentoCompletato = false;

        $http.post($scope.params['form'] + '/profilo/controller/relazioniHandler.php',
            {'function': 'richiesteInAttesa'}
        ).then(function (data) {
            console.log(data.data);
            $scope.richiesteEffettuateInAttesa = data.data.richiesteInAttesa;

            $scope.caricamentoCompletato = true;
        });
    };


}]); //CLOSE APP