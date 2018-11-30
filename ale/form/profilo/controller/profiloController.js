
ngApp.controller('profiloController', ["$scope", "$http", 'FileUploader', function ($scope, $http, FileUploader) {

    $scope.params = decodeUrl(window.location.href);

    $scope.caricamentoCompletato = false;
    $scope.showModificaDati = false;

    $scope.init = function(){
        $scope.caricaDati();
    };

    /*========================================== CARICO DATI PAGINA ==================================================*/

    $scope.caricaDati = function(){

        $http.post($scope.params['form'] + '/profilo/controller/profiloHandler.php',
            {'function': 'getDatiPagina'}
        ).then(function (data) {
            console.log(data.data);

            $scope.utente = data.data.utente;
            $scope.post = data.data.post;
            $scope.elencoPost = data.data.elencoPost;

        }).then(function () {
            $scope.caricamentoCompletato = true;
        });
    };

    /* ============================================= GESTIONE POST ================================================== */

    $scope.salvaPost = function () {

        $scope.caricamentoCompletato = false;

        $http.post($scope.params['form'] + '/profilo/controller/profiloHandler.php',
            {'function': 'salvaPost', 'post': $scope.post}
        ).then(function (data) {
            console.log(data.data);

            if(data.data.response == "OK"){
                swal({
                        title: "Post condiviso",
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
                swal("Errore", data.data.message, "error");
            }
        }).then(function () {
            $scope.caricamentoCompletato = true;
        });
    };

    $scope.eliminaPost = function(idPost){

        swal({
            title: "Attenzione",
            text: "Eliminare questo post?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Si, rimuovi",
            cancelButtonText: "No, annulla",
            closeOnConfirm: false
        },function (isConfirm) {
            if(isConfirm){

                $scope.caricamentoCompletato = false;

                $http.post($scope.params['form'] + '/profilo/controller/profiloHandler.php',
                    {'function': 'eliminaPost', 'idPost': idPost}
                ).then(function (data) {
                    console.log(data.data);

                    if(data.data.response == "OK"){
                        swal({
                                title: "Post cancellato",
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

    $scope.modificaPost = function (post) {

        //console.log(post);
        $scope.caricamentoCompletato = false;

        $http.post($scope.params['form'] + '/profilo/controller/profiloHandler.php',
            {'function': 'modificaPost', 'post': post}
        ).then(function (data) {
            console.log(data.data);

            if(data.data.response == "OK"){
                swal({
                        title: "Post modificato",
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
    };

    /* ======================================== GESTIONE PROFILO UTENTE ============================================= */

    $scope.gestioneDatiProfilo = function () {

        $scope.showModificaDati = !$scope.showModificaDati;

        if($scope.showModificaDati){
            $http.post($scope.params['form'] + '/profilo/controller/profiloHandler.php',
                {'function': 'getDatiUtente'}
            ).then(function (data) {
                console.log(data.data);

                $scope.datiUtente = data.data.utente;
                if($scope.datiUtente.data_nascita){
                    $scope.datiUtente.data_nascita = getJsDateFromMySQLdate($scope.datiUtente.data_nascita);
                }
            });
        }
    };

    /*---------------------------------------------immagine profilo---------------------------------------------------*/

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
            //swal("Caricamento immagine completato", "", "success");
            $scope.datiUtente.foto = response.file
        }
    };

    /*--------------------------------------------salvataggio dati utente---------------------------------------------*/

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