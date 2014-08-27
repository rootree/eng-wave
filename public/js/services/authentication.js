define(['./module'], function (services) {
    'use strict';
    return services.factory('AuthenticationService', ['$rootScope','$http', '$sanitize', 'SessionService', 'FlashService', function($rootScope, $http, $sanitize, SessionService, FlashService) {

        var cacheSession   = function() {
            SessionService.set('authenticated', 1);
        };

        var uncacheSession = function() {
            $rootScope.userSettings.authenticated = 0;
            SessionService.set('authenticated', 0);
        };

        var sanitizeCredentials = function(credentials) {
            return {
                email: $sanitize(credentials.email),
                password: $sanitize(credentials.password)
            };
        };

        return {
            login: function(credentials) {
                var request = $http({
                    method: "post",
                    url: "/api/auth/login",
                    data: sanitizeCredentials(credentials)
                });
                request.success(cacheSession);
                return request;
            },
            signUp: function(credentials) {
                var request = $http({
                    method: "post",
                    url: "/api/auth/sign-up",
                    data: {
                        name: $sanitize(credentials.name),
                        email: $sanitize(credentials.email),
                        password: $sanitize(credentials.password),
                        initial: {
                            group : 'group',
                            strategy : 'strategy'
                        }
                    }
                });
                request.success(cacheSession);
                return request;
            },
            logout: function() {
                var logout = $http.get("/api/auth/logout");
                logout.success(uncacheSession);
                return logout;
            },
            isLoggedIn: function() {
                return parseInt(SessionService.get('authenticated'));
            }
        };
    }]);
});