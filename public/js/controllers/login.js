define(['./module'], function (controllers) {
    'use strict';
    controllers.controller('LoginController',  function($scope, $rootScope, $location, AuthenticationService, SessionService, FlashService) {

        if (AuthenticationService.isLoggedIn()) {
            $location.path('/');
            FlashService.success('Авторизация уже была пройдёна');
        } else {
            $rootScope.userSettings.authenticated = 0;
        }

        //$(".styled, .multiselect-container input").uniform({ radioClass: 'choice', selectAutoWidth: false });

        $scope.credentials = { email: "", password: "" };

        $scope.login = function() {
            AuthenticationService.login($scope.credentials).success(function(response) {
                $rootScope.userSettings = response.userSettings;
                $rootScope.userSettings.groupsContent = [];
                document.globalSettings.CSRF = response.CSRF;
                $location.path('/');
            });
        };
    });
});
