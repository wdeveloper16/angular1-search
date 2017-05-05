app.service('Results', function ($q, $http) {
    return {

        get: function (term, type) {
            var key = 'searchResults-' + type + '-' + term;
            var data = sessionStorage.getItem(key);

            if (_.isUndefined(data) || _.isNull(data)) {
                return $http.get('/search.php?type=' + type + '&term=' + term).then(function (httpResponse) {
                    window.sessionStorage.setItem(key, JSON.stringify(httpResponse));

                    return httpResponse.data;
                });
            }
            else {
                data = JSON.parse(data);
                return $q.when(data.data);
            }
        },

        getAutocomplete: function (term) {
            return $http.get("/autosuggestion.php?term=" + term).then(function (httpResponse) {
                return httpResponse;
            });
            // var key = 'autocomplete-' + term;
            // var data = sessionStorage.getItem(key);
            // if (_.isUndefined(data) || _.isNull(data)) {
            //     return $http.get("http://localhost:8181/autosuggestion.php?term=" + term).then(function (httpResponse) {
            //         window.sessionStorage.setItem(key, JSON.stringify(httpResponse));
            //         return httpResponse;
            //     });
            // }
            // else {
            //     data = JSON.parse(data);
            //     return $q.when(data);
            // }
        },

        getSocial: function (term) {
            return this.get(term, 'social').then(function (data) {
                //resort results to show fb/twitter on top
                var sites = {
                    'social_facebook': 'facebook.com',
                    'social_twitter': 'twitter.com',
                    'social_lastfm': 'last.fm',
                    'social_pinterest': 'pinterest.com/',
                    'social_google': '.google.com/',
                    'social_instagram': 'instagram.com/',
                    'social_tumblr': 'tumblr.com/',
                    'social_quora': 'quora.com/',
                    'social_delicious': 'delicious.com/',
                    'social_digg': 'digg.com/',
                    'social_flickr': 'flickr.com/',
                    'social_stumble': 'stumbleupon.com/',
                    'social_linkedin': 'linkedin.com/',
                    'social_yelp': 'yelp.com/',
                    'social_vine': 'vine.co/',
                    'social_four': 'foursquare.com/',
                    'social_reddit': 'reddit.com'
                };

                var values = [];
                var first = [];
                var second = [];

                if (!_.isUndefined(data.webPages)) {
                    _.each(sites, function (v2, k2) {
                        _.each(data.webPages.value, function (v, i) {
                            if (!_.isNull(v.displayUrl.match(v2))) {
                                v.class = k2;
                                values.push(v);
                            }

                        });
                    });

                    _.each(values, function (v) {
                        if (!_.find(first, {'class': v.class})) {
                            first.push(v);
                        }
                        else {
                            second.push(v);
                        }
                    });
                }

                _.each(second, function (v) {
                    first.push(v);
                });

                return first;
            });
        },

        post: function (term, type, postData) {
            var key = 'searchResults-' + type + '-' + term;
            var data = sessionStorage.getItem(key);

            if (_.isUndefined(data) || _.isNull(data)) {
                return $http.post('/search.php?type=' + type + '&term=' + term, postData).then(function (httpResponse) {
                    window.sessionStorage.setItem(key, JSON.stringify(httpResponse));
                    return httpResponse.data;
                });
            }
            else {
                data = JSON.parse(data);
                return $q.when(data.data);
            }
        }
    }
});