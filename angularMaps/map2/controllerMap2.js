var mapApp = angular.module('mapApp', []);
mapApp.controller('map2Controller', function ($scope) {

  var mapOptions = {
      zoom: 4,
      center: new google.maps.LatLng(25,80),
      mapTypeId: google.maps.MapTypeId.ROADMAP
  };

var cities = [
    /*
    { id: 1, name: 'Milano', desc: '', pos: [45.4642700, 9.1895100] , icon: {url: 'img/milan.png'}},
    { id: 1, name: 'Pisa', desc: '', pos: [43.7085300, 10.4036000] , icon: {url: 'img/milan.png'}},
    { id: 1, name: 'Roma', desc: '', pos: [41.8919300, 12.5113300] , icon: {url: 'img/milan.png'}},
    { id: 1, name: 'Venezia', desc: '', pos: [45.4371300, 12.3326500] , icon: {url: 'img/milan.png'}},
    */
    {
        place : 'India',
        desc : 'A country of culture and tradition!',
        lat : 23.200000,
        long : 79.225487
    },
    {
        place : 'New Delhi',
        desc : 'Capital of India...',
        lat : 28.500000,
        long : 77.250000
    },
    {
        place : 'Kolkata',
        desc : 'City of Joy...',
        lat : 22.500000,
        long : 88.400000
    },
    {
        place : 'Mumbai',
        desc : 'Commercial city!',
        lat : 19.000000,
        long : 72.90000
    },
    {
        place : 'Bangalore',
        desc : 'Silicon Valley of India...',
        lat : 12.9667,
        long : 77.5667
    }

];

  $scope.map = new google.maps.Map(document.getElementById('map'), mapOptions);

  $scope.markers = [];

  var infoWindow = new google.maps.InfoWindow();

  var createMarker = function (info){

      var marker = new google.maps.Marker({
          map: $scope.map,
          position: new google.maps.LatLng(info.lat, info.long),
          title: info.place
      });
      marker.content = '<div class="infoWindowContent">' + info.desc + '<br />' + info.lat + ' E,' + info.long +  ' N, </div>';

      google.maps.event.addListener(marker, 'click', function(){
          infoWindow.setContent('<h2>' + marker.title + '</h2>' +
            marker.content);
          infoWindow.open($scope.map, marker);
      });

      $scope.markers.push(marker);

  }

  for (i = 0; i < cities.length; i++){
      createMarker(cities[i]);
  }

  $scope.openInfoWindow = function(e, selectedMarker){
      e.preventDefault();
      google.maps.event.trigger(selectedMarker, 'click');
  }

});