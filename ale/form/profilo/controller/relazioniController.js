
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

            $scope.elencoAmici = data.data.elencoAmici;
            $scope.richiesteAmiciziaInAttesa = data.data.richiesteAmiciziaInAttesa;
            $scope.pathIcone = data.data.pathIcone;

            $scope.caricamentoCompletato = true;
        });
    };

    $scope.accetta = function (idRichiedente) {

        $http.post($scope.params['form'] + '/profilo/controller/relazioniHandler.php',
            {'function': 'accettaAmicizia', 'idRichiedente': idRichiedente}
        ).then(function (data) {
            console.log(data.data);

            if(data.data.responseRel1 == "OK" && data.data.responseRel2 == "OK"){
                swal({
                    title: "Richiesta di amicizia accettata",
                    text: "",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: true,
                },function () {
                    window.location.reload();
                });
            }else{
                swal("Errore", data.data.messageRel1 + " " + data.data.messageRel2, "error");
            }
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
            $scope.ricercaRichiesteAmiciziaInAttesa = data.data.ricercaRichiesteAmiciziaInAttesa;
            $scope.caricamentoCompletato = true;
        });
    };

    $scope.aggiungiAmico = function (utente) {

        swal({
            title: "Nuova amicizia",
            text: "Inviare richiesta di amicizia?",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "OK",
            cancelButtonText: "Annulla",
            closeOnConfirm: false,
            imageUrl: $scope.pathIcone + 'handshake.png'
        },function (isConfirm) {
            if (isConfirm) {
                $http.post($scope.params['form'] + '/profilo/controller/relazioniHandler.php',
                    {'function': 'aggiungiAmico', 'idAmico': utente.id}
                ).then(function (data) {
                    console.log(data.data);
                    if(data.data.response == "OK"){
                        swal({
                                title: "Richiesta di amicizia inviata",
                                text: "",
                                type: "success",
                                showCancelButton: false,
                                confirmButtonClass: "btn-success",
                                confirmButtonText: "OK",
                                closeOnConfirm: false
                            },
                            function(){
                                window.location.reload();
                            });
                    }else{
                        swal("Errore", data.data.message, "error");
                    }
                });
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

    $scope.rimuovi = function (idAmico, tipo) {

        var tipoRel = "richiesta";
        if(tipo == 1){
            tipoRel = "amicizia";
        }

        swal({
            title: "Attenzione",
            text: "Rimuovere questa " + tipoRel + "?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Si, rimuovi",
            cancelButtonText: "No, annulla",
            closeOnConfirm: false
        },function (isConfirm) {
            if(isConfirm){
                $http.post($scope.params['form'] + '/profilo/controller/relazioniHandler.php',
                    {'function': 'rimuoviRelazione', 'idAmico': idAmico, 'tipo': tipo}
                ).then(function (data) {
                    console.log(data.data);
                    if(data.data.response == "OK"){
                        swal({
                                title: "Relazione cancellata",
                                text: "",
                                type: "success",
                                showCancelButton: false,
                                confirmButtonClass: "btn-success",
                                confirmButtonText: "OK",
                                closeOnConfirm: false
                            },
                            function(){
                                window.location.reload();
                            });
                    }else{
                        swal("Errore", data.data.message, "error");
                    }
                });
            }
        });
    }


}]); //CLOSE APP