define(['./module'], function (controllers) {
    'use strict';
    controllers.controller('RegController',  function($scope, $rootScope, $location, AuthenticationService, SessionService, FlashService) {

        if (AuthenticationService.isLoggedIn()) {
            $location.path('/');
            FlashService.show('Авторицация уже была пройдена');
            return;
        }




        //===== Работа с сервисами =====//


        //===== Работа с интерфейсом =====//

        $scope.registration = function(userEntity) {
            if (userEntity.passwordRepeat != userEntity.password) {
                FlashService.error('Проверте пароль или его повторение.');
                return;
            }
            var user = AuthenticationService.signUp(userEntity).success(function(response) {

                $rootScope.userSettings = response.userSettings;
                $rootScope.userSettings.groupsContent = [];
                document.globalSettings.CSRF = response.CSRF;
                $location.path('/');

                FlashService.success('Регистарция произведена.');
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
