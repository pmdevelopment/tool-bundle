(function ($) {
    $.fn.pmMarkdown = function (options) {

        var settings = {
            version: 170320,
            forceSync: true
        };

        settings = $.extend({}, settings, options);

        var _elements = this;

        /**
         * Core
         * @type {{debug, init}}
         */
        var core = function () {
            "use strict";

            return {
                /**
                 * Debug
                 */
                debug: function () {
                    for (var i = 0; i < arguments.length; i++) {
                        pmUtil.debug('{pmMarkdown} ' + arguments[i]);
                    }
                },
                /**
                 * Init
                 */
                init: function () {
                    core.debug('core.init()');

                    if (0 === $(_elements).length) {
                        return;
                    }

                    $(_elements).each(function () {
                        new SimpleMDE({
                            element: $(this)[0],
                            spellChecker: false,
                            forceSync: settings.forceSync
                        });
                    });
                }
            };
        }();

        core.init();
    };
}(jQuery));