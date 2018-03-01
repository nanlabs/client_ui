Dropzone.options.fileDropzone = {
    autoProcessQueue: false,
    createImageThumbnails: false,
    dictDefaultMessage: 'Drag & Drop File Here',
    params: function() {
        return {
            location: $('select#location').val()
        };
    },
    init: function() {
        var dropzone = this;
        $('button').on('click', function() {
            dropzone.processQueue();
        });
    }
};
$('form.dropzone').addClass('bg-light');
