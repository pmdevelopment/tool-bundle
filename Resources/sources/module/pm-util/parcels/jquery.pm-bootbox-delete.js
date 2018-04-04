(function ($) {
    $.fn.pmBootboxDelete = function (options) {

        var language = {
            de: {
                yes: 'Ja',
                no: 'Nein',
            },
            en: {
                yes: 'Yes',
                no: 'No'
            }
        };

        var settings = {
            version: 180329,
            callback: {
                success: function (element) {
                    /* Executed on submit success */
                    pmUtil.debug('{pmBootboxDelete} settings.callback.success() default');

                    pmUtilLoading.start();
                    window.location.href = $(element).attr('href');
                }
            }
        };

        settings = $.extend({}, settings, options);

        this.each(function () {
            var _element = this;

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
                            pmUtil.debug('{pmBootboxDelete} ' + arguments[i]);
                        }
                    },
                    /**
                     * Init
                     */
                    init: function () {
                        core.debug('core.init()');

                        var hiddenClass = 'invisible';
                        if ($(_element).data('hidden')) {
                            hiddenClass = $(_element).data('hidden');
                        }

                        $(_element).on('click', function () {
                            var languageActive = $(_element).data('language');

                            if (typeof languageActive == 'undefined') {
                                bootbox.alert('Missing data-language attribute!');

                                return false;
                            }

                            bootbox.confirm({
                                message: $(_element).attr('title'),
                                buttons: {
                                    confirm: {
                                        label: language[languageActive].yes,
                                        className: 'btn-success'
                                    },
                                    cancel: {
                                        label: language[languageActive].no,
                                        className: 'btn-default'
                                    }
                                },
                                callback: function (result) {
                                    if (true === result) {
                                        settings.callback.success(_element);
                                    }
                                }
                            });

                            return false;
                        }).removeClass('disabled').removeClass(hiddenClass);
                    }
                };
            }();

            core.init();
        });

    };
}(jQuery));