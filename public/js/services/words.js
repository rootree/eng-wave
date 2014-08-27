define(['./module'], function (services) {
    'use strict';
    return services.factory('WordsService', ['$http', '$rootScope', '$sanitize', function($http, $rootScope, $sanitize) {
        return {
            deleteWords: function(wordsForDelete) {
                return $http({
                    method: 'POST',
                    url: '/api/words/drop-several-words',
                    data: {
                        wordsForDelete : wordsForDelete
                    }
                }).success(function(response) {
                    console.log(response);
                });
            },
            moveToGroup: function(wordsForMove, moveToGroup) {
                return $http({
                    method: 'POST',
                    url: '/api/words/move-several-words',
                    data: {
                        wordsForMove : wordsForMove,
                        moveToGroup : moveToGroup
                    }
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
            },
            getSpeakURL: function(wordID, type) {
                return $http({
                    method: 'GET',
                    url: '/api/words/speak/' + wordID+ '?type=' + type
                }).success(function(response) {
                    console.log(response);
                });
            }
        };
    }]);
});