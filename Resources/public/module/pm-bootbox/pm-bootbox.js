/**
 * Additional Bootbox Features
 */
var pmBootbox = function () {
    "use strict";

    return {
        dialog: function (element, size) {
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
                pmUtilLoading.stop();
            }, 'html');
        },
        /**
         * Form
         *
         * @param element
         */
        form: function (element) {
            var _self = this;

            pmUtilLoading.start();
            $.get($(element).attr('href'), {}, function (result) {
                _self.formCreate(element, result);
            });
        },
        /**
         * Form Create
         *
         * @param element
         * @param content
         */
        formCreate: function (element, content) {
            var _self = this, title;

            if ($(element).attr('title')) {
                title = $(element).attr('title');
            } else {
                title = $(element).text();
            }

            bootbox.dialog({
                className: "bootbox-form-dialog",
                title: title,
                message: content,
                buttons: {
                    save: {
                        label: "Speichern",
                        className: "btn-success",
                        callback: function () {
                            pmUtilLoading.start();
                            $.post($(element).attr('href'), $('.bootbox-form-dialog form').serialize(), function (result) {
                                if ("" !== result) {
                                    _self.formCreate(element, result);
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
            });
        }
    };
}();

$(document).ready(function () {
    pmBootbox.init();
});