define(['./module'], function (services) {
    'use strict';
    return services.factory('FeedbackService', ['$http', '$rootScope', '$sanitize', function($http) {
        return {
            send: function(feedback) {
                return $http({
                    method: 'POST',
                    url: '/api/feedback/add',
                    data: feedback
                }).success(function(response) {
                    console.log(response);
                });
            }
        };
    }]);
});