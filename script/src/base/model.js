(function (App) {
    App.Models.Model = function () {
        this.init.apply(this, arguments);
    };

    App.Models.Model.prototype = ({
        data: [],
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
            this.addData(this.receive(res));
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
         * @param data
         */
        addData: function (data) {
            var that = this;

            that.data = [];
            if (this.status) {
                _.each(data, function (item) {
                    that.data.push(item);
                });
            } else {
                _.each(data.error, function (item) {
                    that.data.push(item);
                });
            }
        },

        /**
         *
         * @returns {Array}
         */
        get: function () {
            return this.data;
        }
    });
})(window.App);