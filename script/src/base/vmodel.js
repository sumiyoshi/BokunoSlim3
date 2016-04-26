(function (App) {
    App.ViewModels.VModel = function () {
        this.init.apply(this, arguments);
    };

    App.ViewModels.VModel.prototype = ({

        models: {},
        promise: {},

        /**
         * 初期処理
         */
        init: function () {
            var api = new App.Services.API();
            this.promise = api.promise;

            this.setEvents();
            this.setModels();
            this.initViewModel();
        },

        initViewModel: function () {
        },

        setModels: function () {
        },

        setEvents: function () {
        }
    });
})(window.App);