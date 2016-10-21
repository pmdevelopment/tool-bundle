(function ($) {
    $.fn.pmTableAction = function (options) {

        var settings = {
            editable: true,
            path: null
        };

        settings = $.extend({}, settings, options);

        if (null === settings.path) {
            console.log("{pmTableAction} Config Error: No Path");
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
                     * Get Title
                     * @param action
                     * @returns {*}
                     */
                    getTitle: function (action) {
                        if ('edit' === action) {
                            return "Bearbeiten";
                        }

                        return "";
                    },
                    /**
                     * Load
                     */
                    load: function (action) {
                        core.log('form.load(' + action + ')');

                        pmCore.loadingStart();

                        var selectedIds = [];
                        checkboxes.getAllChecked().each(function () {
                            selectedIds.push($(this).val());
                        });

                        var path = settings.path + "?action=" + action + "&ids=" + selectedIds.join(",");

                        $.get(path, {}, function (result) {
                            form.show(action, path, result);

                            pmCore.loadingEnd();
                        }, 'html');
                    },
                    /**
                     * Show
                     */
                    show: function (action, path, html) {
                        core.log('form.show()');

                        bootbox.dialog({
                            className: "bootbox-form-dialog",
                            title: form.getTitle(action),
                            message: html,
                            buttons: {
                                save: {
                                    label: "Speichern",
                                    className: "btn-success",
                                    callback: function () {
                                        pmCore.loadingStart();

                                        $.post(path, $('.bootbox-form-dialog form').serialize(), function (result) {
                                            if ("" !== result) {
                                                form.show(action, path, result);
                                                pmCore.loadingEnd();
                                            } else {
                                                window.location.reload(true);
                                            }
                                        });
                                    }
                                },
                                close: {
                                    label: "Abbrechen",
                                    className: "btn-warning"
                                }
                            }
                        });
                    }
                };
            }();

            /**
             * Buttons
             *
             * @type {{getAll, getEdit, addEdit}}
             */
            var buttons = function () {
                "use strict";

                return {
                    /**
                     * Get
                     * @param filter
                     * @returns {*}
                     */
                    getAll: function (filter) {
                        var result = $(_element).parent().find('.pm-buttons');

                        if (filter) {
                            return result.find(filter);
                        }

                        return result;
                    },
                    /**
                     * Get Edit
                     * @returns {*}
                     */
                    getEdit: function () {
                        return this.getAll('a.pm-button-edit');
                    },
                    /**
                     * Add Edit
                     */
                    addEdit: function () {
                        core.log('buttons.addEdit()');

                        this.getAll().append('<a href="javascript:void(0)" class="btn btn-circle yellow-saffron btn-sm pm-button-edit"><span></span>x Bearbeiten</a>');

                        this.getEdit().on('click', function () {
                            form.load("edit");
                        });
                    }
                };
            }();

            /**
             * Checkboxes
             *
             * @type {{getAll, getAllChecked, getGlobalSelector, getCountSelect, onChange, init}}
             */
            var checkboxes = function () {
                "use strict";

                return {
                    /**
                     * Get All
                     * @returns {*}
                     */
                    getAll: function () {
                        return $(_element).find('input.pm-table-checkbox');
                    },
                    /**
                     * Get All Checked
                     * @returns {*}
                     */
                    getAllChecked: function () {
                        return $(_element).find('input.pm-table-checkbox:checked');
                    },
                    /**
                     * Global Selector
                     * @returns {*}
                     */
                    getGlobalSelector: function () {
                        return $(_element).find('th').find('input.pm-table-checkbox').first();
                    },
                    /**
                     * Count Selected
                     * @returns {*}
                     */
                    getCountSelect: function () {
                        var count = checkboxes.getAllChecked().length;
                        if (true === checkboxes.getGlobalSelector().prop('checked')) {
                            count = count - 1;
                        }

                        return count;
                    },
                    /**
                     * On Change
                     */
                    onChange: function () {
                        core.log('checkboxes.onChange()');

                        var rowSelectedCount = checkboxes.getCountSelect();
                        core.log(" - " + rowSelectedCount + " elements selected");

                        if (true === settings.editable) {
                            if (0 === rowSelectedCount) {
                                core.log('  - removed button, no selection');
                                buttons.getEdit().remove();

                                return;
                            }

                            if (0 === buttons.getEdit().length) {
                                buttons.addEdit();
                            }

                            buttons.getEdit().find('span').text(rowSelectedCount);
                        }
                    },
                    /**
                     * Init
                     */
                    init: function () {
                        core.log('checkboxes.init()');

                        $(_element).find('th').first().before('<th><input type="checkbox" value="all" class="pm-table-checkbox" /></th>');

                        $(_element).find('tr').each(function () {
                            var id = $(this).data('id');
                            $(this).find('td').first().before('<td><input type="checkbox" value="' + id + '" class="pm-table-checkbox" /></td>');
                        });

                        $(_element).after('<div class="clearfix"><div class="pm-buttons pull-right"></div></div>');

                        checkboxes.getAll().on('change', function () {
                            if ("all" === $(this).val()) {
                                checkboxes.getAll().prop('checked', $(this).prop('checked'));
                            }
                            checkboxes.onChange();
                        });
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
                     * Log
                     * @param message
                     */
                    log: function (message) {
                        pmUtil.debug("{pmTableAction} " + message);
                    },
                    init: function () {
                        core.log("core.init()");

                        checkboxes.init();
                    }
                }
            }();

            core.init();
        });
    };
}(jQuery));