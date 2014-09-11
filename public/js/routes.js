/**
 * Defines the main routes in the application.
 * The routes you see here will be anchors '#/' unless specifically configured otherwise.
 */

define(['./app'], function (app) {
    'use strict';
    return app.config(['$routeProvider', function ($routeProvider) {

        $routeProvider.when('/login', {
            title: 'Авторизация',
            hasTutorial: 0,
            templateUrl: 'templates/login.html',
            controller: 'LoginController'
        });
        $routeProvider.when('/suggestion', {
            title: 'Предложить совет',
            hasTutorial: 0,
            templateUrl: 'templates/suggestion.html',
            controller: 'SuggestionController'
        });
        $routeProvider.when('/support', {
            title: 'Поддержка',
            hasTutorial: 0,
            templateUrl: 'templates/support.html',
            controller: 'SupportController'
        });
        $routeProvider.when('/about', {
            title: 'О проекте',
            hasTutorial: 0,
            templateUrl: 'templates/about.html'
            //controller: 'AboutController'
        });
        $routeProvider.when('/reg', {
            title: 'Регистрация',
            hasTutorial: 0,
            templateUrl: 'templates/reg.html',
            controller: 'RegController'
        });
        $routeProvider.when('/forgot', {
            title: 'Восстановление пароля',
            hasTutorial: 0,
            templateUrl: 'templates/forgot.html',
            controller: 'ForgotController'
        });

        // Требуют авторизации
        $routeProvider.when('/strategies', {
            title: 'Стратегии',
            hasTutorial: 1,
            templateUrl: 'templates/strategies.html',
            controller: 'StrategiesController'
        });
        $routeProvider.when('/downloads', {
            title: 'Загрузки',
            hasTutorial: 1,
            templateUrl: 'templates/downloads.html',
            controller: 'DownloadsController'
        });
        $routeProvider.when('/settings', {
            title: 'Настройки',
            templateUrl: 'templates/settings.html',
            controller: 'SettingsController'
        });
        $routeProvider.when('/packages', {
            title: 'Пакеты',
            hasTutorial: 0,
            templateUrl: 'templates/packages.html',
            controller: 'PackagesController'
        });
        $routeProvider.when('/:groupID?', {
            title: 'Слова',
            hasTutorial: 1,
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


