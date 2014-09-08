define(['./module'], function (controllers) {
    'use strict';
    controllers.controller('StrategiesController',  function($scope, $rootScope, $location, $translate, StrategiesService, SessionService, FlashService) {

        //===== Работа с сервисами =====//

        $scope.commitStrategy = function(strategyID, strategyTitle, strategyType) {
            if ($scope.isAddingStrategy) {
                _addStrategyOnServer(strategyTitle);
            } else {
                _updateStrategyOnServer(strategyID, strategyTitle);
            }
        };
        $scope.dropStrategy = function(strategy) {
            if ($scope.isAddingStrategy || $scope.isEditingStrategy) {
                $scope.cancelStrategyCommit();
                return;
            }
            StrategiesService.dropStrategy(strategy.id).
                success(function(response) {
                    $rootScope.userSettings.strategies =
                        _.reject($rootScope.userSettings.strategies, function(strategy2){ return strategy2.id == strategy.id; });
                    FlashService.success($translate.instant('MESSAGE_STRATEGY_DELETED'));
            });
        };

        //===== Работа с интерфейсом =====//

        $scope.applyDrag = function() {
            _applyDrag();
        };
        $scope.isEmptyR = function(object) {
            return _.isEmpty(object);
        };
        $scope.add = function(item) {
            var itemN = angular.copy(item);
            itemN.sort = $scope.sortableSourceItems.length;
            $scope.sortableSourceItems.push(itemN);
            // $scope.strategyForm.$setPristine(true);
            //sortableEle.refresh();
        };
        $scope.editStrategy = function(strategy) {
            $scope.cancelStrategyCommit();
            $scope.sortableSourceItems = angular.copy(strategy.items);
            $scope.strategyTitle = strategy.title;
            $scope.strategyID = strategy.id;
            strategy.tpl = TMP_ADD_STRATEGY;
            $scope.isEditingStrategy = 1;
            _.defer(function(){_focusOnAddingForm();});
        };
        $scope.cancelStrategyCommit = function() {
            _resetForms();
            _dropFocusOnStrategyForm();
            $scope.isEditingStrategy = 0;
            $scope.isAddingStrategy = 0;
        };
        $scope.delete = function(index) {
            $scope.sortableSourceItems.splice(index,1);
        };
        $scope.dragStart = function(e, ui) {
            ui.item.data('start', ui.item.index());
        };
        $scope.dragEnd = function(e, ui) {
            var start = ui.item.data('start'),
                end = ui.item.index();
            var item = $scope.sortableSourceItems.splice(start, 1)[0];
            $scope.sortableSourceItems.splice(end, 0, item);
            $scope.$apply();
        };

        //===== Наблюдаетли =====//

        $scope.$watch("sortableSourceItems", function(newValue, oldValue) {
            var iterator = 0;
            _.each(newValue, function(item){ item.sort = iterator; iterator++; });
            $scope.sortableSourceItems = newValue;
        }, true);
        $scope.$watch("isAddingStrategy", function(newValue, oldValue) {
            if (newValue) {
                _addStrategy();
            } else {
                _cancelAddStrategy();
            }
        }, true);
        $scope.$watch("userSettings.strategies", function(newValue, oldValue) {
            if (!newValue.length) {
                $scope.isAddingStrategy = 1;
            }
        }, true);

        //===== Вспомогательные функции =====//

        var _applyDrag = function() {
            $('#sortable').sortable({
                start: $scope.dragStart,
                update: $scope.dragEnd
            });
        };
        var _focusOnAddingForm = function() {
            $('.strategy-entity').addClass('strategy-entity-back');
        };
        var _dropFocusOnStrategyForm = function() {
            $('.strategy-entity').removeClass('strategy-entity-back');
        };
        var _addStrategy = function () {
            $scope.sortableSourceItems = [
                $scope.ITEM_SOURCE, $scope.ITEM_SILENCE, $scope.ITEM_TARGET
            ];
            $scope.addStrategyTpl = TMP_ADD_STRATEGY;
            $scope.isEditingStrategy = 0;
            $scope.strategyTitle = '';
            _applyDrag();
            _.defer(function(){_focusOnAddingForm();});
        };
        var _cancelAddStrategy = function () {
            $scope.addStrategyTpl = null;
        };
        var _resetForms = function () {
            _.each($rootScope.userSettings.strategies, function (strategy) {
                strategy.tpl = TMP_ITEM_STRATEGY;
            });
        };
        var _addStrategyOnServer = function (strategyTitle) {
            StrategiesService.addStrategy(strategyTitle, $scope.sortableSourceItems).
                success(function(response) {
                    FlashService.success('Новая стратегия добавлена');
                    $scope.cancelStrategyCommit();
                    response.strategy.tpl = TMP_ITEM_STRATEGY;
                    $rootScope.userSettings.strategies.unshift(response.strategy);
                });
        };
        var _updateStrategyOnServer = function (strategyID, strategyTitle) {
            StrategiesService.updateStrategy(strategyID, strategyTitle, $scope.sortableSourceItems).
                success(function(response) {
                    FlashService.success($translate.instant('MESSAGE_STRATEGY_SAVED'));
                    $scope.cancelStrategyCommit();
                    var strategy = _.findWhere($rootScope.userSettings.strategies, {id: strategyID});
                    strategy.title = strategyTitle;
                    strategy.items = $scope.sortableSourceItems;
                });
        };

        //===== Init =====//

        var TMP_ADD_STRATEGY = '/partials/strategies/form.html';
        var TMP_ITEM_STRATEGY = '/partials/strategies/item.html';

        $scope.ITEM_TYPE_SOURCE = 1;
        $scope.ITEM_TYPE_SILENT = 2;
        $scope.ITEM_TYPE_TARGET = 3;

        //===== Примеры элементов стратегии =====//

        $scope.ITEM_SOURCE = {
            type: $scope.ITEM_TYPE_SOURCE,
            settings : {}
        };
        $scope.ITEM_SILENCE = {
            type: $scope.ITEM_TYPE_SILENT,
            settings : {
                type : 1, // Тишина на время оригинала
                seconds : 0
            }
        };
        $scope.ITEM_TARGET = {
            type: $scope.ITEM_TYPE_TARGET,
            settings : {}
        };

        // $scope.strategies = $rootScope.userSettings.strategies;
        _.each($rootScope.userSettings.strategies, function (strategy) {
            strategy.tpl = TMP_ITEM_STRATEGY;
        });

        $scope.isAddingStrategy = 0;
        $scope.isEditingStrategy = 0;

    });
});
