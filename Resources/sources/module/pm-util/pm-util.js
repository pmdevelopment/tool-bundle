/**
 * Loading
 *
 * @type {{config, count, getSpinner, start, stop, startInline, startDialog, stopDialog}}
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
            },
            text: {
                loading: 'Lädt...'
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
         * @param text
         */
        startInline: function (target, size) {
            var divClass = 'pm-util-loading-inline';
            var text = this.config.text.loading;
            var style = 'width:80px;margin: 60px auto;height:80px;';

            if ('small' === size) {
                text = '';
                divClass += ' small';
                style = 'width:40px;margin: 5px auto;height:40px;';
            }

            target.html('<div class="' + divClass + '" style="' + style + '"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke="' + this.config.inline.color + '" stroke-width="2" stroke-miterlimit="10" /></svg>' + text + '</div>');
        },
        /**
         * Start Dialog
         */
        startDialog: function () {
            var element = $('<div></div>');

            bootbox.dialog({
                message: element,
                animate: false
            });

            this.startInline(element);
        },
        /**
         * Stop Dialog
         */
        stopDialog: function () {
            bootbox.hideAll();
        }
    }
}();

/**
 * pmUtil
 *
 * @type {{config, debug, debugError, debugWarning, initBootbox, initAjax, init}}
 */
var pmUtil = function () {
    "use strict";

    return {
        config: {
            debugging: false,
            ajax: {
                cache: true,
                error: 'Es ist ein schwerwiegender Fehler aufgetreten. Bitte lade die Seite erneut.'
            },
            module: {
                select2: {
                    enabled: false,
                    callback: function () {
                        $('.pm-select2').pmSelect2();
                    },
                    version: '4.0.3',
                    theme: '0.1.0-beta.9'
                },
                bootbox: {
                    enabled: false,
                    cache: '201708251230',
                    callback: function () {
                        $('.pm-bootbox').pmBootbox({
                            version: 170117
                        });
                    }
                },
                bootbox_delete: {
                    enabled: false,
                    cache: '201701171458',
                    callback: function () {
                        $('.pm-bootbox-delete').pmBootboxDelete({
                            version: 170117
                        });
                    }
                },
                bootstrapSwitch: {
                    enabled: false,
                    version: '3.3.2',
                    callback: function () {
                        $('.pm-bootstrap-switch').pmBootstrapSwitch({
                            version: 161201
                        });
                    }
                },
                simpleMde: {
                    enabled: false,
                    version: '1.11.2',
                    callback: function () {
                        $('.pm-markdown').pmMarkdown({
                            version: 170320
                        });
                    }
                }
            }
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
         * Debug with error style
         */
        debugError: function () {
            if (true === this.config.debugging) {
                for (var i = 0; i < arguments.length; i++) {
                    console.error(arguments[i]);
                }
            }
        },
        /**
         * Debug with warning style
         */
        debugWarning: function () {
            if (true === this.config.debugging) {
                for (var i = 0; i < arguments.length; i++) {
                    console.warn(arguments[i]);
                }
            }
        },
        /**
         * Init Bootbox
         */
        initBootbox: function () {
            this.debug('pmUtil.initBootbox()');
            this.debug(' -- pmUtil.initBootbox is deprecated! --');
            var _self = this;

            $('a.delete').on('click', function () {
                pmUtil.debug('pmUtil.initBootbox() a.delete_click()');

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
            }).removeClass('disabled');
        },
        /**
         * Init Ajax
         */
        initAjax: function () {
            $.ajaxSetup({
                cache: pmUtil.config.ajax.cache
            });

            if (false !== this.config.ajax.error) {
                $(document).ajaxComplete(function (event, xhr) {
                    if (undefined !== xhr.status && 500 === xhr.status) {
                        if (undefined !== bootbox) {
                            bootbox.hideAll();
                            bootbox.alert(pmUtil.config.ajax.error);
                        } else {
                            alert(pmUtil.config.ajax.error);
                        }
                    }
                });
            }
        },
        /**
         * Init
         */
        init: function () {
            this.debug('pmUtil.init()');

            this.initAjax();

            /* v2 Update: Load all Scripts yourself! */

            if (true === this.config.module.select2.enabled) {
                if (typeof $().pmSelect2 === "undefined") {
                    alert('Missing pmSelect2!');
                } else {
                    pmUtil.config.module.select2.callback();
                }
            }

            if (true === this.config.module.bootbox.enabled) {
                if (typeof $().pmBootbox === "undefined") {
                    alert('Missing pmBootbox!');
                } else {
                    pmUtil.config.module.bootbox.callback();
                }
            }

            if (true === this.config.module.bootbox_delete.enabled) {
                if (typeof $().pmBootboxDelete === "undefined") {
                    alert('Missing pmBootboxDelete!');
                } else {
                    pmUtil.config.module.bootbox_delete.callback();
                }

            }

            if (true === this.config.module.bootstrapSwitch.enabled) {
                if (typeof $().pmBootstrapSwitch === "undefined") {
                    alert('Missing pmBootstrapSwitch!');
                } else {
                    pmUtil.config.module.bootstrapSwitch.callback();
                }
            }

            if (true === this.config.module.simpleMde.enabled) {
                if (typeof $().pmMarkdown === "undefined") {
                    alert('Missing pmMarkdown!');
                } else {
                    pmUtil.config.module.simpleMde.callback();
                }
            }
        }
    };
}();

$(document).ready(function () {
    pmUtil.init();
});