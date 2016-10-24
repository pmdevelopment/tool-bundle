(function ($) {
    $.fn.pmTable = function (options) {

        var settings = {
            modules: {
                action: false,
                sortable: false,
                limitable: false,
                filters: false
            },
            paths: {
                self: "",
                action: "",
                filter: ""
            },
            sorting: {
                index: "",
                direction: ""
            },
            limit: {
                select: 'select.pm-table-limit',
                value: 50
            },
            filter: {
                selectors: {
                    menu: 'ul.pm-table-filter',
                    labels: '.pm-table-filter-selected'
                },
                active: {}
            },
            icons: {
                sorting: {
                    asc: 'fa fa-caret-up text-muted',
                    desc: 'fa fa-caret-down text-muted'
                }
            }
        };

        settings = $.extend({}, settings, options);

        this.each(function () {
            var _element = this;

            /**
             * Sorting
             * @type {{update, init}}
             */
            var sorting = function () {
                "use strict";

                return {
                    /**
                     * Update
                     * @param key
                     * @param direction
                     */
                    update: function (key, direction) {
                        core.debug('sorting.update(' + key + ',' + direction + ')');

                        settings.sorting.index = key;
                        settings.sorting.direction = direction;

                        core.reload();
                    },
                    /**
                     * Init
                     */
                    init: function () {
                        core.debug('sorting.init()');

                        $(_element).find('th').each(function () {
                            if (true !== $(this).data('sortable')) {
                                return;
                            }

                            var key = $(this).data('key');

                            var link = $('<a></a>', {
                                href: 'javascript:void(0)'
                            }).text($(this).text()).on('click', function () {
                                var direction = 'asc';

                                if (key === settings.sorting.index && 'asc' === settings.sorting.direction) {
                                    direction = 'desc';
                                }

                                sorting.update(key, direction);
                            });

                            $(this).html(link);

                            if (settings.sorting.index === key) {
                                $(this).prepend($('<i></i>', {
                                    style: 'padding-right:10px;',
                                    class: settings.icons.sorting[settings.sorting.direction]
                                }));
                            }
                        });
                    }
                };
            }();

            var limit = function () {
                "use strict";

                return {
                    /**
                     * Init
                     */
                    init: function () {
                        core.debug('limit.init()');

                        $(_element).parent().find(settings.limit.select).unbind('change').on('change', function () {
                            settings.limit.value = $(this).val();

                            core.reload();
                        });
                    }
                };
            }();

            var core = function () {
                "use strict";

                return {
                    /**
                     * Debug
                     */
                    debug: function () {
                        for (var i = 0; i < arguments.length; i++) {
                            pmUtil.debug('{pmTable} ' + arguments[i]);
                        }
                    },
                    /**
                     * Reload
                     */
                    reload: function () {
                        core.debug('core.reload()');

                        pmUtilLoading.start();
                        var queryString = [];

                        if (true === settings.modules.sortable) {
                            queryString.push("order_by=" + settings.sorting.index);
                            queryString.push("order_dir=" + settings.sorting.direction);
                        }

                        if (true === settings.modules.limitable) {
                            queryString.push("limit=" + settings.limit.value);
                        }

                        window.location.href = settings.paths.self + "?" + queryString.join("&");
                    },
                    /**
                     * Init
                     */
                    init: function () {
                        core.debug('core.init()');

                        if (true === settings.modules.action) {
                            $(_element).pmTableAction({
                                editable: true,
                                path: settings.paths.action
                            });
                        }

                        if (true === settings.modules.filters) {
                            $(_element).pmTableFilter({
                                path: settings.paths.filter,
                                selectors: settings.filter.selectors
                            });
                        }

                        if (true === settings.modules.sortable) {
                            sorting.init();
                        }

                        if (true === settings.modules.limitable) {
                            limit.init();
                        }
                    }
                };
            }();

            core.init();
        });
    };
}(jQuery));