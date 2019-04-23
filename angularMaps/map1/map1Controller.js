var mapApp = angular.module('mapApp', []);

mapApp.controller('map1Controller', function ($scope) {

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

        $scope.geolocalizza();

        $scope.setMarker();
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

    $scope.setMarker = function () {

        $scope.cities = getCities();
        if (!$scope.cities.length > 0) {
            alert('Nessun marker');
        } else {
            $scope.markers = [];

            $scope.cities.forEach(function (c) {

                //instanzio l'oggetto marker + setto i contenuti che visualizzero in infoWindow
                var marker = new google.maps.Marker({
                    map: $scope.map,
                    position: new google.maps.LatLng(c.pos[0], c.pos[1]),
                    title: c.name,
                    icon: c.icon,
                    animation: google.maps.Animation.DROP,
                    content : '<div> '+ c.desc +'<br/><a href="https://it.wikipedia.org/w/index.php?title='+c.name+'" target="_blank"> more info</a> <br/><br/>Lat: ' + c.pos[0] + ' Lon:' + c.pos[1] +'</div>'
                });

                //instanzio l'oggetto infoWindow (finestra che si apre sulla mappa, contenitore)
                infoWindow = new google.maps.InfoWindow({
                    maxWidth: 400
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

});

function getCities() {
    return [

        {id: 1, name: 'Amsterdam', desc: "Amsterdam è la capitale e la maggiore città dei Paesi Bassi, nella provincia dell'Olanda Settentrionale. Possiede uno dei maggiori centri rinascimentali di tutta l'Europa.", pos: [52.366667, 4.866667], icon: {url: 'img/amsterdam.png'}},
        {id: 2, name: 'Atene', desc: "Atene è un comune greco di 655 780 abitanti, capitale della Repubblica Ellenica, capoluogo dell'unità periferica di Atene Centrale e della periferia dell'Attica. È nota in tutto il mondo per la nascita della democrazia.", pos: [37.966667, 23.716667], icon: {url: 'img/atene.png'}},
        {id: 3, name: 'Berlino', desc: "Berlino  è la maggiore città e anche un Bundesland della Germania, quindi una 'città-stato'. Capitale federale della Repubblica Federale di Germania e sede del suo governo, è uno dei più importanti centri politici, culturali, scientifici, fieristici e mediatici d'Europa.", pos: [52.516667, 13.383333], icon: {url: 'img/berlino.png'}},
        {id: 4, name: 'Budapest', desc: "Budapest è la capitale e la maggiore città dell'Ungheria. nacque ufficialmente nel 1873 dall'unione delle città storiche di Buda e Óbuda, ubicate sulla sponda destra del Danubio, con l'abitato di Pest, situato sulla riva opposta del fiume e anch'esso di antiche origini.", pos: [47.498333, 19.040833], icon: {url: 'img/budapest.png'}},
        {id: 5, name: 'Istanbul', desc: "Istanbul storicamente conosciuta come Bisanzio, Costantinopoli o Nuova Roma è la città capoluogo della provincia omonima e il principale centro industriale, finanziario e culturale della Turchia.", pos: [41.016667, 28.966667], icon: {url: 'img/istanbul.png'}},
        {id: 6, name: 'Londra', desc: "Londra  è la capitale e maggiore città dell'Inghilterra e del Regno Unito, con i suoi 8 825 000 abitanti; è anche sede del più antico sistema di metropolitana del mondo, la London Underground (The Tube). È la seconda città più visitata al mondo dal turismo internazionale.", pos: [51.507222, -0.1275], icon: {url: 'img/londra.png'}},
        {id: 7, name: 'Madrid', desc: "Madrid è la capitale e la città più popolosa della Spagna. Oltre che capitale del Paese, Madrid è anche sede del governo e residenza del monarca spagnolo, ed è il centro politico della Spagna.", pos: [40.499999, -3.673333], icon: {url: 'img/madrid.png'}},
        {id: 8, name: 'Mosca', desc: "Mosca è la capitale, la città più popolosa nonché il principale centro economico e finanziario della Russia. È la prima città d'Europa per popolazione e superficie, e la residenza di circa un decimo dei cittadini russi. È dunque la decima città più popolosa del mondo nonché, a causa del suo rigido clima continentale, la megalopoli più settentrionale e fredda della Terra. Mosca è una delle maggiori economie urbane globali", pos: [55.751667, 37.617778], icon: {url: 'img/mosca.png'}},
        {id: 9, name: 'Parigi', desc: "Parigi è la capitale e la città più popolata della Francia, capoluogo della regione dell'Île-de-France e l'unico comune a essere nello stesso tempo dipartimento. Con oltre 37 milioni di turisti l'anno, Parigi è la città più visitata al mondo, e secondo la rivista The Economist (2010), anche la più cara.", pos: [48.856667, 2.351944], icon: {url: 'img/parigi.png'}},
        {id: 10, name: 'Roma', desc: "Roma, capitale della Repubblica Italiana, nonch&eacute; capoluogo dell'omonima citt&aacute; metropolitana e della regione Lazio. Il suo centro storico, delimitato dal perimetro delle mura aureliane, è stato inserito nella lista dei Patrimoni dell'umanità dell'UNESCO. Roma, cuore della cristianità cattolica, è l'unica città al mondo ad ospitare al proprio interno un intero Stato, l'enclave della Città del Vaticano.", pos: [41.8919300, 12.5113300], icon: {url: 'img/roma.png'}},

    ];
}

