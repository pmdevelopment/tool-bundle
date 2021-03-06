(function ($) {
    $.fn.pmTableAction = function (options) {

        var settings = {
            editable: true,
            deletable: false,
            path: null,
            custom: []
        };

        /*
         * Custom action:
         * {
         *    id: 'foobar',
         *    title: 'Title',
         *    path: '...',
         *    color: 'btn-primary'
         * }
         */

        settings = $.extend({}, settings, options);

        if (null === settings.path) {
            console.log("{pmTableAction} Config Error: No Path");
        }

        this.each(function () {
            var _element = this;

            /**
             * Form
             * @type {{getTitle, getPath, load, show}}
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

                        if ('delete' === action) {
                            return "Löschen";
                        }

                        for (var index = 0; index < settings.custom.length; index++) {
                            if (action === settings.custom[index].id) {
                                return settings.custom[index].title;
                            }
                        }

                        bootbox.alert('Unbekannte Aktion: ' + action);

                        return "";
                    },
                    /**
                     * Get Path
                     * @param action
                     * @returns {*}
                     */
                    getPath: function (action) {
                        if ('edit' === action || 'delete' === action) {
                            return settings.path;
                        }

                        for (var index = 0; index < settings.custom.length; index++) {
                            if (action === settings.custom[index].id) {
                                return settings.custom[index].path;
                            }
                        }

                        bootbox.alert('Unbekannte Aktion: ' + action);

                        return "";
                    },
                    /**
                     * Load
                     */
                    load: function (action, callback) {
                        core.log('form.load(' + action + ')');

                        pmCore.loadingStart();

                        var selectedIds = [];
                        checkboxes.getAllChecked().each(function () {
                            selectedIds.push($(this).val());
                        });

                        var path = form.getPath(action) + "?action=" + action + "&ids=" + selectedIds.join(',');

                        $.get(path, {}, function (result) {
                            if ("delete" === action) {
                                if ("" === result) {
                                    window.location.reload(true);
                                } else {
                                    pmCore.loadingEnd();
                                    bootbox.alert("Es ist ein Problem aufgetreten.");
                                }

                            } else {
                                form.show(action, path, result, callback);

                                pmCore.loadingEnd();
                            }
                        }, 'html');
                    },
                    /**
                     * Show
                     */
                    show: function (action, path, html, callback) {
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
                                                form.show(action, path, result, callback);
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

                        if (undefined !== callback && undefined !== callback.on_load) {
                            $('.bootbox-form-dialog').on('shown.bs.modal', function () {
                                callback.on_load();
                            });
                        }
                    }
                };
            }();

            /**
             * Buttons
             * @type {{classes, getAll, getEdit, getDelete, getCustom, addEdit, addDelete, addCustom, addCount}}
             */
            var buttons = function () {
                "use strict";

                return {
                    classes: {
                        button: 'btn btn-circle btn-sm',
                        colors: {
                            edit: 'yellow-saffron',
                            delete: 'red'
                        }
                    },
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
                     * Get Edit
                     * @returns {*}
                     */
                    getDelete: function () {
                        return this.getAll('a.pm-button-delete');
                    },
                    /**
                     * Get Customs
                     * @returns {*}
                     */
                    getCustom: function () {
                        return this.getAll('a.pm-button-custom');
                    },
                    /**
                     * Add Edit
                     */
                    addEdit: function () {
                        core.log('buttons.addEdit()');

                        this.getAll().append('<a href="javascript:void(0)" class="' + buttons.classes.button + ' ' + buttons.classes.colors.edit + ' pm-button-edit">Bearbeiten</a>');

                        this.getEdit().on('click', function () {
                            form.load("edit");
                        });
                    },
                    /**
                     * Add Delete
                     */
                    addDelete: function () {
                        core.log('buttons.addDelete()');

                        this.getAll().append('<a href="javascript:void(0)" class="' + buttons.classes.button + ' ' + buttons.classes.colors.delete + ' pm-button-delete">Löschen</a>');

                        this.getDelete().on('click', function () {
                            bootbox.confirm('Sollen die Einträge wirklich gelöscht werden?', function (result) {
                                if (true === result) {
                                    form.load("delete");
                                }
                            });

                        });
                    },
                    /**
                     * Add Custom
                     */
                    addCustom: function () {
                        core.log('buttons.addCustom()');

                        if (0 < settings.custom.length && 0 === buttons.getCustom().length) {
                            $(settings.custom).each(function () {
                                buttons.getAll().append('&nbsp;<a href="javascript:void(0)" class="' + buttons.classes.button + ' ' + this.color + ' pm-button-custom" data-id="' + this.id + '">' + this.title + '</a>');
                            });

                            $(settings.custom).each(function () {
                                var customButton = this;

                                buttons.getAll('a[data-id="' + customButton.id + '"]').on('click', function () {
                                    if (undefined === customButton.callback) {
                                        form.load($(this).data('id'));
                                    } else {
                                        form.load($(this).data('id'), customButton.callback);
                                    }
                                });
                            });
                        }
                    },
                    /**
                     * Add Count
                     *
                     * @param count
                     */
                    addCount: function (count) {
                        core.log('buttons.addCount()');

                        var counter = buttons.getAll('a.counter');

                        if (0 === counter.length) {
                            buttons.getAll().prepend('<a href="javascript:void(0)" class="' + buttons.classes.button + ' disabled counter">' + count + 'x</a>');
                        } else {
                            counter.text(count + 'x');
                        }
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

                        if (0 === rowSelectedCount) {
                            core.log('  - removed button, no selection');
                            buttons.getAll('a').remove();

                            return;
                        }

                        if (true === settings.editable) {
                            if (0 === buttons.getEdit().length) {
                                buttons.addEdit();
                            }
                        }

                        if (true === settings.deletable) {
                            if (0 === buttons.getDelete().length) {
                                buttons.addDelete();
                            }
                        }

                        buttons.addCustom();
                        buttons.addCount(rowSelectedCount);
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
                    /**
                     * Init
                     */
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