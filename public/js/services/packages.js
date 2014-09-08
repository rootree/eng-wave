define(['./module'], function (services) {
    'use strict';
    return services.factory('PackagesService', ['$http', '$rootScope', '$sanitize', function($http, $rootScope, $sanitize) {
        return {
            install: function(packageID) {
                return $http({
                    method: 'GET',
                    url: '/api/packages/install/' + packageID
                }).success(function(response) {
                    console.log(response);
                });
            }
        };
    }]);
});