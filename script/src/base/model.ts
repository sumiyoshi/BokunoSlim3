/// <reference path="../../typings/jquery/jquery.d.ts" />
/// <reference path="../../typings/underscore/underscore.d.ts" />
/// <reference path="../services/api.ts" />
declare var App:any;

module Models {
    import API = Services.API;

    export class Model {

        public data:any;
        public api:any;
        public url:String;

        constructor() {
            this.api = new API();
        }

        /**
         * @param params
         * @returns {*}
         */
        public fetch(params) {
            return this.api.load(this.url, params, this.success.bind(this), this.error.bind(this));
        }

        /**
         * @param res
         */
        public success(res) {
            this.addData(this.receive(res));
        }

        /**
         * @param res
         */
        public error(res) {
            if (_.has(res, 'status') && _.has(res, 'statusText')) {
                var status = res.status;
                var statusText = res.statusText;
            }
        }

        /**
         * @param res
         * @returns {*}
         */
        public receive(res) {
            return res.data;
        }

        /**
         * @param data
         */
        public addData(data) {
            var that = this;

            that.data = [];
            _.each(data, function (item) {
                that.data.push(item);
            });
        }

        /**
         * @returns {Array}
         */
        public get() {
            return this.data;
        }
    }
}