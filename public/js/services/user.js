define(['./module'], function (services) {
    'use strict';
    return services.factory('UserService', ['$http', '$rootScope', '$sanitize', 'SessionService', '$translate', function($http, $rootScope, $sanitize, SessionService, $translate) {

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
                            group : $translate.instant('TEXT_INITIAL_GROUP'),
                            strategy : $translate.instant('TEXT_STRATEGY_FOR_ORIGINAL'),
                            strategy2 : $translate.instant('TEXT_STRATEGY_FOR_TRANSLATION')
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