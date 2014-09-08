/**
 * Defines the main routes in the application.
 * The routes you see here will be anchors '#/' unless specifically configured otherwise.
 */

define(['./app'], function (app) {
    'use strict';
    return app.config(['$httpProvider', function ($httpProvider) {

        var logsOutUserOn401 = function($location, $q, $rootScope, $translate, SessionService, FlashService) {

            var success = function(response) {
                $rootScope.isLoading --;
                return response;
            };

            var error = function(response) {

                if(response.status === 401) {
                    SessionService.unset('authenticated');
                    $location.path('/login');
                    FlashService.error($translate.instant('RESPONSE_CODE_1005'));
                }
                if(response.status === 400) {
                    FlashService.error($translate.instant('RESPONSE_CODE_' + response.data.code));
                }
                if(response.status === 404) {
                    FlashService.error($translate.instant('RESPONSE_CODE_1004'));
                }
                if(response.status === 500) {
                    FlashService.error($translate.instant('RESPONSE_CODE_1003'));
                }
                $rootScope.isLoading = false;

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
                     $rootScope.isLoading ++;
                     var sep = config.url.indexOf('?') === -1 ? '?' : '&';
                     config.url = config.url + sep + '_auth_token=' + document.globalSettings.CSRF;
                     return config;
                }
            }
        };
        $httpProvider.interceptors.push(sessionInjector);

    }]);
});


