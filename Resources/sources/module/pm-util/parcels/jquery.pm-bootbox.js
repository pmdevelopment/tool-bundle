(function ($) {
    $.fn.pmBootbox = function (options) {

        var language = {
            de: {
                save: 'Speichern',
                cancel: 'Abbrechen',
                close: 'Schließen'
            },
            en: {
                save: 'Save',
                cancel: 'Cancel',
                close: 'Close'
            }
        }

        var settings = {
            version: 170828,
            callback: {
                load: function (element) {
                    /* Executed on form loaded */
                    pmUtil.debug('{pmBootbox} settings.callback.load() default');

                    var recursive = $(element).data('recursive') || settings.recursive;
                    if (null !== recursive) {
                        $('.pm-bootbox-form-dialog').find(recursive).pmBootbox(settings);
                    }
                },
                success: function () {
                    /* Executed on submit success */
                    pmUtil.debug('{pmBootbox} settings.callback.success() default');

                    window.location.reload(true);
                },
                cancel: function () {
                    /* Executed on cancel */
                    pmUtil.debug('{pmBootbox} settings.callback.cancel() default');
                },
                init: function () {
                    /* Executed on init */
                    pmUtil.debug('{pmBootbox} settings.callback.init() default');
                }
            },
            use_form_action: true,
            use_pm_markdown: false,
            disable_buttons: false,
            recursive: null,
            animate: true,
            language: 'de'
        };

        settings = $.extend({}, settings, options);

        this.each(function () {
            var _element = this;

            /**
             * Dialog
             * @type {{init, create, getButtons, getButtonsConfirm, getButtonsForm}}
             */
            var dialog = function () {
                "use strict";

                return {
                    /**
                     * Init
                     * @param onSuccess
                     */
                    init: function (onSuccess, onCancel) {
                        core.debug('dialog.init()');

                        var uri = core.getUri();
                        if (null === uri) {
                            pmUtilLoading.stopDialog();
                            bootbox.alert({
                                title: core.getTitle(),
                                message: $(_element).data('title')
                            });

                            return false;
                        }

                        $.get(core.getUri(), {}, function (result) {
                            if ('' === result) {
                                onSuccess = onSuccess || settings.callback.success;

                                onSuccess();
                            } else {
                                dialog.create(result, onSuccess, onCancel);
                            }
                        });
                    },
                    /**
                     *
                     * @param result
                     * @param onSuccess
                     */
                    create: function (result, onSuccess, onCancel) {
                        core.debug('dialog.create()');
                        var buttons, type;

                        onSuccess = onSuccess || settings.callback.success;
                        onCancel = onCancel || settings.callback.cancel

                        pmUtilLoading.stopDialog();

                        var bootboxDialog = bootbox.dialog({
                            className: 'pm-bootbox-form-dialog',
                            title: core.getTitle(),
                            size: core.getSize(),
                            message: result,
                            buttons: dialog.getButtons(result, onSuccess, onCancel),
                            animate: settings.animate
                        });

                        if (true === settings.use_pm_markdown && true === pmUtil.config.module.simpleMde.enabled) {
                            bootboxDialog.on('shown.bs.modal', function () {
                                pmUtil.config.module.simpleMde.callback();
                            });
                        }

                        settings.callback.load(_element);
                    },
                    /**
                     * Get Buttons
                     *
                     * @param result
                     * @param onSuccess
                     * @param onCancel
                     * @returns {*}
                     */
                    getButtons: function (result, onSuccess, onCancel) {
                        if (true === settings.disable_buttons) {
                            return {};
                        }

                        if (0 <= result.indexOf('<form')) {
                            return dialog.getButtonsForm(onSuccess);
                        }

                        if (true === $(_element).data('confirm')) {
                            return dialog.getButtonsConfirm(onSuccess, onCancel);
                        }

                        return {
                            close: {
                                label: language[settings.language].close,
                                className: 'btn-success'
                            }
                        };
                    },
                    /**
                     * Get Buttons Confirm
                     *
                     * @param onSuccess
                     * @param onCancel
                     * @returns {{cancel: {label: string, className: string, callback: *}, confirm: {label, className: string, callback: confirm.callback}}}
                     */
                    getButtonsConfirm: function (onSuccess, onCancel) {
                        return {
                            confirm: {
                                label: $(_element).attr('title'),
                                className: 'btn-success',
                                callback: function () {
                                    pmUtilLoading.startDialog();

                                    $.get(core.getUri(), {confirmed: true}, function (result) {
                                        if ("" !== result) {
                                            dialog.create(result, onSuccess);
                                        } else {
                                            onSuccess();
                                        }
                                    });
                                }
                            },
                            cancel: {
                                label: language[settings.language].cancel,
                                className: 'btn-default',
                                callback: onCancel
                            },
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
                            labelSubmit = language[settings.language].save;
                        }

                        return {
                            save: {
                                label: labelSubmit,
                                className: "btn-success",
                                callback: function () {
                                    if ('form' === $(_element).data('submit')) {
                                        $('.pm-bootbox-form-dialog form').attr('action', core.getUri()).submit();

                                        return;
                                    }

                                    var form = $('.pm-bootbox-form-dialog').find('form');
                                    var uri = core.getUri();

                                    if (true === settings.use_form_action && 'undefined' !== typeof form.attr('action')) {
                                        uri = form.attr('action');
                                    }

                                    pmUtilLoading.startDialog();

                                    if (0 < form.find('input[type="file"]').length) {
                                        $.ajax({
                                            url: uri,
                                            type: "POST",
                                            data: new FormData(form[0]),
                                            contentType: false,
                                            cache: false,
                                            processData: false,
                                            success: function (result) {
                                                if ("" !== result) {
                                                    dialog.create(result, onSuccess);
                                                } else {
                                                    onSuccess();
                                                }
                                            }
                                        });

                                    } else {
                                        $.post(uri, form.serialize(), function (result) {
                                            if ("" !== result) {
                                                dialog.create(result, onSuccess);
                                            } else {
                                                onSuccess();
                                            }
                                        });
                                    }
                                }
                            },
                            close: {
                                label: language[settings.language].cancel,
                                className: "btn-default"
                            }
                        };
                    }
                };
            }();

            /**
             * Core
             *
             * @type {{debug, init, getTitle, getSize, getUri}}
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
                        });

                        var hiddenClass = 'invisible';
                        if ($(_element).data('hidden')) {
                            hiddenClass = $(_element).data('hidden');
                        }

                        if (undefined !== settings.callback.init) {
                            settings.callback.init();
                        }

                        $(_element).removeClass('disabled').removeClass(hiddenClass);
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
                    },
                    /**
                     * Get Uri
                     * @returns {*|HTMLElement}
                     */
                    getUri: function () {
                        if ($(_element).attr('href')) {
                            return $(_element).attr('href');
                        }

                        if ($(_element).data('bootbox')) {
                            return $(_element).data('bootbox');
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