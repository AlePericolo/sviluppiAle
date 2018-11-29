
ngApp.controller('elementsController', ['$scope', '$http', function ($scope, $http) {

    $scope.params = decodeUrl(window.location.href);

    $scope.caricamentoCompletato = false;
    $scope.gestisciElemento = false;

    //caricaDati
    $scope.init = function(){
        $scope.caricaDati();
    };

    /*============================================= CARICO DATI PAGINA ===============================================*/

    $scope.caricaDati = function(){
        $http.post($scope.params['form'] + '/elements/controller/elementsHandler.php',
            {'function': 'getDatiPagina'}
        ).then(function (data) {
            console.log(data.data);
            $scope.elements = data.data.elements;

            $scope.caricamentoCompletato = true;
        });
    };

    $scope.recuperaElemento = function (id) {
        $http.post($scope.params['form'] + '/elements/controller/elementsHandler.php',
            {'function': 'recuperaElemento', 'id': id}
        ).then(function (data) {
            console.log(data.data);
            $scope.element = data.data.elemento;
            if($scope.element.date){
                console.log($scope.element.date);
                $scope.element.date = getJsDateFromYYYYMMGG($scope.element.date);
            }
            $scope.gestisciElemento = true;
        });
    };

    $scope.salva = function () {
        console.log('Salvataggio:');
        console.log($scope.element);
        $http.post($scope.params['form'] + '/elements/controller/elementsHandler.php',
            {'function': 'salva', 'elemento': $scope.element}
        ).then(function (data) {
            console.log(data.data);
            if(data.data.response == 'OK'){
                swal({
                        title: "Elemento salvato",
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
                swal("Errore!", data.data.message, "error");
            }
        });
    };

    $scope.elimina = function (id) {

        var message = 'Eliminare questo elemento?';

        if(id == -1){
            message = "Eliminare tutti gli elementi?"
        }

        swal({
                title: "Attenzione",
                text: message,
                type: "warning",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Elimina",
                showCancelButton: true,
                cancelButtonClass: "btn-default",
                cancelButtonText: "Annulla",
                closeOnConfirm: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $http.post($scope.params['form'] + '/elements/controller/elementsHandler.php',
                        {'function': 'elimina', 'id': id}
                    ).then(function (data) {
                        console.log(data.data);
                        if(data.data.response == "KO"){
                            swal("Errore", data.data.message, "error");
                        }else{
                            window.location.reload();
                        }
                    });
                }
            });
    };

    $scope.annulla = function () {
        $scope.gestisciElemento = false;
    };


}]); //CLOSE APP