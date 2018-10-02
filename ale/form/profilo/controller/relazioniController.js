
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

        }).then(function (){
            $scope.caricamentoCompletato = true;
        });
    };

    $scope.accetta = function (idRichiedente) {

        $scope.caricamentoCompletato = false;

        $http.post($scope.params['form'] + '/profilo/controller/relazioniHandler.php',
            {'function': 'accettaAmicizia', 'idRichiedente': idRichiedente}
        ).then(function (data) {
            console.log(data.data);

            if(data.data.response == "OK"){
                swal({
                    title: "Richiesta di amicizia accettata",
                    text: "",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: true
                },function () {
                    window.location.reload();
                });
            }else{
                swal("Errore", data.data.message, "error");
            }
        }).then(function (data) {
            $scope.caricamentoCompletato = true;
        });
    };

    /* ======================================= SWITCH SEZIONE amici/cerca =========================================== */

    $scope.gestioneAmici = function () {

        $scope.mostraAmici = !$scope.mostraAmici;

        if(!$scope.mostraAmici){
            $scope.find = {"nome": "", "cognome": "", "etaDa": 18, "etaA" : 99};
            $scope.slider = { options: { floor: 0, ceil: 100, step: 1, noSwitching: true }};
        }
    };

    /* ========================================== SEZIONE NUOVI AMICI =============================================== */

    $scope.filtraRicerca = function () {
        $scope.filtraRicercaAmici = true;
        $scope.ricercaAmici = [];
        $scope.ricercaRichiesteAmiciziaInAttesa = [];
    };

    $scope.annullaFiltraRicerca = function () {
        $scope.filtraRicercaAmici = false;
        $scope.ricercaAmici = [];
        $scope.ricercaRichiesteAmiciziaInAttesa = [];
    };

    /* ricerca globale/filtrata
    *  - elenco utenti che posso aggiungere agli amici
    *  - elenco utenti che mi hanno aggiunto agli amici cui devo rispondere
    * */
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
            if($scope.ricercaAmici.length == 0){
                swal("Attenzione!", "La ricerca non ha prodotto risultati", "warning");
            }
            $scope.ricercaRichiesteAmiciziaInAttesa = data.data.ricercaRichiesteAmiciziaInAttesa;

        }).then(function () {
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

                $scope.caricamentoCompletato = false;

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
                }).then(function () {
                    $scope.caricamentoCompletato = true;
                });
            }
        });
    };

    /* elenco utenti cui ho inviato una richiesta di amicizia che non mi hanno ancora risposto */
    $scope.richiesteInAttesa = function () {

        $scope.caricamentoCompletato = false;

        $http.post($scope.params['form'] + '/profilo/controller/relazioniHandler.php',
            {'function': 'richiesteInAttesa'}
        ).then(function (data) {
            console.log(data.data);
            $scope.richiesteEffettuateInAttesa = data.data.richiesteInAttesa;
            if($scope.richiesteEffettuateInAttesa.length == 0){
                swal("Attenzione!", "La ricerca non ha prodotto risultati", "warning");
            }
        }).then(function () {
            $scope.caricamentoCompletato = true;
        });
    };

    /* ======================================== RIMUOVI amico/richiesta ============================================= */

    /* rimuovi richiesta/amicizia
     * - tipo = 0 (rimuovo richiesta) elimino 1 record
     * - tipo = 1 (già amici) rimuovo 2 record
     */
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

                $scope.caricamentoCompletato = false;

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
                }).then(function () {
                    $scope.caricamentoCompletato = true;
                });
            }
        });
    }

}]); //CLOSE APP