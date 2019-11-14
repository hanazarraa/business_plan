jQuery(document).ready(function() {

    var nb_commentaires = parseInt($('#nb-commentaires').html());

    $('#commentaires .submit-note').click(function(e) {
        e.preventDefault();

        $('#commentaires .submit-note').css('opacity', '0.4');
        $('#commentaire').css('opacity', '0.4');

        var redacteur = $('#redacteur').val();
        var commentaire = $('#commentaire').val();
        var id_tableau = $('#id_tableau').val();

        jQuery.ajax({
            type: "POST",
            url: base_url + "espace_client/espace_client/ajax_commentaires",
            data: { 'redacteur': redacteur, 'commentaire': commentaire, 'id_tableau': id_tableau },
            dataType: "json",
            success: function(data) {
                if (data.result == 'ok') {
                    $('#commentaires-container').append(data.commentaire);
                    $('#commentaire').val('');

                    alertify.success('Commentaire ajoutÃ©');

                    nb_commentaires++;
                    $('#nb-commentaires').html(nb_commentaires);
                }

                if (data.redacteur_empty) {
                    $('#redacteur').addClass('erreur');
                } else {
                    $('#redacteur').removeClass('erreur');
                }

                if (data.commentaire_empty) {
                    $('#commentaire').addClass('erreur');
                } else {
                    $('#commentaire').removeClass('erreur');
                }

                $('#commentaires .submit-note').css('opacity', '1');
                $('#commentaire').css('opacity', '1');
            }
        });
    });


    $('#notes .submit-note').click(function(e) {
        e.preventDefault();

        $('#notes .submit-note').css('opacity', '0.4');
        $('#note').css('opacity', '0.4');

        var note = $('#note').val();
        var id_tableau = $('#id_tableau').val();

        jQuery.ajax({
            type: "POST",
            url: base_url + "espace_client/espace_client/ajax_notes",
            data: { 'note': note, 'id_tableau': id_tableau },
            dataType: "json",
            success: function(data) {
                if (data.result == 'ok') {
                    $('#note').val(data.note);

                    alertify.success('Note modifiÃ©e');
                }

                $('#notes .submit-note').css('opacity', '1');
                $('#note').css('opacity', '1');
            }
        });
    });


    $('#show-notes').click(function(e) {
        e.preventDefault();
        var visible = $('#notes').is(':visible');
        if (!visible) {
            $('#notes').slideDown();
            $('.liens-sociaux').slideUp();
        } else {
            $('#notes').slideUp();
            $('.liens-sociaux').slideDown();
        }
    });

    $('#hide-notes').click(function(e) {
        e.preventDefault();

        $('#notes').slideUp();
        $('.liens-sociaux').slideDown();
    });


    $('#show-commentaires').click(function(e) {
        e.preventDefault();

        $('#commentaires').animate({
            right: '0'
        }, 400, function() {

        });

        $('.liens-sociaux').slideUp();
    });

    $('#hide-commentaires').click(function(e) {
        e.preventDefault();

        $('#commentaires').animate({
            right: '-50%'
        }, 400, function() {

        });

        $('.liens-sociaux').slideDown();
    });

    $('body').on('click', '.delete-commentaire', function(e) {
        //$('.delete-commentaire').live('click', function(e) {
        e.preventDefault();
        var message = 'ÃŠtes-vous sÃ»r de vouloir faire cela ?';
        if (confirm(message)) {
            var id = $(this).attr('id').split('-')[2];

            jQuery.ajax({
                type: "POST",
                url: base_url + "espace_client/espace_client/ajax_delete_commentaire",
                data: 'id_commentaire=' + id,
                dataType: "json",
                success: function(data) {
                    if (data.result == 'ok') {
                        $('#commentaire-' + id).fadeOut();

                        alertify.success('Commentaire supprimÃ©');

                        nb_commentaires--;
                        $('#nb-commentaires').html(nb_commentaires);
                    }
                }
            });
        }
    });
});