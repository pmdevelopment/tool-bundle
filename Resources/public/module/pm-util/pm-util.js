/**
 * Loading
 *
 * @type {{config, count, getSpinner, start, stop, startInline}}
 */
var pmUtilLoading = function () {
    "use strict";

    return {
        config: {
            spinner: {
                id: "pm-util-loading-modal",
                color: "#fff",
                icon: "fa fa-spinner fa-spin fa-4x",
                style: "display:block;overflow: visible!important;"
            },
            showModal: true,
            inline: {
                color: "#3bc9d7",
                init: false
            }
        },
        count: 0,
        /**
         * Get Spinner
         * @returns {*|Mixed|jQuery|HTMLElement}
         */
        getSpinner: function () {
            return $('#' + this.config.spinner.id);
        },
        /**
         * Start
         */
        start: function () {
            var _self = this;

            if (0 === _self.count) {
                var body = $('body');

                var spinner = $('<div id="' + _self.config.spinner.id + '" class="modal fade in text-center" style="' + _self.config.spinner.style + '"></div>');
                var icon = $('<i></i>').attr('class', _self.config.spinner.icon).css('color', _self.config.spinner.color);

                spinner.css("top", ($(window).height() / 2) + "px");

                spinner.append(icon);

                body.append(spinner);

                if (true === _self.config.showModal) {
                    body.append($('<div class="modal-backdrop in" id="' + _self.config.spinner.id + '-modal"></div>'));
                }
            }

            _self.count++;
        },
        /**
         * Stop
         */
        stop: function () {
            var _self = this;

            if (0 < _self.count) {
                _self.count--;
            }

            if (0 === _self.count) {
                if (true === _self.config.showModal) {
                    $("#" + _self.config.spinner.id + "-modal").remove();
                }

                _self.getSpinner().remove();
            }
        },
        /**
         * Start inline
         * @param target
         * @param size
         */
        startInline: function (target, size) {
            if (false === this.config.inline.init) {
                this.config.inline.init = true;

                $('head').append($('<link rel="stylesheet" type="text/css" />').attr('href', '/bundles/pmtool/module/pm-util/css/loading.css?v=1.0.1'));
            }

            var divClass = 'pm-util-loading-inline';
            var text = 'Lädt...';
            var style = 'width:80px;margin: 60px auto;';

            if ('small' === size) {
                text = '';
                divClass += ' small';
                style = 'width:40px;margin: 5px auto;';
            }

            target.html('<div class="' + divClass + '" style="' + style + '"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke="' + this.config.inline.color + '" stroke-width="2" stroke-miterlimit="10" /></svg>' + text + '</div>');
        }
    }
}();

/**
 * pmUtil
 *
 * @type {{config, debug, initBootbox, init}}
 */
var pmUtil = function () {
    "use strict";

    return {
        config: {
            debugging: false
        },
        /**
         * Debug
         */
        debug: function () {
            if (true === this.config.debugging) {
                for (var i = 0; i < arguments.length; i++) {
                    console.log(arguments[i]);
                }
            }
        },
        /**
         * Init Bootbox
         */
        initBootbox: function () {
            var _self = this;

            $('a.delete').on('click', function () {
                var elem = $(this);

                var title = "Den Eintrag wirklich löschen?";
                if (elem.attr('title')) {
                    title = elem.attr('title');
                }

                bootbox.confirm(title, function (result) {
                    if (true === result) {
                        pmUtilLoading.start();
                        window.location.href = elem.attr('href');
                    }
                });

                return false;
            });
        },
        /**
         * Init
         */
        init: function () {
            this.initBootbox();
        }
    };
}();

$(document).ready(function () {
    pmUtil.init();
});