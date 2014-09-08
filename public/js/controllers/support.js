define(['./module'], function (controllers) {
    'use strict';
    controllers.controller('SupportController',  function($scope, $rootScope, $location, $translate, AuthenticationService, FeedbackService, FlashService) {

        //===== Работа с сервисами =====//


        //===== Работа с интерфейсом =====//

        $scope.sendFeedback = function(feedbackEntity) {
            FeedbackService.send(feedbackEntity).success(function(response) {
                $rootScope.isFeedBackSend = 1;
                $scope.isFeedBackSend = 1;
                FlashService.success($translate.instant('MESSAGE_SUPPORT_SENT'));
            });
        };
        //===== Наблюдаетли =====//

        //===== Вспомогательные функции =====//

        //===== Init =====//

        $scope.userName = '';
        $scope.userEmail = '';
        $scope.isFeedBackSend = $rootScope.isFeedBackSend;
        if (AuthenticationService.isLoggedIn()) {
            $scope.userEmail = $rootScope.userSettings.credential.email;
            $scope.userName = $rootScope.userSettings.credential.name;
        }

        $.jGrowl('test', {  theme: 'growl-info'}); // sticky: true,


    });
});
