/**
 * Defines the main routes in the application.
 * The routes you see here will be anchors '#/' unless specifically configured otherwise.
 */

define(['./app'], function (app) {
    'use strict';
    return app.config(['$routeProvider', function ($routeProvider) {

        $routeProvider.when('/login', {
            title: 'TITLE_AUTH',
            hasTutorial: 0,
            hasSideMenu: 0,
            templateUrl: 'templates/login.html',
            controller: 'LoginController'
        });
        $routeProvider.when('/suggestion', {
            title: 'TITLE_SUGGESTION',
            hasTutorial: 0,
            hasSideMenu: 0,
            templateUrl: 'templates/suggestion.html',
            controller: 'SuggestionController'
        });
        $routeProvider.when('/support', {
            title: 'TITLE_SUPPORT',
            hasTutorial: 0,
            hasSideMenu: 0,
            templateUrl: 'templates/support.html',
            controller: 'SupportController'
        });
        $routeProvider.when('/about', {
            title: 'TITLE_ABOUT',
            hasTutorial: 0,
            hasSideMenu: 0,
            templateUrl: 'templates/about.html'
            //controller: 'AboutController'
        });
        $routeProvider.when('/reg', {
            title: 'TITLE_REGISTRATION',
            hasTutorial: 0,
            hasSideMenu: 0,
            templateUrl: 'templates/reg.html',
            controller: 'RegController'
        });
        $routeProvider.when('/forgot', {
            title: 'TITLE_PASSWORD_RECOVERY',
            hasTutorial: 0,
            hasSideMenu: 0,
            templateUrl: 'templates/forgot.html',
            controller: 'ForgotController'
        });

        // Требуют авторизации
        $routeProvider.when('/strategies', {
            title: 'TITLE_STRATEGIES',
            hasTutorial: 1,
            hasSideMenu: 0,
            templateUrl: 'templates/strategies.html',
            controller: 'StrategiesController'
        });
        $routeProvider.when('/downloads', {
            title: 'TITLE_DOWNLOADS',
            hasTutorial: 1,
            hasSideMenu: 0,
            templateUrl: 'templates/downloads.html',
            controller: 'DownloadsController'
        });
        $routeProvider.when('/settings', {
            title: 'TITLE_SETTINGS',
            hasTutorial: 0,
            hasSideMenu: 0,
            templateUrl: 'templates/settings.html',
            controller: 'SettingsController'
        });
        $routeProvider.when('/packages', {
            title: 'TITLE_PACKS',
            hasTutorial: 0,
            hasSideMenu: 0,
            templateUrl: 'templates/packages.html',
            controller: 'PackagesController'
        });
        $routeProvider.when('/:groupID?', {
            title: 'TITLE_WORDS',
            hasTutorial: 1,
            hasSideMenu: 1,
            templateUrl: 'templates/words.html',
            controller: 'WordsController',
            resolve: {
                /*words : function(WordsService) {
                 return WordsService.init();
                 }*/
            }
        });
        $routeProvider.otherwise({ redirectTo: '/' });
    }]);
});


