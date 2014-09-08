/**
 * Defines the main routes in the application.
 * The routes you see here will be anchors '#/' unless specifically configured otherwise.
 */

define(['./app'], function (app) {
    'use strict';
    return app.config(['$routeProvider', function ($routeProvider) {

        $routeProvider.when('/login', {
            title: 'Авторизация',
            templateUrl: 'templates/login.html',
            controller: 'LoginController'
        });
        $routeProvider.when('/suggestion', {
            title: 'Предложить совет',
            templateUrl: 'templates/suggestion.html',
            controller: 'SuggestionController'
        });
        $routeProvider.when('/support', {
            title: 'Поддержка',
            templateUrl: 'templates/support.html',
            controller: 'SupportController'
        });
        $routeProvider.when('/about', {
            title: 'О проекте',
            templateUrl: 'templates/about.html'
            //controller: 'AboutController'
        });
        $routeProvider.when('/reg', {
            title: 'Регистрация',
            templateUrl: 'templates/reg.html',
            controller: 'RegController'
        });
        $routeProvider.when('/forgot', {
            title: 'Восстановление пароля',
            templateUrl: 'templates/forgot.html',
            controller: 'ForgotController'
        });

        // Требуют авторизации
        $routeProvider.when('/strategies', {
            title: 'Стратегии',
            templateUrl: 'templates/strategies.html',
            controller: 'StrategiesController'
        });
        $routeProvider.when('/downloads', {
            title: 'Загрузки',
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
            templateUrl: 'templates/packages.html',
            controller: 'PackagesController'
        });
        $routeProvider.when('/:groupID?', {
            title: 'Слова',
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


