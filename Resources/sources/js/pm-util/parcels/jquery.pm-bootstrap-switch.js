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

                            $(_element).removeClass('hidden').bootstrapSwitch();

                            if ($(_element).data('path')) {
                                $(_element).on('switchChange.bootstrapSwitch', function (event, state) {
                                    pmUtilLoading.start();

                                    $.get($(_element).data('path'), {"value": $(_element).val(), "checked": state}, function (result) {
                                        pmUtilLoading.stop();

                                        if ("" !== result) {
                                            bootbox.alert('Es ist ein Fehler aufgetreten. Bitte lade die Seite neu.');
                                        }
                                    });
                                });
                            }
                        }
                    };
                }();

                core.init();
            });
        };

        if (undefined === jQuery().bootstrapSwitch) {
            var path_bootstrap_switch = "/bundles/pmtool/vendor/bootstrap-switch/" + pmUtil.config.module.bootstrapSwitch.version;
            $("head").append("<link rel='stylesheet' href='" + path_bootstrap_switch + "/css/bootstrap3/bootstrap-switch.min.css' type='text/css' media='screen' />");

            $.get(path_bootstrap_switch + '/js/bootstrap-switch.min.js', function () {
                pmBootstrapSwitchInit();
            });
        } else {
            pmBootstrapSwitchInit();
        }

        return this;
    };

}(jQuery));