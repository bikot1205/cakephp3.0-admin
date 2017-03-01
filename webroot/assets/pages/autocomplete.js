/*jslint  browser: true, white: true, plusplus: true */
        /*global $, countries */

        $(function () {
            'use strict';
            var countriesArray = $.map(countries, function (value, key) {
                return {value: value, data: key};
            });

            // Setup jQuery ajax mock:
            // Initialize ajax autocomplete:
            $('#moderator').autocomplete({
                serviceUrl: SiteAdminUrl + 'users/search',
                // lookup: countriesArray,
                lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
                    var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
                    return re.test(suggestion.value);
                },
                onSelect: function (suggestion) {
                    $('#moderator_id').val(suggestion.id);
                },
                
            });


        });