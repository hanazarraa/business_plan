jQuery(document).ready(function() {
    $('body').on('change', '.tableau input', function(e) {
        //$('.tableau input').live('change', function(e) {
        var no_format_zero = $('#no_format_zero').length;

        if (!no_format_zero) {
            $(this).val(number_format($(this).val().replace(",", "."), 2, '.', ' '));
        } else {
            if (parseInt($(this).val()) != 0) {
                $(this).val(number_format($(this).val().replace(",", "."), 2, '.', ' '));
            } else {
                $(this).val('');
            }
        }
    });

    $(document).click(function(e) {
        //$(document).live('click', function(e) {
        //console.log(e.target.nodeName);
        if (e.target.nodeName != 'TD' && e.target.nodeName != 'INPUT') {
            $('tr').removeClass('active');
        }
    });

    $('body').on('focus', '.tableau input', function(e) {
        //$('.tableau input').live('focus', function(e) {
        $('tr').removeClass('active');
        $(this).parents('td').parents('tr').addClass('active');
    });

    $('body').on('click', '.tableau td', function(e) {
        //$('.tableau td').live('click', function(e) {
        $('tr').removeClass('active');
        $(this).parents('tr').addClass('active');
    });

    /*$('.tableau input').live('blur', function(e) {
    	$(this).parents('td').parents('tr').removeClass('active');
    });*/

    $('body').on('mouseenter', '.tableau td:not(.separateur):not(.not-survole)', function(e) {
        //$('.tableau td').not('.separateur').not('.not-survole').bind('mouseenter', function(e) {
        $('.tableau td').removeClass('active'); // on enlÃ¨ve d'abord toutes les classes actives

        var td_selectionnee = $(this);
        var tr_first_child_th = $(this).parents('.tableau').find('tr:first-child th');

        //$(this).removeClass('active');

        var nb_cols = 0;
        tr_first_child_th.each(function() {
            var colspan_temp = $(this).attr('colspan');
            if (colspan_temp != undefined) {
                nb_cols += colspan_temp;
            } else {
                nb_cols++;
            }
        });

        if (nb_cols == 0) {
            tr_first_child_th.each(function() {
                var colspan_temp = $(this).attr('colspan');
                if (colspan_temp != undefined) {
                    nb_cols += colspan_temp;
                } else {
                    nb_cols++;
                }
            });
        }

        if (nb_cols != 0) {
            //var index = $(this).parent('tr').find('td').index(this);
            var index = 0;
            var i = 0;
            $(this).parent('tr').find('td').each(function(e) {
                if ($(this).is(td_selectionnee)) {
                    index = i;
                }
                var colspan_temp = $(this).attr('colspan');
                if (colspan_temp != undefined) {
                    i += colspan_temp;
                } else {
                    i++;
                }
            });

            var i = 0;
            $(this).parents('.tableau').not('.no-highlight').find('tr td').each(function() {
                if (i == index) {
                    $(this).addClass('active');
                }
                if (i == nb_cols - 1) {
                    i = 0;
                } else {
                    var colspan = $(this).attr('colspan');
                    if (colspan != undefined) {
                        i += colspan;
                        //$(this).removeClass('active');

                        if (i >= nb_cols - 1) {
                            i = 0;
                        }
                    } else {
                        i++;
                    }
                }
            });
        }
    });

    $('body').on('mouseleave', '.tableau', function(e) {
        //$('.tableau').bind('mouseleave', function(e) {
        $('.tableau td').removeClass('active');
    });


    /* DUPLICATION PAR GLISSE */
    var bg_input = $('.tableau .type-text').css('background-color');

    var data_glisse = null;

    $('body').on('mousedown', '.duplicate', function(e) {
        //$('.duplicate').live('mousedown',function(e){
        //e.preventDefault();
        data_glisse = $(this).children('.type-text').val();
    });

    $('body').on('focus', 'td .type-text', function(e) {
        //$('td').children('.type-text').bind('focus',function(e){
        $(this).select();
    });


    $(document).bind('mouseup', function(e) {
        data_glisse = null;
        e.preventDefault();
        $('td').css('background', 'none');
        $('td').children('.type-text').animate({ backgroundColor: bg_input },
            function() {

            }
        );
    });

    $('body').on('mouseenter', '.duplicate', function(e) {
        //$(".duplicate").live('mouseenter',function(e){
        if (data_glisse != null) {
            $(this).children('.type-text').val(data_glisse);
            $(this).children('.type-text').trigger('change'); // utilisÃ© pour mettre Ã  jour le total en temps rÃ©el
            $(this).css('background', '#dafff7');
            $(this).children('.type-text').css('background-color', '#dafff7');
        }
    });


    /* BOUTON VALIDER QUI SUIT L'ECRAN */
    $('#validation_mouvante').fadeOut();
    $(window).scroll(function() {
        if ($(window).scrollTop() > 110) {
            if ($('#validation_mouvante').length == 0) {
                $('#validation_mouvante').fadeIn();
            }
            $('#validation_mouvante').fadeIn();

            //$('#validation_mouvante').css("top", $(window).scrollTop()+180);
        } else {
            $('#validation_mouvante').fadeOut();
        }
    });

    /* CALCUL DU TOTAL EN TEMPS REEL */
    $('body').on('change', '.sommeÃ¨js input', function(e) {
        //$('.somme-js').children('input').bind('change', function(e) {
        var id = $(this).parent().attr('id').split('-');
        var dep = id[1];
        var per = id[2];
        id = id[1] + '-' + id[2];

        var id_input = $(this).attr('id').split('-');

        var somme = 0;
        var val = 0;
        $('.somme-' + id).children('input').each(function(e) {
            val = $(this).val().replace(",", ".").replace(' ', '');
            $(this).val(val);
            somme += parseFloat(val);
        });

        somme = number_format(somme, 2, '.', ' ');
        $('#total-' + id).html(somme);

        if ($('.totalgeneral').length) {
            somme = 0;
            $('.totalprovisoire-' + per).each(function(e) {
                val = parseFloat($(this).html().replace(",", ".").replace(' ', ''));
                somme += val;
            });

            somme = number_format(somme, 2, '.', ' ');
            $('#totalgeneral-' + per).html(somme);
        }

        if ($('.totalgeneraldep').length) {
            somme = 0;
            $('.totalprovisoiredep-' + dep).each(function(e) {
                val = parseFloat($(this).html().replace(",", ".").replace(' ', ''));
                somme += val;
            });

            somme = number_format(somme, 2, '.', ' ');
            $('#totalgeneraldep-' + dep).html(somme);
        }

        if ($('.totalrubrique').length) {
            somme = 0;
            $('.sommerubrique-' + id_input[1]).children('input').each(function(e) {
                val = parseFloat($(this).val().replace(",", ".").replace(' ', ''));
                somme += val;
            });

            somme = number_format(somme, 2, '.', ' ');
            $('#totalrubrique-' + id_input[1]).html(somme);
        }

        if ($('.totalgeneralannee').length) {
            somme = 0;
            $('.totalgeneral').each(function(e) {
                val = parseFloat($(this).html().replace(",", ".").replace(' ', ''));
                somme += val;
            });

            somme = number_format(somme, 2, '.', ' ');
            $('.totalgeneralannee').html(somme);
        }
    });

    $('body').on('change', '.salairemensuel', function(e) {
        //$('.salairemensuel').bind('change', function(e) {

        var periode = $(this).attr('id').split('-')[1];
        var id_salarie = $(this).attr('id').split('-')[2];
        var id_departement = $(this).attr('id').split('-')[3];
        var id = periode + '-' + id_salarie + '-' + id_departement;

        //console.log('#etp-'+periode+'-'+id_salarie);

        var val = $('#etp-' + periode + '-' + id_salarie).trigger('change');

        //console.log(val);

        //$('#etp-'+id_salarie+'-'+periode).val(val);


    });


    /* JS TABLEAUX ETP */
    $('body').on('change', '.etpmoyen input', function(e) {
        //$('.etpmoyen').children('input').bind('change', function(e) {
        var val = 0;
        var somme = new Array();
        var sommeetptotale = new Array();
        var sommemassesalarialetotale = new Array();
        var somme_etp = new Array(); //var requete_ajax = false;

        var periode = $(this).parent().attr('id').split('-')[1];
        var id_salarie = $(this).parent().attr('id').split('-')[2];
        var id_departement = $(this).parent().attr('id').split('-')[3];
        var id = periode + '-' + id_salarie + '-' + id_departement;

        valeur = str_replace(' ', '', $('#valeur-' + id_salarie + '-' + periode).val());

        jQuery.ajax({
            type: "POST",
            url: base_url + 'espace_client/salarie/ajax_nouveau_cout_annuel/',
            dataType: "json",
            data: { 'id': id_salarie, 'periode': periode, 'salaire': valeur, 'etp': $(this).val() },
            success: function(data) {
                //console.log(data);
                $('#massesalariale-' + id).html(number_format(data, 2, '.', ' '));


                $('.etpmoyen').children('input').each(function(e) {
                    val = $(this).val().replace(",", ".");
                    val = number_format(val, 2, '.', ' ');
                    $(this).val(val);

                    var periode = $(this).parent().attr('id').split('-')[1];
                    var id_salarie = $(this).parent().attr('id').split('-')[2];
                    var id_departement = $(this).parent().attr('id').split('-')[3];
                    var id = periode + '-' + id_salarie + '-' + id_departement;

                    var massesalariale = $('#massesalariale-' + id).html();

                    if (somme[periode + '' + id_departement] == undefined) {
                        somme[periode + '' + id_departement] = 0;
                    }

                    if (somme_etp[periode + '' + id_departement] == undefined) {
                        somme_etp[periode + '' + id_departement] = 0;
                    }

                    if (sommeetptotale[periode] == undefined) {
                        sommeetptotale[periode] = 0;
                    }

                    if (sommemassesalarialetotale[periode] == undefined) {
                        sommemassesalarialetotale[periode] = 0;
                    }


                    massesalariale = str_replace(' ', '', massesalariale);
                    temp_somme_etp = str_replace(' ', '', $(this).val());


                    console.log(parseFloat(temp_somme_etp));

                    somme[periode + '' + id_departement] += parseFloat(jQuery.trim(massesalariale));
                    somme_etp[periode + '' + id_departement] += parseFloat(jQuery.trim(temp_somme_etp));
                    sommeetptotale[periode] += parseFloat(jQuery.trim(temp_somme_etp));;
                    sommemassesalarialetotale[periode] += parseFloat(jQuery.trim(massesalariale));;
                    //$('#massesalariale-'+id).html(number_format(massesalariale,2,'.',' '));

                    $('#massesalarialetotale-' + periode + '-' + id_departement).html(number_format(somme[periode + '' + id_departement], 2, '.', ' '));
                    $('#etptotale-' + periode + '-' + id_departement).html(number_format(somme_etp[periode + '' + id_departement], 2, '.', ' '));
                    $('#sommeetptotale-' + periode).html(number_format(sommeetptotale[periode], 2, '.', ' '));
                    $('#sommemassesalarialetotale-' + periode).html(number_format(sommemassesalarialetotale[periode], 2, '.', ' '));

                });

                //console.log(sommeetptotale);
            }
        });




        //requete_ajax = false;
    });

    /* ETP DETAILLE */
    $('body').on('change', '.etpdetaille input', function(e) {
        //$('.etpdetaille').children('input').bind('change', function(e) {
        var id = $(this).attr('id').split('-');
        var id_salarie = id[1];

        var val = 0;
        var somme = 0;
        var moyenne = 0;
        $('.etpdetaille-' + id_salarie).children('input').each(function(e) {
            $(this).val(number_format($(this).val(), 2, '.', ' '));
            val = parseFloat($(this).val().replace(",", ".").replace(' ', ''));
            somme += val;
        });

        moyenne = round(somme / 12, 2);
        moyenne = number_format(moyenne, 2, '.', ' ');
        $('#moyenneetp-' + id_salarie + ' .ventilate-total').html(moyenne);
    });

    /* DESACTIVER TOUCHE ENTREE DANS LES FORMULAIRES */
    //Bind this keypress function to all of the input tags
    $("input").keypress(function(evt) {
        //Deterime where our character code is coming from within the event
        var charCode = evt.charCode || evt.keyCode;
        if (charCode == 13) { //Enter key's keycode
            return false;
        }
    });

    /* VERIFICATION DES DONNEES NON VALIDEES */

    var a_sauvegarder = false;
    $('body').on('change', 'input:not(.no_alert_a_sauvegarder), select:not(.no_alert_a_sauvegarder)', function(e) {
        //$('input, select').bind('change', function(e) {
        a_sauvegarder = true;
    });

    $('body').on('click', 'a:not(.no_alert_a_sauvegarder)', function(e) {
        //$('a').not('.no_alert_a_sauvegarder').click(function(e) {


        if (a_sauvegarder == true && !$(e.target).hasClass('no_alert_a_sauvegarder')) {


            console.log(e.target);

            if (!confirm('Des donnÃ©es ont Ã©tÃ© saisies sans avoir Ã©tÃ© sauvegardÃ©es. Pensez Ã  cliquer sur "Valider". Voulez vous tout de mÃªme quitter cette page ?')) {
                e.preventDefault();
            }
        }
    });


    /* CALCUL CA > UNITES VENDUES ET VICE VERSA */
    if ($('#type_affichage').length) {
        var type_affichage = $('#type_affichage').val();
    }

    $('body').on('change', '.js-unites', function(e) {
        //$('.js-unites').change(function(e) {
        var id = $(this).attr('id').split('-');
        var periode = id[2];
        if (type_affichage == 'global') {
            var mois = id[2] * 12;
        } else {
            var mois = id[2];
        }
        var id_produit = id[1];
        var unites = str_replace(' ', '', $(this).val());

        jQuery.ajax({
            type: "POST",
            url: base_url + 'espace_client/vente/ajax_get_ca_by_unites',
            dataType: "json",
            data: { 'id_produit': id_produit, 'mois': mois, 'unites': unites },
            success: function(data) {
                $('#unite-' + id_produit + '-' + periode).val(data.unites);
                $('#ca-' + id_produit + '-' + periode).val(data.ca);

                $('#ca-' + id_produit + '-' + periode).trigger('change');
            }
        });
    });

    $('body').on('change', '.js-ca', function(e) {
        //$('.js-ca').change(function(e) {
        var id = $(this).attr('id').split('-');
        var periode = id[2];
        if (type_affichage == 'global') {
            var mois = id[2] * 12;
        } else {
            var mois = id[2];
        }
        var id_produit = id[1];
        var ca = str_replace(' ', '', $(this).val());

        jQuery.ajax({
            type: "POST",
            url: base_url + 'espace_client/vente/ajax_get_unites_by_ca',
            dataType: "json",
            data: { 'id_produit': id_produit, 'mois': mois, 'ca': ca },
            success: function(data) {
                if (data.unites != null) {
                    $('#unite-' + id_produit + '-' + periode).val(data.unites);
                }
                $('#ca-' + id_produit + '-' + periode).val(data.ca);
            }
        });
    });

    $('.navigate input').keydown(function(e) {


        // if (e.keyCode == 37) { //move left or wrap

        //$(":td:eq(" + ($(":td").index(this) + -1) + ")").find('input').focus();
        // active = (active>0)?active-1:active;
        //  }
        if (e.keyCode == 38) { // move up
            index = $(this).parent('td').index();

            index_initial = $(this).parent('td').parent('tr').children('td').size();
            index_finale = $(this).parent('td').parent('tr').prev('tr').children('td').size();

            console.log(index_initial);
            console.log(index_finale);

            if (index_finale > index_initial) {
                index = index + 1;
            } else if (index_finale < index_initial) {
                index = index - 1;
            }

            $(this).parent('td').parent('tr').prev('tr').children('td:eq(' + index + ')').find('input').focus()

        }
        //  if (e.keyCode == 39) { // move right or wrap
        //alert('right');
        //$(":td:eq(" + ($(":td").index(this) + 1) + ")").find('input').focus();
        //active = (active<(columns*rows)-1)?active+1:active;
        //   }
        if (e.keyCode == 40) { // move down
            index = $(this).parent('td').index();

            index_initial = $(this).parent('td').parent('tr').children('td').size();
            index_finale = $(this).parent('td').parent('tr').next('tr').children('td').size();
            console.log(index_initial);
            console.log(index_finale);

            if (index_finale > index_initial) {
                index = index + 1;
            } else if (index_finale < index_initial) {
                index = index - 1;
            }

            r = $(this).parent('td').parent('tr').next('tr').children('td:eq(' + index + ')').find('input').focus();


            // A finir!!!
            /*if(r.length == 0)
            {
            	$(this).parent('td').parent('tr').next('tr').children('td:eq(' +index+ ')').html('ddd');
            }*/

        }



    });



    /* EXPORT DES TABLEAUX AU FORMAT EXCEL */
    $('body').on('click', '#export-excel', function(e) {
        //$('#export-excel').click(function(e) {
        e.preventDefault();

        jQuery.ajax({
            type: "POST",
            url: base_url + 'frontoffice/ajax_export_excel',
            dataType: "json",
            success: function(data) {
                window.open($(location).attr('href'));
            }
        });
    });


    //	$('.masquer-tr').hide();
    //	$('.hideshow').live('click', function(e) {
    //		e.preventDefault();
    //		var id = $(this).attr('id').split('-');
    //		id = id[1];
    //		if(!$('.masquertr-'+id).is(':visible')) {
    //			$('.masquertr-'+id).show();
    //		}else {
    //			$('.masquertr-'+id).hide();
    //		}
    //
    //	});

    $('.masquertr').click(function(e) {
        e.preventDefault();
        var id = $(this).attr('id').split('-')[1];
        $('.tr-' + id).toggle();
        $(this).find('span').toggle();
    });

    $(window).scroll(function() {
        if ($(window).scrollTop() > 115) {
            $('.menu-business-plan-2').css('position', 'fixed');
            $('.menu-business-plan-2').css('top', '0px');

            $('.lien-home-2').fadeIn('slow');
        }

        if ($(window).scrollTop() < 120) {
            $('.menu-business-plan-2').css('position', 'absolute');
            $('.menu-business-plan-2').css('top', '115px');

            $('.lien-home-2').hide();
        }
    });

    /* Taille des inputs adaptÃ©e Ã  leur contenu */
    $('td input').each(function(e) {
        var input_length = $(this).val().length;
        if (input_length == 0) {
            input_length = 4;
        }
        $(this).attr('size', input_length);
    });

    $('body').on('keydown', 'td input', function(e) {
        //$('td input').live('keydown', function(e) {
        var input_length = $(this).val().length;
        if (input_length == 0) {
            input_length = 4;
        }
        $(this).attr('size', input_length);
    });

    $('body').on('change', 'td input', function(e) {
        //$('td input').live('change', function(e) {
        var input_length = $(this).val().length;
        if (input_length == 0) {
            input_length = 4;
        }
        $(this).attr('size', input_length);
    });

    $('body').on('submit', 'form.ventilation', function(e) {
        e.preventDefault();

        var ventilation_class = $('#ventilation_class').val();
        var ventilation_mode = $('#ventilation_mode').val();
        var global_amount = parseFloat($(this).find('#montant').val().replace(",", ".").replace(' ', ''));

        if (ventilation_mode == 'sum') {
            var monthly_amount = round(global_amount / 12, 2);
        } else {
            var monthly_amount = round(global_amount, 2);
        }

        monthly_amount = number_format(monthly_amount, 2, '.', ' ');

        $('tr.' + ventilation_class + ' input').val(monthly_amount);

        if ($('tr.' + ventilation_class + ' .ventilate-total').length) {
            $('tr.' + ventilation_class + ' .ventilate-total').html(number_format(global_amount, 2));
        }

        $('.modal').modal("hide");

        $('tr.' + ventilation_class + ' input').trigger('change');
    });
});