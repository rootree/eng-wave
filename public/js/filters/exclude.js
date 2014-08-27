/**
 *  При редактировании карточки слова, если выбрали язык оригинала, то для языка для перевода, язык оригинала не доступен
 */
define(['./module'], function (filters) {
    'use strict';

    return filters.filter('exclude', function() {
        return function(languages, excludeLanguage){

            if (Object.prototype.toString.call( languages ) !== '[object Array]' || typeof excludeLanguage == 'undefined') {
                return languages;
            }

            excludeLanguage = parseInt(excludeLanguage);

            //var arrayToReturn = [];
            for (var i = 0; i < languages.length; i++){
                languages[i].disabled = 0;
                // debugger;
                if (languages[i].id == excludeLanguage) {
                    // arrayToReturn.push(languages[i]);
                    languages[i].disabled = 1;
                }
            }

            return languages;
        };
    });
});