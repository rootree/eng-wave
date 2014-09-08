define(['./module'], function (controllers) {
    'use strict';
    controllers.controller('RegController',  function($scope, $rootScope, $location, $translate, AuthenticationService, SessionService, FlashService, UserService) {

        if (AuthenticationService.isLoggedIn()) {
            $location.path('/');
            FlashService.show($translate.instant('MESSAGE_AUTH_COMPLETED'));
            return;
        }

        //===== Работа с сервисами =====//


        //===== Работа с интерфейсом =====//

        $scope.registration = function(userEntity) {
            if (userEntity.passwordRepeat != userEntity.password) {
                FlashService.error($translate.instant('MESSAGE_REG_PASSWORD_MISMATCH'));
                return;
            }
            var user = UserService.signUp(userEntity).success(function(response) {

                $rootScope.userSettings = response.userSettings;
                $rootScope.userSettings.groupsContent = [];
                document.globalSettings.CSRF = response.CSRF;
                $location.path('/');

                FlashService.success($translate.instant('MESSAGE_REG_COMPLETED'));
            });
        };

        //===== Наблюдаетли =====//

        //===== Вспомогательные функции =====//

        //===== Init =====//


        $scope.userEntity = {
            email: "",
            name: "",
            passwordRepeat: "",
            password: ""
        };
    });
});
