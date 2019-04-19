var mapApp = angular.module('mapApp', ['ui.bootstrap', 'ngMap']);
mapApp.controller('map1Controller', function ($scope, NgMap) {

    //milano
    $scope.geopos = {lat:45.4642700,lng:9.1895100};

    NgMap.getMap().then(function (map) {
        $scope.map = map;
    });

    $scope.cities = [
        { id: 1, name: 'Legnano', pos: [45.5978800, 8.9150600] , icon: {url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'}},
        { id: 2, name: 'Cinisello Balsamo', pos: [45.5582300, 9.2149500] },
        { id: 3, name: 'Robbio', pos: [45.2890100, 8.5928900] },
        { id: 4, name: 'San Giuliano Milanese', pos: [45.3940200, 9.2910900] },
        { id: 5, name: 'Vimodrone', pos: [45.5146100, 9.2877200] }
    ];

    $scope.setPosition = function (event) {
        $scope.geopos.lat = event.latLng.lat().toPrecision(8);
        $scope.geopos.lng = event.latLng.lng().toPrecision(8);
    };

    $scope.showInfo = function (event,city) {
        alert(JSON.stringify(city));
    };

    $scope.getCurrentPosition = function () {
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(showLocation);
        }else{
            alert('Geolocation is not supported by this browser.');
        }
    };

    function showLocation(position){
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        $http.post('handlerMap1.php',
            {
                'function': 'showLocation',
                'latitude' :latitude,
                'longitude' :longitude
            }
        ).then(function (data) {
            console.log(data);
            $scope.geopos.lat = data.data.latitudine;
            $scope.geopos.lng = data.data.longitude;
        });
    }

});