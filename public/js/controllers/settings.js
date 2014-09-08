define(['./module'], function (controllers) {
    'use strict';
    controllers.controller('SettingsController',  function($scope, $rootScope, $location, $sanitize, $sce, $translate,
                                                           UserService,
                                                           FeedbackService,
                                                           FlashService) {

        //===== Работа с сервисами =====//


        //===== Работа с интерфейсом =====//

        $scope.applyChanges = function(credential) {
            if (credential.passwordRepeat != credential.password) {
                FlashService.error($translate.instant('MESSAGE_REG_PASSWORD_MISMATCH'));
                return;
            }
            UserService.updateCredential(credential).success(function(response) {
                $rootScope.userSettings.credential.name = ($scope.credential.name);
                $rootScope.userSettings.credential.email = $sanitize($scope.credential.email);
                FlashService.success($translate.instant('MESSAGE_SETTING_SAVED'));
            });
        };
        //===== Наблюдаетли =====//

        //===== Вспомогательные функции =====//

        //===== Init =====//

        // $scope.credentialForm.$setPristine(true);

        $scope.credential = {};

        $scope.credential.password = '';
        $scope.credential.passwordRepeat = '';

        $scope.credential.name = ($rootScope.userSettings.credential.name);
        $scope.credential.email = ($rootScope.userSettings.credential.email);
    });
});
