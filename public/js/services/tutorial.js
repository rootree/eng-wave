define(['./module'], function (services) {
    'use strict';
    return services.factory('TutorialService', ['$translate','$rootScope', function($translate, $rootScope) {
        return {
            page: function(text) {
                $.jGrowl($translate.instant(text), { sticky: true, header: $translate.instant('HEADER_TUTORIAL') , beforeClose: function(e,m) {
                    alert('About to close this notification!');
                },theme: 'growl-info'}); //
            }
        };
    }]);
});