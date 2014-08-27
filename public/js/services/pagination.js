define(['./module'], function (services) {
    'use strict';
    return services.factory('PaginationService', ['$scope', function($scope) {
        return {
            updatePagination : function(length) {
                $scope.pagination.total = this.getElementsOnPage(length);
                $scope.pagination.display = this.getPaginationElements(length);
            },
            getElementsOnPage : function(length) {
                return Math.ceil(length / $scope.pagination.elementsOnPage)
            },
            getPaginationElements : function(length) {
                var _countElementsOnPage = getElementsOnPage(length);
                if (_countElementsOnPage > $scope.pagination.paginationElements) {
                    return $scope.pagination.paginationElements;
                } else {
                    return _countElementsOnPage;
                }
            }
        }
    }]);
});