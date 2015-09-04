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
    for (errorKey in formErrors) {
        out += '<li>' + formErrors[errorKey] + '</li>';
    }
    out += '</ul>';
    return out;
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
    //se la searchbox è vuota svuota anche l'elenco di risultati per nascondere il div
    $(box).on('blur', function () {
        if($(box).val() == null) $(div).find('ul').empty();
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
    })
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
                    $('ul#contentmalf').append('<li><a href="' + baseUrl + searchKeyword + '">' + this.Malfunzionamento + '</a></li>');
                });
            }, "json");
        }
}

function filterList(input, tbody){
    $(input).on('input', function () {
        $.extend($.expr[':'], {
            'containsi': function (elem, i, match, array) {
                return (elem.textContent || elem.innerText || '').toLowerCase()
                        .indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });
        var t = $(tbody);
        var query = $(input).val().split(" ")[0];
        $(t).find('tr').hide();
        $(t).find('tr').filter(":contains('" + query + "')").show();
    });
}

function filterFaq(input, div){
    $(input).on('input', function () {
        $.extend($.expr[':'], {
            'containsi': function (elem, i, match, array) {
                return (elem.textContent || elem.innerText || '').toLowerCase()
                        .indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });
        var query = $(input).val().split(" ")[0];
        $(div).find('.faq-c').hide();
        $(div).find('.faq-c').filter(":contains('" + query + "')").show();
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
    var label = $('input[type="checkbox"]').parent('label');
    if($('select#Ruolo').val()=='tecnico'){
        $('input[type="checkbox"]').hide();
        label.hide();
        $('td#Categorie-label').hide();
        $('td#AddCategory-label').hide();
        $('a.addcategory').hide();
    } else{
        $('select#centri').hide();
        $('td#centri-label').hide();
    }

    $('select#Ruolo').on('change', function() {
        if(this.value =='tecnico'){
            $('select#centri').show("slow");
            $('td#centri-label').show("slow");
            $('input[type="checkbox"]').hide("slow");
            label.hide();
            $('td#Categorie-label').hide("slow");
            $('td#AddCategory-label').hide("slow");
            $('a.addcategory').hide("slow");
        } else{
            $('input[type="checkbox"]').show("slow");
            label.show();
            $('td#Categorie-label').show("slow");
            $('td#AddCategory-label').show("slow");
            $('a.addcategory').show("slow");
            $('select#centri').hide("slow");
            $('td#centri-label').hide("slow");
        }
    })
}

function showDiv(i){
    $(function(){
        $(i).hover(function() { // When hovering
            $(i).parent().find('div').toggle(); // Toggle the element's visibility
        });
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