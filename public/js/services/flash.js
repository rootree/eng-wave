define(['./module'], function (services) {
    'use strict';
    return services.factory('FlashService', ['$rootScope', '$timeout',  function ($rootScope, $timeout ) {
        return {
            error : function(message) {
                $.jGrowl(message, {  theme: 'growl-error'}); // sticky: true,
            },
            show : function(message) {
                $.jGrowl(message);
            },
            success : function(message) {
                $.jGrowl(message, { theme: 'growl-success'});
            }
        };
    }]);
});
