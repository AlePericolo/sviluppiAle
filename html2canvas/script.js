var translationsEN = {

    titoloPagina: "Registration",
    titoloSezioneDatiPersonali: "Personal data",
    labelNome: "Name",
    labelCognome: "Surname",
    labelIndirizzo: "Residence address",
    labelDataNascita: "Date of birth",
    labelLuogoNascita: "Place of birth",
    labelLuogoNascitaDett: "(city and country)",
    labelTelefono: "Phone",
    labelCellulare: "Mobile",
    labelEmail: "E-mail",
    labelAltezza: "Height",

    lingua_IT: "Italian",
    lingua_EN: "English"
};

var translationsIT= {

    titoloPagina: "Registrazione",
    titoloSezioneDatiPersonali: "Dati personali",
    labelNome: "Nome",
    labelCognome: "Cognome",
    labelIndirizzo: "Indirizzo residenza",
    labelDataNascita: "Data di nascita",
    labelLuogoNascita: "Luogo di nascita",
    labelLuogoNascitaDett: "(città e nazione)",
    labelTelefono: "Telefono",
    labelCellulare: "Cellulare",
    labelEmail: "E-mail",
    labelAltezza: "Altezza",

    lingua_IT: "Italiano",
    lingua_EN: "Inglese"
};

var app = angular.module('dscApp', ['pascalprecht.translate']);

app.config(['$translateProvider', function ($translateProvider) {
    // add translation tables
    $translateProvider.translations('en', translationsEN);
    $translateProvider.translations('it', translationsIT);
    $translateProvider.fallbackLanguage('it');
    $translateProvider.preferredLanguage('it');
}]);

app.controller('moduloNoleggioController', ['$translate', '$scope', function ($translate, $scope) {

    $scope.ita = true;
    $scope.changeLanguage = function (langKey) {
        $translate.use(langKey);
        if(langKey == 'it'){
            $scope.ita = true;
        }else{
            $scope.ita = false;
        }
    };

    //data for ng-repeat
    $scope.giorni=[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
    $scope.mesi=[1,2,3,4,5,6,7,8,9,10,11,12];
    $scope.anni=[];
    var i;
    $scope.currentYear = (new Date()).getFullYear();
    $scope.currentYear10 = $scope.currentYear + 10;

    for(i = $scope.currentYear; i > 1900; i --){
        $scope.anni.push(i);
    }

    $scope.scaricaPDF = function(){

        var doc = new jsPDF('P', 'mm', [210, 297]);

        doc.setFont("helvetica");
        doc.setFontType("bold");
        doc.setFontSize(10);

        doc.rect(10,6,70,5); //margine sx, margine alto, lunghezza (+margine sx), altezza (da margine alto)
        doc.text(10,10, translationsIT.titoloSezioneDatiPersonali); //margine sx, margine alto

        doc.rect(10,16,70,5); //margine sx, margine alto, lunghezza (+margine sx), altezza (da margine alto)
        doc.text(10,20, 'prova stampa2'); //margine sx, margine alto ToDO sostituire con ngModel per usare i dati scritti in pagina

        doc.rect(10,26,70,5); //margine sx, margine alto, lunghezza (+margine sx), altezza (da margine alto)
        doc.text(10,30, 'prova stampa3'); //margine sx, margine alto ToDO sostituire con ngModel per usare i dati scritti in pagina

        doc.save('test.pdf');
    };

}]);