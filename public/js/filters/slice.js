define(['./module'], function (filters) {
    'use strict';

    return filters.filter('slice', function() {
        return function(arr, start, end) {
            return (arr || []).slice(start, end);
        };
    });
});