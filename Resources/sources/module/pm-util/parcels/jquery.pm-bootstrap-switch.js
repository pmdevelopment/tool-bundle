(function ($) {

    $.fn.pmBootstrapSwitch = function (options) {

        var settings = {
            version: 161114
        };

        settings = $.extend({}, settings, options);

        var _target = this;

        var pmBootstrapSwitchInit = function () {
            _target.each(function () {
                var _element = $(this);

                var event = function () {
                    "use strict";

                    return {
                        change: function () {

                        }
                    };
                }();

                /**
                 * Core
                 * @type {{debug, init}}
                 */
                var core = function () {
                    "use strict";

                    return {
                        /**
                         * Debug
                         * @param message
                         */
                        debug: function (message) {
                            pmUtil.debug('{pmBootstrapSwitch} ' + message);
                        },
                        /**
                         * Init
                         */
                        init: function () {
                            core.debug('core.init()');

                            $(_element).removeClass('invisible').bootstrapSwitch();

                            /* Ajax */
                            if ($(_element).data('path')) {
                                $(_element).on('switchChange.bootstrapSwitch', function (event, state) {
                                    pmUtilLoading.startDialog();

                                    $.get($(_element).data('path'), {"value": $(_element).val(), "checked": state}, function (result) {
                                        pmUtilLoading.stop();

                                        if ("" !== result) {
                                            bootbox.alert('Es ist ein Fehler aufgetreten. Bitte lade die Seite neu.');
                                        }
                                    });
                                });
                            }

                            /* Redirect */
                            if ($(_element).data('redirect')) {
                                $(_element).on('switchChange.bootstrapSwitch', function (event, state) {
                                    pmUtilLoading.startDialog();

                                    window.location.href = $(_element).data('redirect');
                                });
                            }

                            /* Bootbox */
                            if ($(_element).data('bootbox')) {
                                $(_element).on('switchChange.bootstrapSwitch', function (event, state) {
                                    if (!$(_element).data('initalized')) {
                                        var callbals =
                                            $(_element).pmBootbox(
                                                {
                                                    callback: {
                                                        load: function () {
                                                        },
                                                        success: function () {
                                                            window.location.reload(true);
                                                        },
                                                        cancel: function () {
                                                            $(_element).bootstrapSwitch('state', true, true);
                                                        },
                                                        init: function () {

                                                        }
                                                    }
                                                }
                                            );

                                        $(_element).data('initalized', true);
                                    }

                                    $(_element).click();

                                    return false;
                                });
                            }
                        }
                    };
                }();

                core.init();
            });
        };

        if (typeof jQuery().bootstrapSwitch === "undefined") {
            alert('Bootstrap Switch not loaded');
        } else {
            pmBootstrapSwitchInit();
        }

        return this;
    };

}(jQuery));