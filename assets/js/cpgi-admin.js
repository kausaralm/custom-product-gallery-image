jQuery(document).ready(function ($) {
    $('#upload_image_button').on('click', function (e) {
        e.preventDefault();

        var imageFrame;
        if (imageFrame) {
            imageFrame.open();
            return;
        }

        imageFrame = wp.media({
            title: 'Select or Upload an Image',
            button: { text: 'Use this image' },
            multiple: false
        });

        imageFrame.on('select', function () {
            var attachment = imageFrame.state().get('selection').first().toJSON();
            $('#cpgi_image').val(attachment.id);
            $('#cpgi_image_preview').attr('src', attachment.url).show();
        });

        imageFrame.open();
    });
});


