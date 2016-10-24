(function ($) {
    $.fn.pmTableFilter = function (options) {

        var settings = {
            path: null,
            selectors: {
                menu: '',
                labels: ''
            }
        };

        settings = $.extend({}, settings, options);

        if (null === settings.path) {
            console.log("{pmTableFilter} Config Error: No Path");
        }

        this.each(function () {
            var _element = this;

            /**
             * Form
             *
             * @type {{getTitle, load, show}}
             */
            var form = function () {
                "use strict";

                return {
                    /**
                     * Get Select2 Config
                     * @returns {{ajax: {url: *, dataType: string, delay: number, data: Function, processResults: Function, cache: boolean}, minimumInputLength: number, escapeMarkup: Function, templateResult: Function, templateSelection: Function, closeOnSelect: boolean}}
                     */
                    getSelect2Config: function (key) {
                        return {
                            ajax: {
                                url: settings.path + "?key=" + key,
                                dataType: 'json',
                                delay: 150,
                                data: function (params) {
                                    return {
                                        q: params.term, // search term
                                        page: params.page
                                    };
                                },
                                processResults: function (data, params) {
                                    params.page = params.page || 1;

                                    return {
                                        results: data.items,
                                        more: (params.page * 30) < data.total_count
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
                                return item.text
                            },
                            closeOnSelect: false
                        };
                    },
                    /**
                     * Show
                     */
                    show: function (key, title) {
                        core.log('form.show(' + key + ',' + title + ')');

                        var input = $('<select></select>', {
                            class: 'form-control'
                        });

                        bootbox.dialog({
                            className: "bootbox-form-dialog",
                            title: title,
                            message: input,
                            buttons: {
                                save: {
                                    label: "Anwenden",
                                    className: "btn-success",
                                    callback: function () {
                                        pmCore.loadingStart();


                                    }
                                },
                                close: {
                                    label: "Abbrechen",
                                    className: "btn-warning"
                                }
                            }
                        });

                        $(input).select2(form.getSelect2Config(key));
                    }
                };
            }();

            /**
             * Core
             *
             * @type {{log, init}}
             */
            var core = function () {
                "use strict";

                return {
                    /**
                     * Get List
                     * @returns {*}
                     */
                    getList: function () {
                        return $(_element).parent().find(settings.selectors.menu);
                    },
                    /**
                     * Log
                     * @param message
                     */
                    log: function (message) {
                        pmUtil.debug("{pmTableFilter} " + message);
                    },
                    /**
                     * Apply
                     * @param key
                     * @param title
                     */
                    apply: function (key, title) {
                        core.log("core.apply(" + key + "," + title + ")");


                    },
                    /**
                     * Init
                     */
                    init: function () {
                        core.log("core.init()");

                        var filterCount = 0;

                        $(_element).find('th').each(function () {
                            if (true !== $(this).data('filter')) {
                                return;
                            }

                            var key = $(this).data('key');
                            var title = $(this).text();

                            var entry = $('<a></a>', {
                                href: 'jaavascript:void(0)'
                            }).text(title).on('click', function () {
                                form.show(key, title);
                            });

                            core.getList().append($('<li></li>').append(entry));
                            filterCount++;
                        });

                        if (0 < filterCount) {
                            core.getList().parent().removeClass('hidden');
                        }
                    }
                }
            }();

            core.init();
        });
    };
}(jQuery));