(function ($) {
    $.fn.pmTable = function (options) {

        var settings = {
            version: 181106,
            bootstrap: 3,
            class_hidden: 'hidden',
            modules: {
                action: false,
                sortable: false,
                limitable: false,
                filter: false,
                search: false
            },
            paths: {
                self: "",
                filter: ""
            },
            action: {
                editable: false,
                deletable: false,
                path: null
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
                    menu: '.pm-table-filter',
                    labels: '.pm-table-filter-selected'
                },
                active: {},
                preload: false
            },
            search: {
                selectors: {
                    input: 'input.pm-table-search'
                }
            },
            icons: {
                sorting: {
                    asc: 'fa fa-caret-up text-muted',
                    desc: 'fa fa-caret-down text-muted'
                },
                hide: '<i class="fa fa-times"></i>'
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

            /**
             * Limit
             * @type {{init}}
             */
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


            /**
             * Filter
             */
            var filter = function () {
                "use strict";

                return {
                    /**
                     * Get Select2 Config
                     * @returns {{ajax: {url: *, dataType: string, delay: number, data: Function, processResults: Function, cache: boolean}, minimumInputLength: number, escapeMarkup: Function, templateResult: Function, templateSelection: Function, closeOnSelect: boolean}}
                     */
                    getSelect2Config: function (key, preload) {
                        return {
                            language: 'de',
                            ajax: {
                                url: settings.paths.filter,
                                dataType: 'json',
                                delay: 150,
                                data: function (params) {
                                    return {
                                        q: params.term, // search term
                                        page: params.page,
                                        key: key
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
                                return item.text;
                            },
                            closeOnSelect: true,
                            multiple: true,
                            allowClear: true,
                            dropdownParent: $(".bootbox-body")
                        };
                    },
                    /**
                     * Init Extended Data Adapter
                     */
                    initSelect2DataAdapter: function () {
                        core.debug("filter.initSelect2DataAdapter()");

                        $.fn.select2.amd.define('select2/data/extended-ajax', ['./ajax', '../utils', 'jquery'], function (AjaxAdapter, Utils, $) {

                            function ExtendedAjaxAdapter($element, options) {
                                //we need explicitly process minimumInputLength value
                                //to decide should we use AjaxAdapter or return defaultResults,
                                //so it is impossible to use MinimumLength decorator here
                                this.minimumInputLength = options.get('minimumInputLength');
                                this.defaultResults = options.get('defaultResults');

                                ExtendedAjaxAdapter.__super__.constructor.call(this, $element, options);
                            }

                            Utils.Extend(ExtendedAjaxAdapter, AjaxAdapter);

                            //override original query function to support default results
                            var originQuery = AjaxAdapter.prototype.query;

                            ExtendedAjaxAdapter.prototype.query = function (params, callback) {
                                var defaultResults = (typeof this.defaultResults == 'function') ? this.defaultResults.call(this) : this.defaultResults;
                                if (undefined !== defaultResults && (!params.term || params.term.length < this.minimumInputLength)) {
                                    var processedResults = this.processResults(defaultResults, params);
                                    callback(processedResults);
                                } else {
                                    originQuery.call(this, params, callback);
                                }
                            };

                            return ExtendedAjaxAdapter;
                        });
                    },
                    /**
                     * Get List
                     * @returns {*}
                     */
                    getList: function () {
                        var parent = $(_element).parent();

                        if (true === parent.hasClass('table-scrollable')) {
                            parent = parent.parent();
                        }

                        return parent.find(settings.filter.selectors.menu);
                    },
                    /**
                     * Get Input
                     * @param ajax
                     * @returns {*|HTMLElement}
                     */
                    getInput: function (ajax) {
                        if (false === ajax) {
                            return $('<input>', {
                                class: 'form-control',
                                style: 'width:100%;'
                            });
                        }

                        return $('<select>', {
                            class: 'form-control',
                            style: 'width:100%;',
                            multiple: 'multiple'
                        });
                    },
                    /**
                     * Show
                     */
                    show: function (th, title, preload) {
                        var key = th.data('key');
                        var ajax = th.data('ajax');

                        core.debug('filter.show(' + key + ',' + title + ')');

                        var input = filter.getInput(ajax);

                        bootbox.dialog({
                            className: "pm-table-filter-dialog",
                            title: title,
                            message: input,
                            buttons: {
                                save: {
                                    label: "Anwenden",
                                    className: "btn-success",
                                    callback: function () {
                                        pmUtilLoading.start();

                                        var dialog = $('.pm-table-filter-dialog');

                                        if (false === ajax) {
                                            filter.apply(key, [dialog.find('input').val()]);

                                            return true;
                                        }

                                        filter.apply(key, dialog.find('select').val());
                                    }
                                },
                                close: {
                                    label: "Abbrechen",
                                    className: "btn-warning"
                                }
                            }
                        });

                        $.fn.modal.Constructor.prototype.enforceFocus = function () {
                        };

                        if (false === ajax) {
                            return;
                        }

                        var select = $('.pm-table-filter-dialog').find('select');

                        var selectConfig = filter.getSelect2Config(key);
                        if (true === settings.filter.preload && false !== ajax) {
                            selectConfig.defaultResults = preload;
                            selectConfig.dataAdapter = $.fn.select2.amd.require('select2/data/extended-ajax');
                        }

                        select.select2(selectConfig);

                        window.setTimeout(function () {
                            select.select2("open");
                        }, 300);
                    },
                    /**
                     * Apply
                     * @param key
                     * @param values
                     */
                    apply: function (key, values) {
                        core.debug("filter.apply(" + key + "," + values.length + " values)");

                        if (typeof settings.filter.active[key] !== 'array') {
                            settings.filter.active[key] = [];
                        }

                        $.each(values, function (index, value) {
                            settings.filter.active[key].push({"id": value});
                        });

                        core.reload();
                    },
                    /**
                     * Remove
                     * @param key
                     * @param id
                     */
                    remove: function (key, id) {
                        core.debug('filter.remove(' + key + ')');


                        $.each(settings.filter.active[key], function (index, value) {
                            if (value.id === id) {
                                settings.filter.active[key].splice(index, 1);
                            }
                        });

                        core.reload();
                    },
                    /**
                     * Init
                     */
                    init: function () {
                        core.debug("filter.init()");

                        var filterCount = 0;
                        var titles = {};

                        $(_element).find('th').each(function () {
                            if (true !== $(this).data('filter')) {
                                return;
                            }

                            var th = $(this);
                            var title = th.text();
                            var ajax = th.data('ajax');
                            var key = th.data('key');

                            titles[key] = title;

                            var entry = $('<a></a>', {
                                href: 'javascript:void(0)'
                            }).text(title).on('click', function () {
                                if (true === settings.filter.preload && false !== ajax) {
                                    $.get(settings.paths.filter, {key: key, preload: true}, function (result) {
                                        filter.show(th, title, result);
                                    }, 'json');
                                } else {
                                    filter.show(th, title);
                                }
                            });

                            var filterList = filter.getList();
                            if (true === filterList.hasClass('btn-group')) {
                                entry.addClass('btn btn-default');

                                filterList.append(entry);
                            } else {
                                filterList.append($('<li></li>').append(entry));
                            }

                            filterCount++;
                        });

                        if (0 < filterCount) {
                            filter.getList().parent().removeClass(settings.class_hidden);
                        }

                        var labelClass = 'label label-default';
                        if (4 === settings.bootstrap) {
                            labelClass = 'badge badge-info';
                        }

                        $.each(settings.filter.active, function (key, values) {
                            $.each(values, function (valueKey, valueItem) {
                                var label = $('<a></a>', {
                                    href: 'javascript:void(0)',
                                    class: labelClass,
                                    dataKey: key,
                                    dataValue: valueItem.id
                                }).html('<strong>' + titles[key] + ':</strong> ' + valueItem.text + ' ' + settings.icons.hide).on('click', function () {
                                    filter.remove(key, valueItem.id);
                                });

                                $(settings.filter.selectors.labels).append(label);
                            });
                        });

                        if (true === settings.filter.preload) {
                            filter.initSelect2DataAdapter();
                        }
                    }
                }
            }();

            var search = function () {
                "use strict";

                return {
                    /**
                     * Get Input
                     * @returns {*|HTMLElement}
                     */
                    getInput: function () {
                        return $(settings.search.selectors.input);
                    },
                    /**
                     * Get Term
                     * @returns {*}
                     */
                    getTerm: function () {
                        return this.getInput().val();
                    },
                    /**
                     * Init
                     */
                    init: function () {
                        core.debug("search.init()");

                        this.getInput().on('change', function () {
                            core.reload();
                        }).parent().removeClass(settings.class_hidden);

                    }
                }
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

                        if (true === settings.modules.filter) {
                            for (var index in settings.filter.active) {
                                if (false === settings.filter.active.hasOwnProperty(index)) {
                                    continue;
                                }

                                var value = settings.filter.active[index];
                                var values = [];

                                $.each(value, function (valueIndex, valueData) {
                                    values.push(valueData.id);
                                });

                                queryString.push("filter[" + index + "]=" + values.join(','));
                            }
                        }

                        if (true === settings.modules.search) {
                            queryString.push("search=" + search.getTerm());
                        }

                        core.debug(" => " + queryString.join("&"));

                        window.location.href = settings.paths.self + "?" + queryString.join("&");
                    },
                    /**
                     * Init
                     */
                    init: function () {
                        core.debug('core.init()');

                        $.ajaxSetup({
                            cache: true
                        });

                        /* Inline settings */
                        if (undefined !== $(_element).data('bootstrap')) {
                            settings.bootstrap = $(_element).data('bootstrap');
                        }

                        if (undefined !== $(_element).data('module-search')) {
                            settings.modules.search = true;
                            settings.search.selectors.input = $(_element).data('module-search');
                        }

                        /* Bootstrap 4 manipulation */
                        if(4 === settings.bootstrap && 'hidden' === settings.class_hidden){
                            settings.class_hidden = 'invisible';
                        }

                        this.initModules();
                    },
                    /**
                     * Init Modules
                     */
                    initModules: function () {
                        if (true === settings.modules.action) {
                            $(_element).pmTableAction(settings.action);
                        }

                        if (true === settings.modules.filter) {
                            filter.init();
                        }

                        if (true === settings.modules.sortable) {
                            sorting.init();
                        }

                        if (true === settings.modules.limitable) {
                            limit.init();
                        }

                        if (true === settings.modules.search) {
                            search.init();
                        }
                    }
                };
            }();

            core.init();
        });

    };
}(jQuery));