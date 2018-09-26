
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

    $scope.modificaDatiProfilo = function () {

        $scope.showModificaDati = true;
    };

    $scope.chiudiModifica = function () {

        $scope.showModificaDati = false;
    };


    var uploader = $scope.uploader = new FileUploader({
        url: '../src/function/upload.php'
    });

    console.log(uploader);

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
            $scope.utente.foto = response.file
        }
    };

    $scope.salvaDatiUtente = function () {

        console.log($scope.utente);
    };



}]); //CLOSE APP