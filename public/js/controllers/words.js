define(['./module'], function (controllers) {
    'use strict';
    controllers.controller('WordsController', function($scope, $rootScope, $filter, $location, $timeout, $routeParams,
                                                       UserService,
                                                       WordsService,
                                                       GroupsService,
                                                       SessionService,
                                                       FlashService,
                                                       AuthenticationService,
                                                       SoundService
        ) {

        if (!AuthenticationService.isLoggedIn()) {
            $location.path('/login');
            FlashService.show('Пройдите авторизацию');
            return;
        }


        //===== Работа с сервисами =====//

        $scope.getGroup = function(idGroup, force){
            if ($rootScope.userSettings.settings.currentGroup == idGroup && force !== true) {
                return;
            }
            $rootScope.userSettings.settings.currentGroup = idGroup;
            if (!_.isUndefined($rootScope.userSettings.groupsContent[idGroup])) {
                _switchGroup($rootScope.userSettings.groupsContent[idGroup]);
                return;
            }
            var group = _getGroupByID(idGroup);
            if (!_.isUndefined(group) && group.count == 0) {
                $rootScope.userSettings.groupsContent[idGroup] = [];
                _switchGroup($rootScope.userSettings.groupsContent[idGroup]);
                return;
            }
            return GroupsService.get(idGroup).success(function(response) {
                $rootScope.userSettings.groupsContent[idGroup] = response.words;
                _switchGroup($rootScope.userSettings.groupsContent[idGroup]);
            });
        };
        $scope.deleteWord = function(wordID, index){
            var wordsForDelete = [wordID];
            WordsService.deleteWords(wordsForDelete).success(function(response) {
                _dropItemByID($scope.words, wordID);
                _decreaseGroupCount($rootScope.userSettings.settings.currentGroup, 1);
                $scope.pagination.cur = 1;//Устанавиливать есть на текущей странице нет элементов
                FlashService.success('Слово удалено');
            });
        };
        $scope.deleteGroup = function(groupID){
            GroupsService.delete(groupID).success(function(response) {
                $rootScope.userSettings.groups =
                    _.reject($rootScope.userSettings.groups, function(group){ return group.id == groupID; });
                if (response.currentGroup != $rootScope.userSettings.settings.currentGroup) {
                    $scope.getGroup(response.currentGroup);
                }
                FlashService.success('Группа успешно удалена');
            }).error(function(response){
                    if (response.existWords) {

                    }
                });
        };
        $scope.deleteGroupWords = function(){
            var selectedItems = _getSelectedIDs();
            if (selectedItems.length) {
                WordsService.deleteWords(selectedItems).success(function(response) {
                    _dropSelectedItems(selectedItems);
                    _decreaseGroupCount(UserService.currentGroup().id, selectedItems.length);
                    $scope.pagination.cur = 1;//Устанавиливать есть на текущей странице нет элементов
                    FlashService.success('Выделенные словавы были удалены');
                });
            }
        };
        $scope.submitChangingGroup = function(){
            var selectedItems = _getSelectedIDs();
            if (selectedItems.length) {
                var moveToGroup = parseInt($scope.moveToGroup);
                //debugger;
                if (!isNaN(moveToGroup) && moveToGroup != UserService.currentGroup().id) {
                    WordsService.moveToGroup(selectedItems, moveToGroup).success(function(response) {
                        _dropSelectedItems(selectedItems);
                        _decreaseGroupCount(UserService.currentGroup().id, selectedItems.length);
                        _increaseGroupCount(moveToGroup, selectedItems.length);
                        $scope.pagination.cur = 1;
                        FlashService.success('Выбранные слова успешно перемещены в новую группу');
                    });
                }
            }
        };
        $scope.editGroup = function(){
            if ($scope.currentGroupTitle != UserService.currentGroup().title){
                GroupsService.update($scope.currentGroupTitle, UserService.currentGroup().id).
                    success(function(response) {
                        UserService.currentGroup().title = $scope.currentGroupTitle;
                        $scope.editingGroup = 0;
                        FlashService.success('Группа успешно отредактирована');
                    }).
                    error(function(response) {
                        $scope.currentGroupTitle = UserService.currentGroup().title;
                        $scope.editingGroup = 0;
                    });
            }
        };
        $scope.addNewGroup = function(){
            GroupsService.add($scope.NewGroupForm.title).success(function(response) {
                $rootScope.userSettings.groups.push(response.group);
                $scope.pagination.cur = 1;
                $scope.isAddingNewGroup = 0;
                $scope.NewGroupForm.title = '';
                $scope.getGroup(response.group.id).success(function(){
                    FlashService.success('Группа успешно добавлена');
                });
            });
        };
        $scope.addNewWord = function(newWord){
            if (typeof newWord.fkWordsGroup == 'undefined') {
                console.log('Allert');
                newWord.fkWordsGroup = $rootScope.userSettings.settings.currentGroup;
            }
            WordsService.add(newWord).success(function(response) {
                newWord.source = '';
                newWord.target = '';
                if (newWord.fkWordsGroup == UserService.currentGroup().id) {
                    $scope.words.push(response.word);
                }
                _increaseGroupCount(UserService.currentGroup().id, 1);
                FlashService.success('Новое слово успешно добавлено');
            });
        };
        $scope.updateWord = function(wordEntity){
            WordsService.update(wordEntity).success(function(response) {
                FlashService.success('Слово успешно отредактированно');
                _dropItemByID($scope.words, wordEntity.id);
                if (wordEntity.fkWordsGroup == UserService.currentGroup().id) {
                    $scope.words.push(response.word);
                } else {
                    _decreaseGroupCount(UserService.currentGroup().id, 1);
                    _increaseGroupCount(parseInt(wordEntity.fkWordsGroup), 1);
                }
                $scope.isEditingWord = 0;
                if (wordEntity.fkWordsGroup != $rootScope.userSettings.settings.currentGroup) {
                    $scope.pagination.cur = 1;//Устанавиливать есть на текущей странице нет элементов
                }
            });
        };

        //===== Работа с интерфейсом =====//

        $scope.showFromForNewWord = function(){
            $scope.isAddingNewWord = !$scope.isAddingNewWord;
            $scope.editingGroup = 0;
            $scope.isEditingWord = 1;
            $scope.isAddingNewGroup = 0;
        };
        $scope.onSearch = function(){
            $scope.checkedAllWords = false;
            angular.forEach($scope.words, function(word){
                word.checked = false;
            });
            _updatePagination();
            $scope.pagination.cur = 1;
        };
        $scope.cleanSearch = function(){
            $scope.checkedAllWords = false;
            angular.forEach($scope.words, function(word){
                word.checked = false;
            });
            $scope.q = '';
            $scope.pagination.cur = 1;
            _updatePagination();
        };
        $scope.actionOnSelectedItems = function(){
            if ($scope.actionTypeOnSelectedItems == 'Delete') {
                $scope.deleteGroupWords();
            }
        };
        $scope.editWord = function(word, index){

            $scope.isEditingWord = 1;
            $scope.isAddingNewWord = 0;
            $scope.isAddingNewGroup = 0;

            angular.forEach($filter('filter')($scope.words, $scope.q), function(word){
                word.checked = false;
            });

            $scope.editWordForm.$setPristine(true);
            $scope.editWordEntity = {};
            $scope.editWordEntity = _.clone(word);
            $scope.editWordEntity.index = index;

        };
        $scope.play = function(elementID, wordEntity, sourceType){
            console.log(wordEntity);
            console.log(sourceType);
            var URLField = sourceType == 1 ? 'sourceURL' : 'targetURL';
            if (!_.isEmpty(wordEntity[URLField])) {
                SoundService.play($('#' + elementID), wordEntity[URLField]);
                return;
            }

            WordsService.getSpeakURL(wordEntity.id, sourceType).success(function(response) {
                wordEntity[URLField] = response.speakURL;
                SoundService.play($('#' + elementID), response.speakURL);
            });
        };
        $scope.cancelGroupChanging = function(){
            $scope.editingGroup = 0;
            $scope.currentGroupTitle = UserService.currentGroup().title;
        };

        //===== Наблюдаетли =====//

        $scope.$watch("words", function(newValue, oldValue) {
            _updatePagination();
            var selectedItems = 0;
            angular.forEach(newValue, function(item){
                selectedItems += item.checked ? true : false;
            });
            $scope.selectedItems = selectedItems;
            if (newValue.length == 0) {
                $scope.editingGroup = 0;
            }
        }, true);
        $scope.$watch("isAddingNewWord", function(newValue, oldValue) {
            if (newValue) {
                var firstLang = _.first($rootScope.globalSettings.languages);
                var lastLang = _.last($rootScope.globalSettings.languages);
                $scope.newWordEntity.fkLanguageSource = firstLang.id;
                $scope.newWordEntity.fkLanguageTarget = lastLang.id;
            }
        }, true);
        $scope.$watch("checkedAllWords", function(newValue, oldValue) {
            angular.forEach($filter('filter')($scope.words, $scope.q), function(word){
                word.checked = newValue ? true : false;
            });
        }, true);

        //===== Вспомогательные функции =====//

        var _updatePagination = function() {
            var _filteredLength = $filter('filter')($scope.words, $scope.q).length;
            $scope.pagination.total = _getElementsOnPage(_filteredLength);
            $scope.pagination.display = _getPaginationElements(_filteredLength);
        };
        var _getElementsOnPage = function(length) {
            return Math.ceil(length / $scope.pagination.elementsOnPage)
        };
        var _getPaginationElements = function(length) {
            var _countElementsOnPage = _getElementsOnPage(length);
            if (_countElementsOnPage > $scope.pagination.paginationElements) {
                return $scope.pagination.paginationElements;
            } else {
                return _countElementsOnPage;
            }
        };
        var _getSelectedIDs = function(){
            var selectedItems =  _.where($scope.words, {checked: true});
            var selectedItemIDs = _.pluck(selectedItems, 'id');
            return  selectedItemIDs;
        };
        var _increaseGroupCount = function(idGroup, count){
            var newGroup = _getGroupByID(idGroup);
            if (!_.isUndefined(newGroup)) {
                newGroup.count = newGroup.count + count;
            }
        };
        var _decreaseGroupCount = function(idGroup, count){
            var newGroup = _getGroupByID(idGroup);
            if (!_.isUndefined(newGroup)) {
                newGroup.count = newGroup.count - count;
            }
        };
        var _getGroupByID = function(idGroup) {
            return _.findWhere($rootScope.userSettings.groups, {id: idGroup});
        };
        var _dropSelectedItems = function(selectedItems) {
            angular.forEach(selectedItems, function(selectedItemID){
                _dropItemByID($scope.words, selectedItemID);
            });
        };
        var _dropItemByID = function(haystack, needleID) {
            //_.reject(haystack, function(item){ return item.id == needleID; });
            //haystack = _.reject(haystack, function(item){ return item.id == needleID; });
            //debugger;
            //;
            angular.forEach(haystack, function(item, index){
                if (item.id == needleID) {
                    haystack.splice(index, 1);
                    return;
                }
            });
        };
        var _switchGroup = function(words) {

            $scope.words = words;
            $scope.currentGroupTitle = UserService.currentGroup().title;

            $scope.selectedItems = 0;
            $scope.checkedAllWords = false;

            $scope.isAddingNewWord = !words.length;
            $scope.editingGroup = 0;
            $scope.isEditingWord = 0;
            $scope.isAddingNewGroup = 0;

            $scope.pagination.cur = 1;
        };

        //===== Init =====//

        $scope.words = [];
        $scope.selectedItems = 0;
        $scope.newWordEntity = {};
        $scope.checkedAllWords = false;
        $scope.currentGroupTitle = UserService.currentGroup().title;

        $scope.pagination = {
            cur: 1,
            total: 1,
            display: 0,
            elementsOnPage : 10,
            paginationElements : 5
        };

        var groupID = parseInt($routeParams.groupID);
        if (groupID) {
            $scope.getGroup(groupID, true);
        } else {
            $scope.getGroup($rootScope.userSettings.settings.currentGroup, true);
        }

    } )
});