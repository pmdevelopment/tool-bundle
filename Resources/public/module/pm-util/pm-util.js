/**
 * Utilities: Loading
 *
 * @type {{start, stop}}
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
            showModal: true
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
        }
    }
}();

/**
 * Utilities
 *
 * @type {{initBootbox, init}}
 */
var pmUtil = function () {
    "use strict";

    return {
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