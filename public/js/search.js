jQuery(document).ready(function($) {

     // Set the Options for "Bloodhound" suggestion engine
    /*var engine = new Bloodhound({
        remote: {
            url: '/find?q=%QUERY%',
            wildcard: '%QUERY%'
        },
        datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
        queryTokenizer: Bloodhound.tokenizers.whitespace
    });

    $(".search-input").typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    }, {
        source: engine.ttAdapter(),

        // This will be appended to "tt-dataset-" to form the class name of the suggestion menu.
        name: 'usersList',

        // the key from the array we want to display (name,id,email,etc...)
        templates: {
            empty: [
                '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
            ],
            header: [
                '<div class="list-group search-results-dropdown">'
            ],
            suggestion: function (data) {                
                return '<a href="/@' + data.username + '" class="list-group-item">' + data.name + '- @' + data.username + '</a>'
            }
        }
    });*/




    var suggested_users = new Bloodhound({
        remote: {
            url: '/find-user?q=%QUERY%',
            wildcard: '%QUERY%'
        },
        datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
        queryTokenizer: Bloodhound.tokenizers.whitespace
    });

    var suggested_groups = new Bloodhound({
        remote: {
            url: '/find-group?q=%QUERY%',
            wildcard: '%QUERY%'
        },
        datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
        queryTokenizer: Bloodhound.tokenizers.whitespace
    });

    $(".search-input").typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    },
    {

      name: 'suggested-user',
      display: 'suggest',
      source: suggested_users.ttAdapter(),
      templates: {
        empty: [
            '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
        ],
        header:'<h3 class="suggest-content-name">Users</h3>',
        suggestion: function (data) {
            return '<a href="/search?q=' + data.username + '" class="list-group-item">' + data.name + '- @' + data.username + '</a>'
        }
      }
    },
    {
      name: 'suggested-group',
      display: 'suggest',
      source: suggested_groups,
      templates: {
        empty: [
            '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
        ],
        header: '<h3 class="suggest-content-name">Groups</h3>',
        suggestion: function (data) {
            return '<a href="/search?q=' + data.name + '" class="list-group-item">' + data.name + '</a>'
        }
      }
    }
    );




});

