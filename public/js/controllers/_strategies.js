define(['./module'], function (controllers) {
    'use strict';
    controllers.controller('StrategiesController',  function($scope, $rootScope, $location, StrategiesService, SessionService, FlashService) {

        //===== Работа с сервисами =====//
/*
        StrategiesService.getAll().success(function(response) {
            _.each(response.originals, function (strategy) {
                strategy.tpl = TMP_ITEM_STRATEGY;
            });
            _.each(response.translate, function (strategy) {
                strategy.tpl = TMP_ITEM_STRATEGY;
            });
            $scope.sourceStrategies = response.originals;
            $scope.targetStrategies = response.translate;
        });*/
        $scope.commitStrategy = function(strategyID, strategyTitle, strategyType) {
            if ($scope.isAdding) {
                _addStrategy(strategyTitle);
            } else {
                _updateStrategy(strategyID, strategyTitle, strategyType);
            }
        };
        $scope.dropStrategy = function(strategy) {
            if ($scope.isAdding || $scope.isEditing) {
                $scope.cancelStrategyCommit();
                return;
            }
            StrategiesService.dropStrategy(strategy.id).
                success(function(response) {
                    if (strategy.type == $scope.STRATEGY_TYPE_SOURCE) {
                        $scope.sourceStrategies =
                            _.reject($scope.sourceStrategies, function(strategy2){ return strategy2.id == strategy.id; });

                    } else {
                        $scope.targetStrategies =
                            _.reject($scope.targetStrategies, function(strategy2){ return strategy2.id == strategy.id; });

                    }
                    FlashService.success('Выбранная стратегия удалена');
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
            $scope.strategyForm.$setPristine(true);
            //sortableEle.refresh();
        };
        $scope.editStrategy = function(strategy) {
            $scope.cancelStrategyCommit();
            $scope.sortableSourceItems = angular.copy(strategy.items);
            $scope.strategyTitle = strategy.title;
            $scope.strategyID = strategy.id;
            $scope.strategyType = strategy.type;
            strategy.tpl = TMP_ADD_STRATEGY;
            $scope.isEditing = 1;
            _.defer(function(){_focusOnAddingForm();});
            $scope.strategyForm.$setPristine(true);
        };
        $scope.cancelStrategyCommit = function() {
            _resetForms();
            _dropFocusOnStrategyForm();
            $scope.isEditing = 0;
            $scope.addingSourceStrategy = 0;
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
        $scope.$watch("sourceStrategies", function(newValue, oldValue) {
            /*if (_.isUndefined(newValue) || !newValue.length) {
                $scope.addingSourceStrategy = 1;
            } else {
                $scope.addingSourceStrategy = 0;
            }*/
        }, true);
        $scope.$watch("targetStrategies", function(newValue, oldValue) {
            /*if (_.isUndefined(newValue) || !newValue.length) {
                $scope.addingTargetStrategy = 1;
            } else {
                $scope.addingTargetStrategy = 0;
            }*/
        }, true);
        $scope.$watch("addingSourceStrategy", function(newValue, oldValue) {
            if (newValue) {
                _addSourceStrategy();
            } else {
                _cancelAddSourceStrategy();
            }
            $scope.isAdding = newValue || $scope.addingTargetStrategy;
        }, true);
        $scope.$watch("addingTargetStrategy", function(newValue, oldValue) {
            if (newValue) {
                _addTargetStrategy();
            } else {
                _cancelAddTargetStrategy();
            }
            $scope.isAdding = newValue || $scope.addingSourceStrategy;
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
        var _addSourceStrategy = function () {
            $scope.sortableSourceItems = [
                $scope.ITEM_SOURCE, $scope.ITEM_SILENCE, $scope.ITEM_TARGET
            ];
            $scope.sourceAddStrategyTpl = TMP_ADD_STRATEGY;
            $scope.strategyTitle = '';
            _applyDrag();
            _.defer(function(){_focusOnAddingForm();});
        };
        var _addTargetStrategy = function () {
            $scope.sortableSourceItems = [
                $scope.ITEM_TARGET, $scope.ITEM_SILENCE, $scope.ITEM_SOURCE
            ];
            $scope.targetAddStrategyTpl = TMP_ADD_STRATEGY;
            $scope.strategyTitle = '';
            _applyDrag();
            _.defer(function(){_focusOnAddingForm();});
        };
        var _cancelAddSourceStrategy = function () {
            $scope.sourceAddStrategyTpl = null;
            if (!$scope.addingTargetStrategy) {
            }
        };
        var _cancelAddTargetStrategy = function () {
            $scope.targetAddStrategyTpl = null;
            if (!$scope.addingSourceStrategy) {
            }
        };
        var _resetForms = function () {
            _.each($scope.strategies, function (strategy) {
                strategy.tpl = TMP_ITEM_STRATEGY;
            });
        };
        var _addStrategy = function (strategyTitle) {
            var strategyType = $scope.addingTargetStrategy == 1
                ? $scope.STRATEGY_TYPE_TARGET
                : $scope.STRATEGY_TYPE_SOURCE;
            StrategiesService.addStrategy(strategyTitle, strategyType, $scope.sortableSourceItems).
                success(function(response) {
                    FlashService.success('Новая стратегия добавлена');
                    $scope.cancelStrategyCommit();
                    response.strategy.tpl = TMP_ITEM_STRATEGY;
                    if (response.strategy.type == $scope.STRATEGY_TYPE_SOURCE) {
                        $scope.sourceStrategies.unshift(response.strategy);
                    } else {
                        $scope.targetStrategies.unshift(response.strategy);
                    }
                });
        };
        var _updateStrategy = function (strategyID, strategyTitle, strategyType) {
            StrategiesService.updateStrategy(strategyID, strategyTitle, $scope.sortableSourceItems).
                success(function(response) {
                    FlashService.success('Стратегия обновлена');
                    $scope.cancelStrategyCommit();
                    var strategy;
                    if (strategyType == $scope.STRATEGY_TYPE_SOURCE) {
                        strategy = _.findWhere($scope.sourceStrategies, {id: strategyID});
                    } else {
                        strategy = _.findWhere($scope.targetStrategies, {id: strategyID});
                    }
                    strategy.title = strategyTitle;
                    strategy.items = $scope.sortableSourceItems;
                });
        };

        //===== Init =====//

        var TMP_ADD_STRATEGY = '/partials/strategies/form.html';
        var TMP_ITEM_STRATEGY = '/partials/strategies/item.html';

        //$scope.STRATEGY_TYPE_SOURCE = 1;
        //$scope.STRATEGY_TYPE_TARGET = 2;

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

        $scope.sourceStrategies = [];
        $scope.targetStrategies = [];


        $scope.targetStrategies = $rootScope.userSettings.strategies;
        _.each($scope.targetStrategies, function (strategy) {
            strategy.tpl = TMP_ITEM_STRATEGY;
        });

        $scope.addingSourceStrategy = 0;
        $scope.addingTargetStrategy = 0;

        $scope.isEditing = 0;

    });
});
