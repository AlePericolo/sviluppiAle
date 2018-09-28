
ngApp.controller('profiloController', ["$scope", "$http", 'FileUploader', function ($scope, $http, FileUploader) {

    $scope.params = decodeUrl(window.location.href);

    $scope.caricamentoCompletato = false;
    $scope.showModificaDati = false;

    //caricaDati
    $scope.init = function(){
        $scope.caricaDati();
    };

    /*============================================= CARICO DATI PAGINA ===============================================*/

    $scope.caricaDati = function(){
        $http.post($scope.params['form'] + '/profilo/controller/profiloHandler.php',
            {'function': 'getDatiPagina'}
        ).then(function (data) {
            console.log(data.data);

            $scope.utente = data.data.utente;
            $scope.caricamentoCompletato = true;
        });
    };

    $scope.gestioneDatiProfilo = function () {

        $scope.showModificaDati = !$scope.showModificaDati;

        if($scope.showModificaDati){
            $http.post($scope.params['form'] + '/profilo/controller/profiloHandler.php',
                {'function': 'getDatiUtente'}
            ).then(function (data) {
                console.log(data.data);

                $scope.datiUtente = data.data.utente;
                if($scope.datiUtente.data_nascita != ''){
                    $scope.datiUtente.data_nascita = getJsDateFromYYYYMMGG($scope.datiUtente.data_nascita);
                }
            });
        }
    };

    var uploader = $scope.uploader = new FileUploader({
        url: '../src/function/upload.php'
    });

    //console.log(uploader);

    // CALLBACKS
    uploader.onCompleteItem = function(fileItem, response) {

        if(response.answer === 'KO'){

            swal({
                    title: "Errore",
                    text: response.message,
                    type: "error",
                    showCancelButton: false,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "OK",
                    closeOnConfirm: true
                },
                function(){
                    window.location.reload();
                });
        }else{
            swal("Caricamento immagine completato", "", "success");
            $scope.datiUtente.foto = response.file
        }
    };

    $scope.salvaDatiUtente = function () {

        console.log($scope.datiUtente);

        $http.post($scope.params['form'] + '/profilo/controller/profiloHandler.php',
            {'function': 'salvaDatiUtente', 'utente': $scope.datiUtente}
        ).then(function (data) {
            console.log(data.data);
            if(data.data.response == 'OK'){
                swal({
                        title: "Dati salvati correttamente",
                        text: "",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "OK",
                        closeOnConfirm: true
                    },
                    function(){
                        window.location.reload();
                    });
            }else{
                swal("Errore salvataggio", data.data.message, "error");
            }

        });
    };

}]); //CLOSE APP