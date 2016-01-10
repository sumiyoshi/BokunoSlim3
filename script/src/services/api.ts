/// <reference path="../../typings/jquery/jquery.d.ts" />
/// <reference path="../../typings/underscore/underscore.d.ts" />
declare var App:any;

module Services {
    export class API {

        /**
         *
         * @param api_config
         * @param params
         * @param cb
         * @param error
         * @returns {JQueryXHR}
         */
        public load(api_config:any, params:Object, cb:Function, error:any) {
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
        }

        /**
         *
         * @param request
         * @param cb
         */
        public promise(request:any, cb:Function) {
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
                    alert('通信エラー');
                });
        }
    }
}
