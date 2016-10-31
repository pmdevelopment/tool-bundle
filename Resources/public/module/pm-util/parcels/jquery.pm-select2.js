(function ($) {

    $.fn.pmSelect2 = function () {

        var pmSelect2Init = function () {
            this.each(function () {
                var _element = $(this);

                /**
                 * Config
                 * @type {{get}}
                 */
                var config = function () {
                    "use strict";

                    return {
                        /**
                         * Get Config
                         * @param url
                         * @returns {{language: string, ajax: {url: *, dataType: string, delay: number, data: Function, processResults: Function, cache: boolean}, minimumInputLength: number, escapeMarkup: Function, templateResult: Function, templateSelection: Function, closeOnSelect: boolean, allowClear: boolean, dropdownParent: (*|HTMLElement)}}
                         */
                        get: function (url) {
                            var limit = 30;

                            return {
                                language: 'de',
                                ajax: {
                                    url: url,
                                    dataType: 'json',
                                    delay: 150,
                                    data: function (params) {
                                        return {
                                            q: params.term, // search term
                                            page: params.page,
                                            limit: limit
                                        };
                                    },
                                    processResults: function (data, params) {
                                        params.page = params.page || 1;

                                        return {
                                            results: data.items,
                                            more: (params.page * limit) < data.total_count
                                        };
                                    },
                                    cache: true
                                },
                                minimumInputLength: 3,
                                escapeMarkup: function (markup) {
                                    return markup;
                                },
                                templateResult: function (item) {
                                    return '<option value="' + item.id + '">' + item.text + '</option>';
                                },
                                templateSelection: function (item) {
                                    return item.text;
                                },
                                closeOnSelect: true,
                                allowClear: true,
                                dropdownParent: $(".bootbox-body")
                            };
                        }
                    }
                }();

                var core = function () {
                    "use strict";

                    return {
                        /**
                         * Debug
                         * @param message
                         */
                        debug: function (message) {
                            pmUtil.debug('{pmSelect2} ' + message);
                        },
                        /**
                         * Init
                         */
                        init: function () {
                            core.debug('core.init()');

                            var url = $(_element).data('path');

                            $(_element).select2(config.get(url));
                        }
                    };
                }();

                core.init();
            });
        };

        if (undefined === jQuery().select2) {
            $.get('/bundles/pmtool/vendor/select2/' + pmUtil.config.module.select2.version + '/select2.min.js', function () {
                pmSelect2Init();
            });
        } else {
            pmSelect2Init();
        }

        return this;
    };

}(jQuery));