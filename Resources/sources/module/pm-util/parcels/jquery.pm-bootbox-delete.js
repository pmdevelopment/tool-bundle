(function ($) {
    $.fn.pmBootboxDelete = function (options) {

        var settings = {
            version: 170117,
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
                            core.debug('core.init() => element.onClick');

                            var title = "Den Eintrag wirklich löschen?";
                            if ($(_element).attr('title')) {
                                title = $(_element).attr('title');
                            }

                            bootbox.confirm(title, function (result) {
                                if (true === result) {
                                    settings.callback.success(_element);
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