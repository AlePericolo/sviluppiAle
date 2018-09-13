var app = angular.module('myApp', ['ngRoute', 'slickCarousel']);

app.controller('MainCtrl', ['$scope', function($scope){

        $scope.slickConfigLoaded = false;
        $scope.slickCurrentIndex = 0;
        $scope.slickDots = true;

        $scope.slickConfig = {
            autoplay: true,
            dots: true,
            enabled: true,
            focusOnSelect: true,
            infinite: true,
            initialSlide: 0,
            slidesToShow: 5,
            slidesToScroll: 1,
            method: {},
            event: {
                afterChange: function (event, slick, currentSlide, nextSlide) {
                    $scope.slickCurrentIndex = currentSlide;
                },
                init: function (event, slick) {
                    slick.slickGoTo($scope.slickCurrentIndex); // slide to correct index when init
                }
            }
        };

        $scope.items = [{ label: '9788821595509.jpg' },
                        { label: '123.jpg' },
                        { label: '9788821595851.jpg' },
                        { label: '9788821596162.jpg' },
                        { label: '9788821596179.jpg' },
                        { label: '9788821596292.jpg' },
                        { label: '9788821596360.jpg' },
                        { label: '9788821596506.jpg' },
                        { label: '9788821596537.jpg' },
                        { label: '9788821596612.jpg' },
                        { label: '9788821596827.jpg' },
                        { label: '9788821596902.jpg' }];

        $scope.slickConfigLoaded = true;

        $scope.show = function (k) {
            console.log(k);
        }
}]);

