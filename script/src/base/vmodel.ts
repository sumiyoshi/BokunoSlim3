/// <reference path="../../typings/jquery/jquery.d.ts" />
/// <reference path="../../typings/underscore/underscore.d.ts" />
/// <reference path="../services/api.ts" />
declare var App:any;

module VModels {
    import API = Services.API;

    export class VModel {

        public static models:Object;
        public promise:any;

        constructor() {
            var api = new API();
            this.promise = api.promise;

            this.setEvents();
            this.initViewModel();
        }

        public initViewModel() {
        }


        public setEvents() {
        }
    }
}
