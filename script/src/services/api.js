(function (App) {

    App.Services.API = function () {
        this.init.apply(this, arguments);
    };

    App.Services.API.prototype = ({

        init: function () {

        },

        load: function (api_config, params, cb, error) {

            if (api_config !== undefined) {
                var url = App.domain + api_config.url;
                var method = api_config.method;

                return $.ajax({
                    type: method,
                    data: params,
                    url: url,
                    success: function (res) {
                        if (cb !== undefined) {
                            cb(res);
                        }
                    }.bind(this),
                    error: error
                });
            }
        },

        promise: function (request, cb) {
            var jqXHRList = [];

            _.each(request, function (item) {
                jqXHRList.push(item);
            });

            $.when.apply($, jqXHRList)
                .done(function () {
                    var json = [];

                    if (request.length == 1) {
                        json.push(arguments[0]);
                    } else {
                        _.each(arguments, function (item) {
                            json.push(item);
                        });
                    }

                    cb(json);
                })
                .fail(function () {
                    console.log('error');
                });
        }
    });
})(window.App);

