define(['./module'], function (services) {
    'use strict';
    return services.factory('UserService', ['$http', '$rootScope', function($http, $rootScope) {
        return {
            currentGroup : function(){
                var currentGroup;
                currentGroup = _.findWhere($rootScope.userSettings.groups, {id: $rootScope.userSettings.settings.currentGroup});
                if (_.isUndefined(currentGroup)) {
                    currentGroup = {
                        title: '-'
                    };
                }
                return currentGroup;
            }
        };
    }]);
});