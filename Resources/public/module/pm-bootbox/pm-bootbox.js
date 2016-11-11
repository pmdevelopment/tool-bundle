/**
 * Bootbox
 *
 * @type {{dialog, form, formCreate, init}}
 */
var pmBootbox = function () {
    "use strict";

    return {
        /**
         * Dialog
         *
         * @param element
         * @param size
         * @param callback
         */
        dialog: function (element, size, callback) {
            pmUtilLoading.start();
            if (!size) {
                size = "small";
            }

            $.get($(element).attr('href'), {}, function (result) {
                bootbox.dialog({
                    title: $(element).attr('title'),
                    message: result,
                    size: size,
                    buttons: {
                        close: {
                            label: "Schließen",
                            className: "btn-success"
                        }
                    }
                });

                if (typeof callback === "function") {
                    callback();
                }

                pmUtilLoading.stop();
            }, 'html');
        },
        /**
         * Form
         *
         * @param element
         * @param onSuccess
         */
        form: function (element, onSuccess) {
            var _self = this;

            pmUtilLoading.start();
            $.get($(element).attr('href'), {}, function (result) {
                _self.formCreate(element, result, onSuccess);
            });
        },
        /**
         * Form Create
         *
         * @param element
         * @param content
         * @param onSuccess
         */
        formCreate: function (element, content, onSuccess) {
            var _self = this, title, size;

            var defaultOnSuccess = function () {
                window.location.reload(true);
            };

            onSuccess = onSuccess || defaultOnSuccess;

            if ($(element).attr('title')) {
                title = $(element).attr('title');
            } else {
                title = $(element).text();
            }

            if ($(element).data('size')) {
                size = $(element).data('size');
            } else {
                size = null;
            }

            bootbox.dialog({
                className: "bootbox-form-dialog",
                title: title,
                size: size,
                message: content,
                buttons: {
                    save: {
                        label: "Speichern",
                        className: "btn-success",
                        callback: function () {
                            pmUtilLoading.start();

                            var form = $('.bootbox-form-dialog form');

                            if (0 < form.find('input[type="file"]').length) {
                                $.ajax({
                                    url: $(element).attr('href'),
                                    type: "POST",
                                    data: new FormData(form[0]),
                                    contentType: false,
                                    cache: false,
                                    processData: false,
                                    success: function (result) {
                                        if ("" !== result) {
                                            _self.formCreate(element, result, onSuccess);
                                        } else {
                                            onSuccess();
                                        }
                                    }
                                });

                            } else {
                                $.post($(element).attr('href'), form.serialize(), function (result) {
                                    if ("" !== result) {
                                        _self.formCreate(element, result);
                                    } else {
                                        onSuccess();
                                    }
                                });
                            }
                        }
                    },
                    close: {
                        label: "Abbrechen",
                        className: "btn-warning"
                    }
                }
            });

            pmUtilLoading.stop();
        },
        /**
         * Init
         */
        init: function () {
            var _self = this;

            $('a.bootbox-dialog').on('click', function () {
                if ($(this).hasClass("bootbox-form")) {
                    _self.form(this, $(this).data("size"));
                } else {
                    _self.dialog(this, $(this).data("size"));
                }

                return false;
            }).removeClass('disabled');
        }
    };
}();

$(document).ready(function () {
    pmBootbox.init();
});
