define(['./module'], function (controllers) {
    'use strict';
    controllers.controller('PackagesController',  function($scope, $rootScope, $location, $sanitize, $sce, $translate,
                                                           UserService,
                                                           PackagesService,
                                                           FlashService) {

        //===== Работа с сервисами =====//
        $scope.installPackage = function(packageEntity){
            PackagesService.install(packageEntity.id).success(function(response) {
                console.log(response);
                FlashService.success($translate.instant('MESSAGE_PACKAGE_INSTALLED'));
                $rootScope.userSettings.groups.push(response.group);
                $rootScope.userSettings.installedPackages.push(packageEntity.id);
                packageEntity.isInstalled = 1;
            });
        };

        //===== Работа с интерфейсом =====//
        $scope.isInstalled = function(packageEntity){
            var installedPackage = _getInstalledPackageID(packageEntity.id);
            return !_.isUndefined(installedPackage);
        };

        //===== Наблюдаетли =====//

        //===== Вспомогательные функции =====//
        var _getInstalledPackageID = function(idPackage) {
            return _.findWhere($rootScope.userSettings.installedPackages, {id: idPackage});
        };

        //===== Init =====//
        _.each($rootScope.userSettings.packages, function(wordsPackage, key){
            wordsPackage.isInstalled = $scope.isInstalled(wordsPackage);
        });

    });
});
