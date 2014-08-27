define(['./module'], function (filters) {
    'use strict';

    return filters.filter('offset', function() {
        return function(input, start) {
            // debugger;
            if (input instanceof Array) {
                start = parseInt(start, 10);
                return input.slice(start);
            } else {
                return [];
            }
        };
    });
});