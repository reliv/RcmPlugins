angular.module('rcmApiService', [])
    .factory(
    'rcmApiService',
    [
        '$http',
        function ($http) {

            var self = this;

            /**
             * cache
             * @type {{}}
             */
            self.cache = {};

            /**
             * ApiCallbacks
             * @constructor
             */
            var ApiCallbacks = function () {
                this.loading = function (loading) {
                };
                this.success = function (data) {
                };
                this.error = function (data) {
                };
            };

            /**
             * ApiData
             * @constructor
             */
            var ApiData = function () {
                this.code = null;
                this.message = null;
                this.data = [];
                this.errors = [];
            };

            /**
             * apiError
             * @param data
             * @param ApiCallbacks
             */
            self.apiError = function (data, apiCallbacks) {

                apiCallbacks.error(
                    self.prepareErrorData(data)
                );
                apiCallbacks.loading(false);
            };

            /**
             * apiSuccess
             * @param data
             * @param ApiCallbacks
             */
            self.apiSuccess = function (data, apiCallbacks, cacheId) {

                if (data.code > 0) {
                    apiCallbacks.error(
                        self.prepareErrorData(data)
                    );
                } else {
                    data = self.prepareData(data);
                    self.cache[cacheId] = angular.copy(data);
                    apiCallbacks.success(
                        data
                    );
                }
                apiCallbacks.loading(false);
            };

            /**
             * get Api
             * @param url
             * @param apiCallbacks
             */
            self.get = function (url, apiCallbacks, cache) {

                if (!apiCallbacks) {
                    apiCallbacks = new ApiCallbacks();
                }

                var cacheId = null;
                if (cache) {
                    cacheId = url;
                }

                if (self.cache[url]) {
                    return self.cache[cacheId];
                }

                apiCallbacks.loading(true);

                $http(
                    {
                        method: 'GET',
                        url: url
                    }
                )
                    .success(
                    function (data) {
                        self.apiSuccess(data, apiCallbacks, cacheId)
                    }
                )
                    .error(
                    function (data) {
                        self.apiError(data, apiCallbacks)
                    }
                );
            };

            /**
             * post
             * @param url
             * @param requestData
             * @param apiCallbacks
             */
            self.post = function (url, requestData, apiCallbacks) {

                if (!apiCallbacks) {
                    apiCallbacks = new ApiCallbacks();
                }

                apiCallbacks.loading(true);

                $http(
                    {
                        method: 'POST',
                        url: url,
                        data: requestData
                    }
                )
                    .success(
                    function (data) {
                        self.apiSuccess(data, apiCallbacks)
                    }
                )
                    .error(
                    function (data) {
                        self.apiError(data, apiCallbacks)
                    }
                );
            };

            /**
             * post
             * @param url
             * @param requestData
             * @param apiCallbacks
             */
            self.put = function (url, requestData, apiCallbacks) {

                if (!apiCallbacks) {
                    apiCallbacks = new ApiCallbacks();
                }

                apiCallbacks.loading(true);

                $http(
                    {
                        method: 'PUT',
                        url: url,
                        data: requestData
                    }
                )
                    .success(
                    function (data) {
                        self.apiSuccess(data, apiCallbacks)
                    }
                )
                    .error(
                    function (data) {
                        self.apiError(data, apiCallbacks)
                    }
                );
            };

            /**
             * prepareErrorData
             * @param data
             * @returns {*}
             */
            self.prepareErrorData = function (data) {

                if (!data) {
                    data = new ApiData();
                }
                if (!data.code) {
                    data.code = 1;
                }
                if (!data.message) {
                    data.message = 'An unknown error occured while making request.';
                }

                return self.prepareData(data);
            };

            /**
             * prepareData
             * @param data
             * @returns {*}
             */
            self.prepareData = function (data) {

                if (data.errors) {
                    angular.forEach(
                        data.errors,
                        function (value, key) {
                            if (typeof value === 'object' && value !== null) {
                                angular.forEach(
                                    value,
                                    function (evalue, ekey) {
                                        data.errors[key] = evalue + ' ';
                                    }
                                );
                            }
                        }
                    );
                }

                return data;
            };
        }
    ]
);

angular.module('rcmAdminApiService', ['rcmApiService'])
    .factory(
    'rcmAdminApiService',
    [
        'rcmApiService',
        function (rcmApiService) {

            var self = this;

            self.url = {
                sourceSite: '/api/admin/manage-sites/current',
                sites: '/api/admin/manage-sites',
                sitePages: '/api/admin/sites', //ID/pages'
                pageTypes: '/api/admin/pagetypes',
                copyPage: '/api/admin/sites'
            };


            self.getSites = function (apiCallbacks, cache) {

                rcmApiService.get(self.url.sites, apiCallbacks, cache);
            };
            /////

        }
    ]
);