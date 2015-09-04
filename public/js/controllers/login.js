define(['./module'], function (controllers) {
    'use strict';
    controllers.controller('LoginController',  function($scope, $rootScope, $location, $translate,
                                                        AuthenticationService, SessionService, FlashService
        ) {

        if (AuthenticationService.isLoggedIn()) {
            $location.path('/');
            FlashService.success($translate.instant('MESSAGE_AUTH_COMPLETED'));
        } else {
            $rootScope.userSettings.authenticated = 0;
        }

        //$(".styled, .multiselect-container input").uniform({ radioClass: 'choice', selectAutoWidth: false });

        $scope.credentials = { email: "", password: "" };

        $scope.login = function() {
            $rootScope.isDemo = false;
            AuthenticationService.login($scope.credentials).success(_authorized);
        };

        $scope.demo = function() {
            var demoCredentials = {
                email : document.globalSettings.demo.email,
                password : document.globalSettings.demo.password
            };
            $rootScope.isDemo = true;
            AuthenticationService.login(demoCredentials).success(_authorized);
        };

        $scope.development = function() {
            FlashService.success($translate.instant('MESSAGE_DEVELOPING'));
        };

        var _authorized = function(response) {
            $rootScope.userSettings = response.userSettings;
            $rootScope.userSettings.groupsContent = [];
            document.globalSettings.CSRF = response.CSRF;
            $location.path('/');
        };
    });
});
