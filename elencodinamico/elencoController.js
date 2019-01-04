var app = angular.module("myList", []); 

app.controller("elencoController", function($scope) {

    $scope.preset = function () {
        $scope.products = [];
        $scope.newElement = {};
    };

    //add element to list
    $scope.addElement = function () {
        //set quantity on element
        if(!$scope.newElement.amount){
            $scope.newElement.amount = 1;
        }
        console.log($scope.newElement);
        //check product list
        if($scope.products.length > 0){
            var index = -1;
            for (var i=0; i<$scope.products.length; i++) {
                //check if the product is alreqdy present in list
                if ($scope.products[i].name === $scope.newElement.name) {
                    index = i;
                    break;
                }
            }
            //the product is already present
            if(index > -1){
                //ask the user
                $scope.manageProduct(index, $scope.newElement.amount);
            }else{
                //add product to list
                $scope.products.push($scope.newElement);
            }
        }else{
            //add product (first)
            $scope.products.push($scope.newElement);
        }
        //clear newElement
        $scope.newElement = {};
    };

    //check if the element is already present and if asks to sum the amounts
    $scope.manageProduct = function (index, amount) {
        swal({
                title: "The element is already present",
                text: "Do you want to sum the amounts?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "No, cancel",
                confirmButtonColor: "#28a745",
                confirmButtonText: "Yes, thanks"
            },
            function(isConfirm){
                if (isConfirm){
                    $scope.products[index].amount = $scope.products[index].amount + amount;
                    $scope.$apply();
                }
            });
    };

    //remove the element from the list
    $scope.removeElement = function (index) {
        swal({
                title: "Are you sure?",
                text: "The element will be removed",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Yes, delete it!",
            },
            function(){
                $scope.products.splice(index, 1);
                $scope.$apply();
            });
    }
});