/**
 * loads sub modules and wraps them up into the main module
 * this should be used for top-level module definitions only
 */
define([
    'angular',
    'angular-route',
    'angular-sanitize',
    'angular-translate',

    'underscore',

    'jquery',
    'bootstrap3',

    'jquery-ui',
    'jquery-uniform',
    //'jquery-autosize',
    'jquery-jgrowl',
    'jquery-select2',

    'howler',

    './controllers/index',
    './directives/index',
    './filters/index',
    './services/index'
], function (angular) {
    'use strict';
    return angular.module('app', [
        'app.services',
        'app.controllers',
        'app.directives',
        'app.filters',
        'ngRoute',
        'ngSanitize',
        'pascalprecht.translate'
    ]);
});
