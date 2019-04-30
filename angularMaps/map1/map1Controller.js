var mapApp = angular.module('mapApp', []);

mapApp.controller('map1Controller', ['$scope', '$http', function ($scope, $http) {

    $scope.init = function () {
        $scope.caricaDati();
    };

    $scope.caricaDati = function () {
        //instanzio la mappa e la lego al div con id 'map'
        $scope.map = new google.maps.Map(document.getElementById('map'), {
               zoom: 4.5,
               center: new google.maps.LatLng(48, 10), //italia
               mapTypeControl: true,
               mapTypeId: 'roadmap',
               streetViewControl: true
        });
        $scope.type = $scope.map.mapTypeId;

        $scope.geolocalizza();

        $scope.getData();

        $scope.selectedCities = [];
        $scope.polygons = [];
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

    //recupero l'elenco delle città
    $scope.getData = function () {

        $http.post('map1Handler.php',{'function': 'getCities'}
        ).then(function (data) {
            $scope.cities = data.data.cities;
        }).then(function () {
            $scope.setMarker($scope.cities);
        });
    };

    //passo l'array delle città e per ogni città instanzio il suo marker + infowindow sulla mappa
    $scope.setMarker = function (data) {

        if (data.length > 0) {

            $scope.markers = [];

            data.forEach(function (c) {

                //instanzio l'oggetto marker + assegno i contenuti che visualizzero in infoWindow e lo setto sull mappa
                var marker = new google.maps.Marker({
                    map: $scope.map,
                    position: new google.maps.LatLng(c.pos[0], c.pos[1]),
                    title: c.name,
                    icon: c.icon,
                    animation: google.maps.Animation.DROP //BOUNCE
                });

                //ad ogni marker aggiungo l'evento che al click apre l'infoWindow
                google.maps.event.addListener(marker, 'click', function () {
                    $scope.cityInfo(c);
                });

                $scope.markers.push(marker);
            });
        }
    };

    //creo e apro infowindow in corrispondenza della città che passo (se ci sono infowindow aperti li chiudo)
    $scope.cityInfo = function (c) {
        infoWindow.close($scope.map);
        infoWindow = new google.maps.InfoWindow({
            position: new google.maps.LatLng(c.pos[0], c.pos[1]),
            content: '<h3>' + c.name + '</h3>' +
                     '<div class="text-justify">' + c.desc + '</div>' +
                     '<p><a href="https://it.wikipedia.org/w/index.php?title=' + c.name + '" target="_blank"> More Info</a></p>' +
                     '<b>Latitudine:</b> ' + c.pos[0] + '&deg; <b>Longitudine:</b> ' + c.pos[1] + '&deg;',
            maxWidth: 400
        });
        infoWindow.open($scope.map);
    };

    //monitoro il filtro sulle città in pagina appena cambia chiamo l'update
    $scope.$watch(function(){
        return $scope.filtered;
    },function() {
        $scope.updateMarkers($scope.filtered);
    });

    //rimuovo tutti i marker e richiamo la funzione per risettarli sulle città filtrate
    $scope.updateMarkers = function (data) {
        if($scope.markers){
            $scope.markers.forEach(function (m) {
               m.setMap(null);
            });
            $scope.setMarker(data);
        }
    };

    $scope.settings = {
        showCheckAll: false,
        keyboardControls: true,
        scrollable: true,
        scrollableHeight: '30vh',
        externalIdProp: '',
        idProp: 'id',
        displayProp: 'name',
        buttonClasses: 'btn btn-outline-dark'
    };

    $scope.customText = {
        buttonDefaultText: 'Select Cities',
        uncheckAll: 'Deselect all',
    };

    //disegno un poligono prendento come vertici le coordinate delle città che ho selezionato
    $scope.drawPolygon = function () {

        console.log($scope.selectedCities);
        var path = [];
        var title = '';
        var coordinate = '';
        $scope.selectedCities.forEach(function (c) {
            var p = {lat: c.pos[0], lng: c.pos[1]};
            path.push(p);
            title += c.name + ' ';
            coordinate += '<b>' + c.name + ':</b> ' + c.pos[0] + '&deg; ' + c.pos[1] + '&deg;<br>'
        });

        var polygon = new google.maps.Polygon({
            paths: path,
            strokeColor: '#a8c2ff',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#d3eeff',
            fillOpacity: 0.35,
            content: '<h3>' + title + '</h3>' +
                     '<p>' + coordinate + '</p>'
        });
        polygon.setMap($scope.map);

        //al poligono aggiungo l'evento che al click apre l'infoWindow
        google.maps.event.addListener(polygon, 'click', function (event) {
            var clickCoordinates = [event.latLng.lat(), event.latLng.lng()];
            $scope.polygonInfo(polygon, clickCoordinates)
        });
        //aggiungo il poligono all'array dei poligoni
        $scope.polygons.push(polygon);
    };

    //infoWindow sul poligono che ho clickato che si apre in corrispondenza del click
    $scope.polygonInfo = function (polygon, clickCoordinates) {

        infoWindow.close($scope.map);
        infoWindow = new google.maps.InfoWindow({
            position: new google.maps.LatLng(clickCoordinates[0], clickCoordinates[1]),
            content:  polygon.content +
                     '<p> Clicked location: ' + clickCoordinates[0] + '&deg; ' + clickCoordinates[1] + '&deg;</p>',
            maxWidth: 400
        });
        infoWindow.open($scope.map);
    };

    //scorro l'array dei poligoni e li rimuovo
    $scope.deletePolygon = function () {
        if($scope.polygons){
            $scope.polygons.forEach(function (p) {
                p.setMap(null);
            });
        }
    };

}]);

$('select').selectpicker();