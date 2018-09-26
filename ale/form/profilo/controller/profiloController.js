
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

        $scope.caricamentoCompletato = false;

        if(response.answer === 'KO'){

            $scope.caricamentoCompletato = true;

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
            console.log(response.file);
        }
    };

    $scope.salvaDatiUtente = function () {

        console.log($scope.utente);
    };



}]); //CLOSE APP