/**
 * configure RequireJS
 * prefer named modules to long paths, especially for version mgt
 * or 3rd party libraries
 */
require.config({

    paths: {
        'angular': ['//yandex.st/angularjs/1.2.16/angular.min', '../lib/angular/angular'],
        'angular-route': '../lib/angular-route/angular-route',
        'angular-sanitize': '../lib/angular-sanitize/angular-sanitize',
        'angular-resource': '../lib/angular-resource/angular-resource',
        'angular-translate': '../lib/angular-translate/angular-translate.min',
        'domReady': '../lib/requirejs-domready/domReady',
        'underscore': ['http://yandex.st/underscore/1.6.0/underscore-min', '../lib/underscore/underscore'],

        'jquery': ['//yandex.st/jquery/1.10.2/jquery.min', '../lib/jquery/jquery-1.11.1.min'],
        'jquery-ui': ['//yandex.st/jquery-ui/1.10.4/jquery-ui.min', '../lib/jquery/jquery-ui.min'],
        'bootstrap3': ['//yandex.st/bootstrap/3.1.1/js/bootstrap.min', '../lib/bootstrap/bootstrap.min'],
        'jquery-uniform': ['plugins/forms/uniform.min'],
        //'jquery-autosize': ['plugins/forms/autosize'],
        'jquery-select2': ['plugins/forms/select2.min'],
        'jquery-jgrowl': ['plugins/interface/jgrowl.min'],
        'howler': '../lib/howler/howler.min'
    },

    /**
     * for libs that either do not support AMD out of the box, or
     * require some fine tuning to dependency mgt'
     */
    shim: {
        'angular': {
            exports: 'angular'
        },
        'angular-route': {
            deps: ['angular']
        },
        'angular-translate': {
            deps: ['angular']
        },
        'angular-sanitize': {
            deps: ['angular']
        },
        'angular-resource': {
            deps: ['angular']
        },
        /*'howler': {
            exports: "Howl"
        },*/
        'jquery-ui': {
            exports: "$",
            deps: ['jquery']
        },
        'jquery-uniform' : ['jquery-ui'],
        // "jquery-autosize" : ['jquery-ui'],
        'jquery-jgrowl' : ['jquery-ui'],
        'jquery-select2' : ['jquery-ui'],
        'bootstrap3': ['jquery'],
        'underscore': {
            exports: "_"
        }
    },

    deps: [
        // kick start application... see bootstrap.js
        './bootstrap'
    ]
});



