(function ($) {

    $.fn.pmSelect2 = function () {

        var _target = this;

        var pmSelect2Init = function () {
            _target.each(function () {
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
                        getAjax: function (url) {
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

                            if (url) {
                                $(_element).select2(config.getAjax(url));
                            } else {
                                $(_element).select2();
                            }
                        }
                    };
                }();

                core.init();
            });
        };

        if (undefined === jQuery().select2) {
            $("head")
                .append("<link rel='stylesheet' href='/bundles/pmtool/vendor/select2/" + pmUtil.config.module.select2.version + "/css/select2.min.css' type='text/css' media='screen' />")
                .append("<link rel='stylesheet' href='/bundles/pmtool/vendor/select2-bootstrap-theme/" + pmUtil.config.module.select2.theme + "/select2-bootstrap.min.css' type='text/css' media='screen' />");

            $.get('/bundles/pmtool/vendor/select2/' + pmUtil.config.module.select2.version + '/js/select2.full.min.js', function () {
                pmSelect2Init();
            });
        } else {
            pmSelect2Init();
        }

        return this;
    };

}(jQuery));