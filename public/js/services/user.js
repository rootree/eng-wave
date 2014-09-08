define(['./module'], function (services) {
    'use strict';
    return services.factory('UserService', ['$http', '$rootScope', '$sanitize', 'SessionService', function($http, $rootScope, $sanitize, SessionService) {

        var cacheSession   = function() {
            SessionService.set('authenticated', 1);
        };

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
            },
            signUp: function(credentials) {
                var request = $http({
                    method: "post",
                    url: "/api/user/create-user",
                    data: {
                        name: credentials.name,
                        email: $sanitize(credentials.email),
                        password: $sanitize(credentials.password),
                        initial: {
                            group : 'Начальная группа',
                            strategy : 'Стратегия для заучивания оригинала',
                            strategy2 : 'Стратегия для заучивания перевода оригинала'
                        }
                    }
                });
                request.success(cacheSession);
                return request;
            },
            updateCredential: function(credentials) {
                var request = $http({
                    method: "post",
                    url: "/api/user/update",
                    data: {
                        name: credentials.name,
                        email: $sanitize(credentials.email),
                        password: $sanitize(credentials.password)
                    }
                });
                request.success(cacheSession);
                return request;
            }
        };
    }]);
});