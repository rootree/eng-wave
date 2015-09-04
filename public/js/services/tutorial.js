define(['./module'], function (services) {
    'use strict';
    return services.factory('TutorialService', ['$translate','$rootScope', function($translate, $rootScope) {
        var alreadyShown = [];
        return {
            page: function(hasTutorial, controller) {
                if (hasTutorial && ($rootScope.userSettings.justInstalled || $rootScope.isDemo)
                    && _.indexOf(alreadyShown, controller) == -1
                ) {
                    $rootScope.tutorialTemplate = '/partials/tutorial/' + controller + '.html';
                    $('#tutorialModal').modal();
                    alreadyShown.push(controller);
                }
            }
        };
    }]);
});