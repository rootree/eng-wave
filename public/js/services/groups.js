define(['./module'], function (services) {
    'use strict';
    return services.factory('GroupsService', ['$http', '$rootScope', '$sanitize', function($http, $rootScope, $sanitize) {

        var getGroup = function(groupID) {
            return $http.get('/api/groups/content/' + groupID);
        };
        return {
            get: function(groupID) {
                return getGroup(groupID).success(function(response) {
                    $rootScope.userSettings.settings.currentGroup = groupID;
                });
            },
            delete: function(groupForDelete) {
                return $http({
                    method: 'GET',
                    url: '/api/groups/drop/' + groupForDelete
                }).success(function(response) {
                    console.log(response);
                });
            },
            update: function(newTitle, groupID) {
                return $http({
                    method: 'POST',
                    url: '/api/groups/update/' + groupID,
                    data: {
                        title : $sanitize(newTitle)
                    }
                }).success(function(response) {
                    console.log(response);
                });
            },
            add: function(title) {
                return $http({
                    method: 'POST',
                    url: '/api/groups/add',
                    data: {
                        title: $sanitize(title)
                    }
                }).success(function(response) {
                    console.log(response);
                });
            }
        };
    }]);
});