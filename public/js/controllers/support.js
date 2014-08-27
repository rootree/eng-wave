define(['./module'], function (controllers) {
    'use strict';
    controllers.controller('SupportController',  function($scope, $rootScope, $location, AuthenticationService, FeedbackService, FlashService) {

        //===== Работа с сервисами =====//


        //===== Работа с интерфейсом =====//

        $scope.sendFeedback = function(feedbackEntity) {
            FeedbackService.send(feedbackEntity).success(function(response) {
                $rootScope.isFeedBackSend = 1;
                $scope.isFeedBackSend = 1;
                FlashService.success('Ваше сообщение отправлено.');
            });
        };
        //===== Наблюдаетли =====//

        //===== Вспомогательные функции =====//

        //===== Init =====//

        $scope.userName = '';
        $scope.userEmail = '';
        $scope.isFeedBackSend = $rootScope.isFeedBackSend;
        if (AuthenticationService.isLoggedIn()) {
            $scope.userEmail = $rootScope.userSettings.email;
        }
    });
});
