$(function() {
    $('#contact-form').submit(function(e) {
        e.preventDefault();

        var form = $(this);
        var formMessages = form.find('.form-message');
        var formData = form.serialize();

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: formData
        })
        .done(function(response) {
            formMessages.removeClass('error').addClass('success');
            formMessages.text(response);
            form.find('input, textarea').val('');
        })
        .fail(function(data) {
            formMessages.removeClass('success').addClass('error');
            if (data.responseText !== '') {
                formMessages.text(data.responseText);
            } else {
                formMessages.text('Oops! An error occurred and your message could not be sent.');
            }
        });
    });
});