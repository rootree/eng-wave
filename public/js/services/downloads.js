define(['./module'], function (services) {
    'use strict';
    return services.factory('DownloadsService', ['$http', '$rootScope', '$sanitize', function($http, $rootScope, $sanitize) {
        return {
            content: function() {
                return $http({
                    method: 'GET',
                    url: '/api/downloads/content'
                }).success(function(response) {
                    console.log(response);
                });
            },
            drop: function(downloadID) {
                return $http({
                    method: 'GET',
                    url: '/api/downloads/drop/' + downloadID
                }).success(function(response) {
                    console.log(response);
                });
            },
            download: function(downloadID) {
                return $http({
                    method: 'GET',
                    url: '/download/' + downloadID
                }).success(function(response) {
                    console.log(response);
                });
            },
            add: function(download) {
                //download = _.invoke(download, 'sanitizeSomething');
                return $http({
                    method: 'POST',
                    url: '/api/downloads/add',
                    data: download
                }).success(function(response) {
                    console.log(response);
                });
            }
        };
    }]);
});