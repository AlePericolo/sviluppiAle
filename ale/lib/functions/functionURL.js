/**
 * Created by clickale on 19/05/17.
 */

function encodeUrl(params) {

    if (params.constructor === Array){
        for (i = 0; i < params.length; i++) {
            params[i] = 'p' + i + '=' + btoa(params[i]);
        }

        return "?" + params.join("&");

    }else{

        return "?p0=" + btoa(params);
    }

}

function decodeUrl(url, keyApp) {
    var app =[];
    var key;

    if (undefined === keyApp && -1 ===url.indexOf('?')){

        var path = url.split("/form");
        app['percorso'] =path[0];
        return app;
    }
    if(keyApp.constructor === Array) {
        key = keyApp;
    }else{
        key = new Array();
        key[0] = keyApp;
    }

    var params = url.split("php?");
    url = url.split("/form");
    app['percorso'] = url[0];

    if(params.length > 1) {
        params = params[1].split("&");

        for (i = 0; i < params.length; i++) {
            app[key[i]] = atob(params[i].replace('p' + i + '=', ''));
        }
    }

    return app;
}



function encodeUrlTest(params) {

    if (params.constructor === Array){
        for (i = 0; i < params.length; i++) {
            params[i] = 'p' + i + '=' + params[i];
        }

        return "?" + params.join("&");

    }else{

        return "?p0=" + params;
    }

}

function decodeUrlTest(url, keyApp) {

    if(keyApp.constructor === Array) {
        key = keyApp;
    }else{
        key = new Array();
        key[0] = keyApp;
    }

    var params = url.split("php?");
    url = url.split("/form");

    app = new Array();
    app['percorso'] = url[0];

    if(params.length > 1){
        params = params[1].split("&");

        for (i = 0; i < params.length; i++) {
            app[key[i]] = params[i].replace('p' + i + '=', '');
        }
    }

    return app;
}