/**
 * Created by sjoder on 18.05.15.
 */
var pmImageUpload = function () {
    "use strict";

    return {
        /**
         * Load Style
         *
         * @param path
         */
        getStyle: function (path) {
            if (document.createStyleSheet) {
                document.createStyleSheet(path);
            }
            else {
                $("head").append("<link rel='stylesheet' href='" + path + "' type='text/css' media='screen' />");
            }
        },
        /**
         * Init
         */
        init: function () {
            var upload = $('form').find('input.pm-imageupload-input');
            if (upload) {
                this.getStyle("/bundles/pmtool/pm-imageupload/css/pm-imageupload.css");
                var holder = $('<div id="pm-imageupload-holder"></div>');

                if (0 < upload.parent().find('.form-group').length) {
                    /**
                     * Bootstrap Form Group
                     */
                    var label = $('<label>Bild:</label>');
                    label.attr('class', upload.parent().find('.form-group > label').first().attr('class'));

                    var widget = $('<div></div>').append(holder);
                    widget.attr('class', upload.parent().find('.form-group > div').first().attr('class'));

                    var formGroup = $('<div class="form-group"></div>').append(label).append(widget);
                    formGroup.insertAfter(upload);
                } else {
                    /**
                     * Default Form
                     */
                    holder.insertAfter(upload);
                }

                this.initHolder(upload);
            }
        },
        /**
         * Init Holder
         */
        initHolder: function (upload) {
            var holder = document.getElementById('pm-imageupload-holder');

            if (0 < upload.val().length) {
                holder.style.background = 'url(' + upload.val() + ') no-repeat center';
                holder.style.backgroundSize = 'contain';
            }

            holder.ondragover = function () {
                $(this).addClass('hover');
                return false;
            };
            holder.ondragend = function () {
                this.removeClass('hover');
                return false;
            };
            holder.ondrop = function (e) {
                this.className = '';
                e.preventDefault();

                var file = e.dataTransfer.files[0], reader = new FileReader();

                reader.onload = function (event) {
                    if ('data:image/' === event.target.result.substr(0, 11)) {
                        holder.style.background = 'url(' + event.target.result + ') no-repeat center';
                        holder.style.backgroundSize = 'contain';

                        upload.val(event.target.result);
                        upload.parent().find('input.pm-imageupload-name').val(file.name);
                    }
                };


                reader.readAsDataURL(file);

                return false;
            };
        }
    };
}();

pmImageUpload.init();