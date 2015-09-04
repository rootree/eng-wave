/**
 * bootstraps angular onto the window.document node
 * NOTE: the ng-app attribute should not be on the index.html when using ng.bootstrap
 */
define([
    'require',
    'angular',
    'app',
    'routes',
    'http',
    'translate'
], function (require, ng) {
    'use strict';

    /*
     * place operations that need to initialize prior to app start here
     * using the `run` function on the top-level module
     */

    require(['domReady!'], function (document) {

        console.log('domReady');

        ng.module("app").run(function(
            $rootScope,
            $location,
            $timeout,
            $translate,
            AuthenticationService,
            FlashService,
            SessionService,
            TutorialService
        ) {

            // $translate,
            // $translate.use('en_US');

            var routesThatRequireAuth = [
                '/settings',
                '/downloads',
                '/strategies',
                '/packages',
                '/'
            ];
            $rootScope.$on('$routeChangeStart', function(event, next, current) {
                if(_(routesThatRequireAuth).contains($location.path()) && !AuthenticationService.isLoggedIn()) {
                    $location.path('/login');
                    FlashService.show($translate.instant('MESSAGE_REQUIRED_AUTH'));
                }
            });
            $rootScope.$on("$routeChangeSuccess", function(event, current, previous){
                //Change page title, based on Route information
                $rootScope.pageTitle = $translate.instant(current.$$route.title);
                $rootScope.currentController = current.$$route.controller;
                TutorialService.page(current.$$route.hasTutorial, current.$$route.controller);
                $('body').removeClass('offcanvas-active');
                $('#navbar-menu').removeClass('in');
                if (current.$$route.hasSideMenu) {
                    $('#submenu').show();
                } else {
                    $('#submenu').hide();
                }
            });
            SessionService.set('authenticated', 0);
            $rootScope.isLoading = false;
            $rootScope.globalSettings = document.globalSettings;
            $rootScope.userSettings = {
                authenticated : 0,
                justInstalled : 0
            };
            if (document.userSettings.authenticated) {
                $rootScope.userSettings = document.userSettings;
                $rootScope.userSettings.justInstalled = 0;
                $rootScope.userSettings.groupsContent = {};
                SessionService.set('authenticated', 1);
            }
            $rootScope.globalLogout = function() {
                if (!AuthenticationService.isLoggedIn()) {
                    $location.path('/login');
                    FlashService.show($translate.instant('MESSAGE_LOGOUT_WAS_BEFORE'));
                } else {
                    AuthenticationService.logout().success(function(response) {
                        FlashService.show($translate.instant('MESSAGE_LOGOUT_COMPLETE'));
                        $location.path('/login');
                    });
                }
            };
            $rootScope.preLoader = 0;
            $('#mainApp').show();

            //===== jGrowl notifications defaults =====//

            $.jGrowl.defaults.closer = false;
            $.jGrowl.defaults.easing = 'easeInOutCirc';
            $.jGrowl.defaults.position = 'bottom-right';

            //===== Disabling main navigation links =====//

            $('.navigation .disabled a, .navbar-nav > .disabled > a').click(function (e){
                e.preventDefault();
            });
            $('#submenu').click(function (e){
                e.preventDefault();
                $('body').toggleClass('offcanvas-active');
            });
        });
        ng.bootstrap(document, ['app']);
    });
});
