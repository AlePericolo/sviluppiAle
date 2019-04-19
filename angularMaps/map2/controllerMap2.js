var mapApp = angular.module('mapApp', []);

mapApp.controller('map2Controller', function ($scope) {

    $scope.init = function () {
        $scope.caricaDati();
    };

    $scope.caricaDati = function () {
        //instanzio la mappa e la lego al div con id 'map'
        $scope.map = new google.maps.Map(document.getElementById('map'), {
               zoom: 6,
               center: new google.maps.LatLng(43, 10), //italia
               mapTypeId: google.maps.MapTypeId.SATELLITE
        });

        $scope.geolocalizza();

        //gestione marker
        $scope.setMarker();
    };

    $scope.setMarker = function () {

        $scope.cities = getCities();
        if (!$scope.cities.length > 0) {
            alert('Nessun marker');
        } else {
            $scope.markers = [];

            $scope.cities.forEach(function (c) {
                //instanzio l'oggetto infoWindow (finestra che si apre sulla mappa, contenitore)
                infoWindow = new google.maps.InfoWindow();

                //instanzio l'oggetto marker + setto i contenuti che visualizzero in infoWindow
                var marker = new google.maps.Marker({
                    map: $scope.map,
                    position: new google.maps.LatLng(c.pos[0], c.pos[1]),
                    title: c.name,
                    icon: c.icon,
                    content : '<div> '+ c.desc +'<br/><a href="https://it.wikipedia.org/w/index.php?title='+c.name+'" target="_blank"> more info</a> <br/><br/>Lat: ' + c.pos[0] + ' Lon:' + c.pos[1] +'</div>'
                });

                //collegon InfoWindow e Marker
                google.maps.event.addListener(marker, 'click', function () {
                    infoWindow.setContent('<h2>' + marker.title + '</h2>' + marker.content);
                    infoWindow.open($scope.map, marker);
                });

                $scope.markers.push(marker);
            });
        }
    };

    $scope.openInfoWindow = function (e, selectedMarker) {
        e.preventDefault();
        google.maps.event.trigger(selectedMarker, 'click');
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
        infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open($scope.map);
    }

});

function getCities() {
    return [
        {id: 1, name: 'Milano', desc: "Milano &eacute; un comune italiano capoluogo della regione Lombardia, dell'omonima citt&aacute; metropolitana, e centro di una delle pi$uacute; popolose aree metropolitane d'Europa.", pos: [45.4642700, 9.1895100], icon: {url: 'img/milan.png'}},
        {id: 1, name: 'Pisa', desc: "Pisa &eacute; un comune italiano capoluogo della provincia omonima in Toscana.", pos: [43.7085300, 10.4036000], icon: {url: 'img/pisa.png'}},
        {id: 1, name: 'Roma', desc: "Roma, capitale della Repubblica Italiana, nonch&eacute; capoluogo dell'omonima citt&aacute; metropolitana e della regione Lazio. La citt&aacute; &eacute; dotata di un ordinamento amministrativo speciale, denominato Roma Capitale, disciplinato da una legge dello Stato.", pos: [41.8919300, 12.5113300], icon: {url: 'img/rome.png'}},
        {id: 1, name: 'Venezia', desc: "Venezia &eacute; un comune italiano capoluogo dell'omonima citt&aacute; metropolitana e della regione Veneto.", pos: [45.4371300, 12.3326500], icon: {url: 'img/venice.png'}}
    ];
}

