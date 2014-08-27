/**
 * Defines the main routes in the application.
 * The routes you see here will be anchors '#/' unless specifically configured otherwise.
 */

define(['./app'], function (app) {
    'use strict';
    return app.config(['$httpProvider', function ($httpProvider) {

        var logsOutUserOn401 = function($location, $q, $rootScope, SessionService, FlashService) {

            var success = function(response) {
                $rootScope.isLoading = false;
                // $('#overlay').fadeOut(150);

                return response;
            };

            var error = function(response) {
                if(response.status === 401) {
                    SessionService.unset('authenticated');
                    $location.path('/login');
                    FlashService.error(response.data.flash);
                }
                if(response.status === 400) {
                    if (_.isString(response.data.flash)) {
                        FlashService.error(response.data.flash);
                    } else {
                        FlashService.error('Произошла неизвестная ошибка');
                    }
                }
                if(response.status === 404) {
                    FlashService.error('Запрошенная страница не найдена');
                }
                if(response.status === 500) {
                    FlashService.error('Сервис временно недоступен');
                }
                $rootScope.isLoading = false;
                // $('#overlay').fadeOut(150);
                return $q.reject(response);
            };

            return function(promise) {
                return promise.then(success, error);
            };
        };
        $httpProvider.responseInterceptors.push(logsOutUserOn401);

        var sessionInjector = function($location, $q, $rootScope) {
            return {
                 request : function(config) {
                      $rootScope.isLoading = true;
                     // $('#overlay').fadeIn(100);
                     var sep = config.url.indexOf('?') === -1 ? '?' : '&';
                     config.url = config.url + sep + '_auth_token=' + document.globalSettings.CSRF;
                     return config;
                }
            }
        };
        $httpProvider.interceptors.push(sessionInjector);

    }]);
});


