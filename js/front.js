$(document).ready(function(){
    $('.map-select').click(function(){
        $('.map-select').removeClass('active');
        $(this).addClass('active');

        if ($(this).attr('id') == 'mapCat') {
            $('#Selecao').addClass('active');
        } else {
            $('#Selecao').removeClass('active');
        }
    });

    $('#config-drawer').click(function(){
        $('.right-drawer').removeClass('not-visible');
        $('.right-drawer').removeClass('is-visible');

        $('.mdl-layout__obfuscator').addClass('is-visible');
    });



    $('.mdl-layout__obfuscator').click(function() {
        $('.right-drawer').addClass('not-visible');
        // $(this).removeClass('is-visible');
    });
});
