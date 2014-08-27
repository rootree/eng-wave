define(['./module'], function (services) {
    'use strict';
    return services.factory('StrategiesService', ['$http', '$rootScope', '$sanitize', function($http, $rootScope, $sanitize) {
        return {
            addStrategy: function(title, items) {
                return $http({
                    method: 'POST',
                    url: '/api/strategies/add',
                    data: {
                        title : title,
                        items : items
                    }
                }).success(function(response) {
                    console.log(response);
                });
            },
            updateStrategy: function(id, title, items) {
                return $http({
                    method: 'POST',
                    url: '/api/strategies/update',
                    data: {
                        id : id,
                        title : title,
                        items : items
                    }
                }).success(function(response) {
                    console.log(response);
                });
            },
            getAll: function() {
                return $http({
                    method: 'GET',
                    url: '/api/strategies/get-all'
                }).success(function(response) {
                    console.log(response);
                });
            },
            dropStrategy: function(strategyID) {
                return $http({
                    method: 'GET',
                    url: '/api/strategies/drop/' + strategyID
                }).success(function(response) {
                    console.log(response);
                });
            },
            add: function(word) {
                //word = _.invoke(word, 'sanitizeSomething');
                return $http({
                    method: 'POST',
                    url: '/api/words/add',
                    data: word
                }).success(function(response) {
                    console.log(response);
                });
            },
            update: function(word) {
                return $http({
                    method: 'POST',
                    url: '/api/words/update',
                    data: word
                }).success(function(response) {
                    console.log(response);
                });
            }
        };
    }]);
});