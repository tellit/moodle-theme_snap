M.theme_cass.dndupload = M.course_dndupload;

var main_init = M.course_dndupload.init;

M.theme_cass.dndupload.init = function(Y, options) {

    var self = this;
    this.init = main_init;

    // Rebuild file handlers without annoying label handler.
    var extensions = ['gif', 'jpe', 'jpg', 'jpeg', 'png', 'svg', 'svgz', 'webp', 'mp3'];
    var newfilehandlers = [];
    for (var h in options.handlers.filehandlers) {
        var handler = options.handlers.filehandlers[h];
        if (handler && handler.module) {
            // Prevent label img dialog from showing.
            if (handler.module !== 'label' || extensions.indexOf(handler.extension.toLowerCase()) === -1) {
                newfilehandlers.push(handler);
            }
        }
    }
    options.handlers.filehandlers = newfilehandlers;

    this.init(Y, options);

    $('.js-cass-drop-file').change(function() {
        var sectionnumber = $(this).attr('id').replace('cass-drop-file-', '');
        var section = Y.one('#section-'+sectionnumber);

        var file;
        for (var i = 0; i < this.files.length; i++) {
            // Get file and trigger upload.
            file = this.files.item(i);
            self.handle_file(file, section, sectionnumber);
        }
    });
};
