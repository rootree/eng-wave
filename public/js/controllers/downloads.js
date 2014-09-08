define(['./module'], function (controllers) {
    'use strict';
    controllers.controller('DownloadsController',  function($scope, $rootScope, $location, $translate, DownloadsService, SessionService, FlashService) {

        //===== Работа с сервисами =====//

        $scope.addNewDownload = function (downloadEntity) {
            DownloadsService.add(downloadEntity).success(function(response) {
                FlashService.success($translate.instant('MESSAGE_DOWNLOAD_QUEUE'));
                $rootScope.userSettings.downloads.unshift(response.download);
                $scope.newDownloadEntity = {};
            });
        };
        $scope.dropDownload = function (downloadEntity) {
            console.log(downloadEntity);
            DownloadsService.drop(downloadEntity.id).success(function(response) {
                FlashService.success($translate.instant('MESSAGE_DOWNLOAD_DELETED'));
                _dropDownloadByID($rootScope.userSettings.downloads, downloadEntity.id);
            });
        };
        $scope.getLink = function (downloadEntity) {
            DownloadsService.download(downloadEntity.id).success(function(response) {
                console.log(response);
            });
        };


        //===== Работа с интерфейсом =====//



        //===== Наблюдаетли =====//



        //===== Вспомогательные функции =====//

        var _dropDownloadByID = function(haystack, needleID) {
            angular.forEach(haystack, function(item, index){
                if (item.id == needleID) {
                    haystack.splice(index, 1);
                    return;
                }
            });
        };


        //===== Init =====//


        //DownloadsService.content().success(function(response) {
        //    $rootScope.userSettings.downloads = response.downloads;
        //});

        $scope.newDownloadEntity = {};
        //$rootScope.userSettings.downloads = $rootScope.userSettings.downloads;
        $scope.predicate = 'createdAt';
        $scope.reverse=!$scope.reverse;

    });
});
