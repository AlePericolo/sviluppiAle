angular.module('mapsApp', ['ui.bootstrap', 'ngMap']).controller('mapsController', function ($scope) {

    $scope.geopos = {lat:51.50722,lng:-0.12750};

    $scope.$on('mapInitialized', function(evt, evtMap) {
        $scope.map = evtMap;
        $scope.marker = new google.maps.Marker({position: evt.latLng, map: $scope.map});

        $scope.click = function(evt) {
            var latitude = evt.latLng.lat().toPrecision(8);
            var longitude = evt.latLng.lng().toPrecision(8);
            $scope.marker.setPosition(evt.latLng);
            $scope.map.panTo(evt.latLng);
            $scope.geopos.lat = latitude;
            $scope.geopos.lng = longitude;
            //$scope.map.setZoom(10);
        }
    });
    
    $scope.getCurrentPosition = function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position){
                $scope.$apply(function(){
                    $scope.position = position;
                    console.log($scope.position);
                });
            });
        }
    };


});