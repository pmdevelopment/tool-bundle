(function ($) {
    $.fn.pmBootbox = function (options) {

        var settings = {
            version: 161114
        };

        settings = $.extend({}, settings, options);

        this.each(function () {
            var _element = this;

            /**
             * Dialog
             * @type {{init, create, getButtonsForm}}
             */
            var dialog = function () {
                "use strict";

                return {
                    /**
                     * Init
                     * @param onSuccess
                     */
                    init: function (onSuccess) {
                        core.debug('dialog.init()');

                        $.get($(_element).attr('href'), {}, function (result) {
                            dialog.create(result, onSuccess);
                        });
                    },
                    /**
                     *
                     * @param result
                     * @param onSuccess
                     */
                    create: function (result, onSuccess) {
                        core.debug('dialog.create()');
                        var buttons, type;

                        var defaultOnSuccess = function () {
                            window.location.reload(true);
                        };

                        onSuccess = onSuccess || defaultOnSuccess;

                        if (0 <= result.indexOf('<form')) {
                            buttons = dialog.getButtonsForm(onSuccess);
                        } else {
                            buttons = {
                                close: {
                                    label: "Schließen",
                                    className: "btn-success"
                                }
                            };
                        }

                        bootbox.hideAll();
                        bootbox.dialog({
                            className: "pm-bootbox-form-dialog",
                            title: core.getTitle(),
                            size: core.getSize(),
                            message: result,
                            buttons: buttons
                        });
                    },
                    /**
                     * Get Buttons Form
                     *
                     * @returns {{save: {label: string, className: string, callback: Function}, close: {label: string, className: string}}}
                     */
                    getButtonsForm: function (onSuccess) {
                        return {
                            save: {
                                label: "Speichern",
                                className: "btn-success",
                                callback: function () {
                                    var formData = $('.pm-bootbox-form-dialog form').serialize();
                                    pmUtilLoading.startDialog();

                                    $.post($(_element).attr('href'), formData, function (result) {
                                        if ("" !== result) {
                                            dialog.create(result, onSuccess);
                                        } else {
                                            onSuccess();
                                        }
                                    });
                                }
                            },
                            close: {
                                label: "Abbrechen",
                                className: "btn-warning"
                            }
                        };
                    }
                };
            }();

            /**
             * Core
             *
             * @type {{debug, init, getTitle, getSize}}
             */
            var core = function () {
                "use strict";

                return {
                    /**
                     * Debug
                     */
                    debug: function () {
                        for (var i = 0; i < arguments.length; i++) {
                            pmUtil.debug('{pmBootbox} ' + arguments[i]);
                        }
                    },
                    /**
                     * Init
                     */
                    init: function () {
                        core.debug('core.init()');

                        $(_element).on('click', function () {
                            pmUtilLoading.startDialog();

                            dialog.init();

                            return false;
                        }).removeClass('disabled');
                    },
                    /**
                     * Get Title
                     * @returns {*}
                     */
                    getTitle: function () {
                        if ($(_element).attr('title')) {
                            return $(_element).attr('title');
                        }

                        return $(_element).text();
                    },
                    /**
                     * Get Size
                     * @returns {*}
                     */
                    getSize: function () {
                        if ($(_element).hasClass('large')) {
                            return "large";
                        }

                        return null;
                    }
                };
            }();

            core.init();
        });

    };
}(jQuery));