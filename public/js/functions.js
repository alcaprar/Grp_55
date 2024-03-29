function doValidation(id, actionUrl, formName) {

    function showErrors(resp) {
        $("#" + id).parent().parent().find(':input').removeClass('_errori');
        if (resp[id]) {
            $("#" + id).parent().parent().find(':input').addClass('_errori');
        }
        $("#" + id).parent().parent().find('.errors').html(' ');
        $("#" + id).parent().parent().find('.errors').html(getErrorHtml(resp[id]));
    }

    $.ajax({
        type : 'POST',
        url : actionUrl,
        data : $("#" + formName).serialize(),
        dataType : 'json',
        success : showErrors
    });
}

function getErrorHtml(formErrors) {
    if (( typeof (formErrors) === 'undefined') || (formErrors.length < 1))
        return;

    var out = '<ul>';
    var firstKey = getFirstKey(formErrors);
    out += '<li>' + formErrors[firstKey] + '</li>';
    out += '</ul>';
    return out;
}

function getFirstKey(data) {
    for (var prop in data)
        return prop;
}

function searchDelay(box, callback, delay, baseUrl, actionUrl, div) {
    var timer = null;
    //caso di generico input, anche con copia/incolla o autocomplete
    $(box).on('input',function() {
        var searchKeyword = $(this).val();
        if (timer) {
            window.clearTimeout(timer);
        }
        timer = window.setTimeout( function() {
            timer = null;
            callback(baseUrl, actionUrl, searchKeyword);
        }, delay );
    });
    //necessario per effettuare una ricerca anche in caso di backspace o delete
    $(box).on('keyUp', function(e){
        if(e.keyCode == 8 || e.keyCode == 46){
            var searchKeyword = $(this).val();
            if (timer) {
                window.clearTimeout(timer);
            }
            timer = window.setTimeout( function() {
                timer = null;
                callback(baseUrl, actionUrl, searchKeyword);
            }, delay );
        }
    });
    box = null;
}

function liveSearch(baseUrl, actionUrl, searchKeyword){
        if (searchKeyword.length >= 1) {
            $('div#dropdown').slideDown(200);
            $.post(actionUrl, { query: searchKeyword }, function(data) {
                $('ul#content').empty();
                $.each(data, function() {
                    $('ul#content').append('<li><a href="' + baseUrl + this.id + '">' + this.Nome + '</a></li>');
                });
            }, "json");
        }
}

function liveSearchMalfunction(baseUrl, actionUrl, searchKeyword){
        if (searchKeyword.length >= 1) {
            $('div#dropdownmalf').slideDown(200);
            $.post(actionUrl, { query: searchKeyword }, function(data) {
                $('ul#contentmalf').empty();
                $.each(data, function() {
                    $('ul#contentmalf').append('<li><a href="' + baseUrl + searchKeyword + '">' + this.Nome + '</a></li>');
                });
            }, "json");
        }
}

function filterMalf(input, ul){
    $(input).on('input', function () {
        var query = $(input).val().split(" ")[0];
        $(ul).find('li').hide();
        $(ul).find('li').filter(":contains('" + query + "')").show();
    });
}

function faqHandler(){
    $(".faq-q").click( function () {
        var container = $(this).parents(".faq-c");
        var answer = container.find(".faq-a");
        var trigger = container.find(".faq-t");

        answer.slideToggle(200);

        if (trigger.hasClass("faq-o")) {
            trigger.removeClass("faq-o");
        }
        else {
            trigger.addClass("faq-o");
        }
    });
}

function addUser(){
    var trCent = $('td#centri-label').parent();
    var trCatAdd = $('td#AddCategory-label').parent();
    var trCat = $('td#Categorie-label').parent();
    if($('select#Ruolo').val()=='tecnico'){
        trCat.hide();
        trCatAdd.hide();
    } else{
        trCent.hide();
    }
    $('select#Ruolo').on('change', function() {
        if(this.value =='tecnico'){
            trCent.show("slow");
            trCat.hide("slow");
            trCatAdd.hide("slow");
        } else{
            trCent.hide("slow");
            trCat.show("slow");
            trCatAdd.show("slow");
        }
    })
}

function showMalf(id){
    $(function(){
        $('.boxMalf#'+ id).toggle(); // Toggle the element's visibility
    });
}

function showSolution(id){
    $(function(){
        $('.boxSolution#'+id).toggle(); // Toggle the element's visibility
    });
}

function chiudiOverlay(){
    $('#overlay').fadeOut('fast');
    $('.box').hide();
    $('.box-comp').hide();
}

function mostraMalfunzionamento(id){
    $('#overlay').fadeIn('fast');
    $('.box#malf'+id).fadeIn('slow');
}

function mostraComponente(id){
    $('#overlay').fadeIn('fast');
    $('.box#comp'+id).fadeIn('slow');
}

function showDivRicerca(pid){
    var id = pid.split("-")[1];
    showDivSol(id);
    showDivMalf(id)
}

function showDivMalf(id){
    $('#malfBreve-'+id).slideToggle();
    $('#malfLungo-'+id).slideToggle();
}

function showDivSol(id){
    $('#solBreve-'+id).slideToggle();
    $('#solLungo-'+id).slideToggle();
}