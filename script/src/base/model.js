(function (App) {
    App.Models.Model = function () {
        this.init.apply(this, arguments);
    };

    App.Models.Model.prototype = ({
        data: [],
        errors: [],
        api: null,
        status: null,

        /**
         * 初期処理
         */
        init: function () {
            this.api = new App.Services.API();
        },

        /**
         *
         * @param params
         * @returns {*}
         */
        fetch: function (params) {
            return this.api.load(this.url, params, this.success.bind(this), this.error.bind(this));
        },

        /**
         * @param res
         */
        success: function (res) {
            this.status = res.status;
            this.addData(res);
        },

        /**
         * @param res
         */
        error: function (res) {
            if (_.has(res, 'status') && _.has(res, 'statusText')) {
                var status = res.status;
                var statusText = res.statusText;
            }
        },

        /**
         *
         * @param res
         * @returns {*}
         */
        receive: function (res) {
            return res.data;
        },

        /**
         * @param response
         */
        addData: function (response) {
            var that = this;

            that.data = [];
            that.errors = [];
            if (this.status) {
                _.each(response.data, function (item) {
                    that.data.push(item);
                });
            } else {
                _.each(response.error, function (item) {
                    that.errors.push(item);
                });
            }
        },

        /**
         *
         * @returns {Array}
         */
        getData: function () {
            return this.data;
        },

        /**
         *
         * @returns {Array}
         */
        getError: function () {
            return this.errors
        }
    });
})(window.App);