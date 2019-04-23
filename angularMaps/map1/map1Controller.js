var mapApp = angular.module('mapApp', []);

mapApp.controller('map1Controller', ['$scope', '$http', function ($scope, $http) {

    $scope.init = function () {
        $scope.caricaDati();
    };

    $scope.caricaDati = function () {
        //instanzio la mappa e la lego al div con id 'map'
        $scope.map = new google.maps.Map(document.getElementById('map'), {
               zoom: 4.2,
               center: new google.maps.LatLng(48, 10), //italia
               mapTypeControl: true,
               mapTypeId: 'roadmap'
        });

        $scope.type = $scope.map.mapTypeId;

        $scope.geolocalizza();

        $scope.getData();
    };

    $scope.geolocalizza = function () {
        infoWindow = new google.maps.InfoWindow();

        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {lat: position.coords.latitude,lng: position.coords.longitude};
                infoWindow.setPosition(pos);
                infoWindow.setContent('Location found.');
                infoWindow.open(map);
                $scope.map.setCenter(pos);
            }, function() {
                handleLocationError(true, infoWindow, $scope.map.getCenter());
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, $scope.map.getCenter());
        }
    };

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ? "Error: The Geolocation service failed." : "Error: Your browser doesn't support geolocation.");
        infoWindow.open($scope.map);
    }

    $scope.getData = function () {

        $http.post('map1Handler.php',{'function': 'getCities'}
        ).then(function (data) {
            console.log(data);
            $scope.cities = data.data.cities;
        }).then(function () {
            $scope.setMarker($scope.cities);
        });
    };

    $scope.setMarker = function (data) {

        if (!data.length > 0) {
            alert('Nessun marker');
        } else {

            console.log(data);
            $scope.markers = [];

            data.forEach(function (c) {

                //instanzio l'oggetto marker + setto i contenuti che visualizzero in infoWindow
                var marker = new google.maps.Marker({
                    map: $scope.map,
                    position: new google.maps.LatLng(c.pos[0], c.pos[1]),
                    title: c.name,
                    icon: c.icon,
                    animation: google.maps.Animation.DROP,
                    content: '<div class="text-justify">' + c.desc + '<br/><a href="https://it.wikipedia.org/w/index.php?title=' + c.name + '" target="_blank"> more info</a> <br/><br/>Lat: ' + c.pos[0] + ' Lon:' + c.pos[1] + '</div>'
                });

                //instanzio l'oggetto infoWindow (finestra che si apre sulla mappa, contenitore)
                infoWindow = new google.maps.InfoWindow({
                    maxWidth: 400
                });

                //collego InfoWindow e Marker
                google.maps.event.addListener(marker, 'click', function () {
                    infoWindow.setContent('<h2>' + marker.title + '</h2>' + marker.content);
                    infoWindow.open($scope.map, marker);
                });

                $scope.markers.push(marker);
            });
        }
    };

    $scope.openInfoWindow = function (c) {
        infoWindow.close($scope.map);
        infoWindow = new google.maps.InfoWindow({
            position: new google.maps.LatLng(c.pos[0], c.pos[1]),
            content: '<div class="text-justify">' + c.desc + '<br/><a href="https://it.wikipedia.org/w/index.php?title=' + c.name + '" target="_blank"> more info</a> <br/><br/>Lat: ' + c.pos[0] + ' Lon:' + c.pos[1] + '</div>',
            maxWidth: 400
        });
        infoWindow.open($scope.map);
    };

    $scope.$watch(function() {
        return $scope.filtered;
    }, function() {
        $scope.updateMarkers($scope.filtered);
    });


    $scope.updateMarkers = function (data) {
        for(i=0; i<$scope.markers.length; i++){
            $scope.markers[i].setMap(null);
        }
        $scope.setMarker(data);
    };

}]);