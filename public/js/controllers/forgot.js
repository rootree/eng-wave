define(['./module'], function (controllers) {
    'use strict';
    controllers.controller('ForgotController',  function($scope, $rootScope, $location, AuthenticationService, SessionService, FlashService) {

        $scope.isLoggedIn = AuthenticationService.isLoggedIn();
    });
});
