(function ($) {
    $.fn.pmBootbox = function (options) {

        var settings = {
            version: 170627,
            callback: {
                load: function () {
                    /* Executed on form loaded */
                    pmUtil.debug('{pmBootbox} settings.callback.load() default');
                },
                success: function () {
                    /* Executed on submit success */
                    pmUtil.debug('{pmBootbox} settings.callback.success() default');

                    window.location.reload(true);
                }
            }
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

                        onSuccess = onSuccess || settings.callback.success;

                        if (0 <= result.indexOf('<form')) {
                            buttons = dialog.getButtonsForm(onSuccess);
                        } else if (true === $(_element).data('confirm')) {
                            buttons = dialog.getButtonsConfirm(onSuccess);
                        } else {
                            buttons = {
                                close: {
                                    label: 'Schließen',
                                    className: 'btn-success'
                                }
                            };
                        }

                        bootbox.hideAll();
                        bootbox.dialog({
                            className: 'pm-bootbox-form-dialog',
                            title: core.getTitle(),
                            size: core.getSize(),
                            message: result,
                            buttons: buttons
                        });

                        settings.callback.load();
                    },
                    /**
                     * Get Buttons Confirmed
                     *
                     * @param onSuccess
                     * @returns {{cancel: {label: string, className: string}, confirm: {label: *, className: string, callback: Function}}}
                     */
                    getButtonsConfirm: function (onSuccess) {
                        return {
                            cancel: {
                                label: 'Abbrechen',
                                className: 'btn-default'
                            },
                            confirm: {
                                label: $(_element).attr('title'),
                                className: 'btn-success',
                                callback: function () {
                                    pmUtilLoading.startDialog();

                                    $.get($(_element).attr('href'), {confirmed: true}, function (result) {
                                        if ("" !== result) {
                                            dialog.create(result, onSuccess);
                                        } else {
                                            onSuccess();
                                        }
                                    });
                                }
                            }
                        }
                    },
                    /**
                     * Get Buttons Form
                     *
                     * @returns {{save: {label: string, className: string, callback: Function}, close: {label: string, className: string}}}
                     */
                    getButtonsForm: function (onSuccess) {
                        var labelSubmit = $(_element).data('button-submit');
                        if (undefined === labelSubmit) {
                            labelSubmit = 'Speichern';
                        }

                        return {
                            save: {
                                label: labelSubmit,
                                className: "btn-success",
                                callback: function () {
                                    if ('form' === $(_element).data('submit')) {
                                        $('.pm-bootbox-form-dialog form').attr('action', $(_element).attr('href')).submit();

                                        return;
                                    }

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
                        }).removeClass('disabled').removeClass('hidden');
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

        return this;
    };
}(jQuery));