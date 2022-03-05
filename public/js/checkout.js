
$( document ).ready(function() {
    $(".cardnumber").mask('0000 0000 0000 0000');
    $(".year_input").mask('0000');
    // $(".xpiredatemonth").mask('00');

    var passed = true;
    var back =$(".prev");
    var next = $(".next");
    var steps = $(".step");
    var confirmbtn = $("#submit_btn");
    var steps_c = $(".step-content");
    
    next.bind("click", function() { 
       
        if (validate()){
           
            $.each( steps, function( i ) {
                if(!$(steps[-1]).hasClass('current')){
                    back.removeClass('hide_back');
                }

                if (!$(steps[i]).hasClass('current') && !$(steps[i]).hasClass('done')) {
                    $(steps[i]).addClass('current');
                    $(steps[i - 1]).removeClass('current').addClass('done');
                    return false;
                }
                    
            })     


            $.each( steps_c, function( i ) {
                if (!$(steps_c[i]).hasClass('current-step') && !$(steps_c[i]).hasClass('done-step')) {
                    $(steps_c[i]).addClass('current-step');
                    $(steps_c[i - 1]).removeClass('current-step').addClass('done-step');
                    return false;
                } 
            })  


            if($('.step:last-child').hasClass('current')){
                next.addClass('hide_back');
                confirmbtn.removeClass('hide_back');
            }  
        }
        
    });

    back.bind("click", function() { 
        if ($('.step:last-child').hasClass('current') && next.hasClass('hide_back')){
            next.removeClass('hide_back');
            confirmbtn.addClass('hide_back');
        }

        $.each( steps, function( i ) {
            if($(steps[1]).hasClass('current') ){
                back.addClass('hide_back');
            }

            if ($(steps[i]).hasClass('done') && $(steps[i + 1]).hasClass('current')) {
                $(steps[i + 1]).removeClass('current');
                $(steps[i]).removeClass('done').addClass('current');
               return false;
            }

        });   


        $.each( steps_c, function( i ) {
            if ($(steps_c[i]).hasClass('done-step') && $(steps_c[i+1]).hasClass('current-step') ) {
                $(steps_c[i + 1]).removeClass('current-step');
                $(steps_c[i]).removeClass('done-step').addClass('current-step');

                return false;
            } 
        });      
    });



    $('input').bind("keyup keydown change", function() { 
        var name = $(this).attr("name").replace("_1", "");
        $("input[name="+name+"]").val($(this).val());
    });

    $('select').bind("change", function() { 
        var name = $(this).attr("name").replace("_1", "");
        $("select[name="+name+"]").val($(this).val());
    });

    $('input[type=checkbox]').bind("change", function() { 
        var name = $(this).attr("name").replace("_1", "");
         $("input[name="+name+"]")[0].checked = true;
    });


    function validate(){
        var inputs =  $(".current-step :input");
        passed = true;
        $.each( inputs, function( i ) {

            if(inputs[i].value.trim() == '' && $.inArray(inputs[i].name ,['addrline2_1','addrline2'] ) == -1) {
                
                if ($("input[name="+inputs[i].name+"]").hasClass('is-valid'))
                    $("input[name="+inputs[i].name+"]").removeClass('is-valid');

                if ($("select[name="+inputs[i].name+"]").hasClass('is-valid'))
                    $("select[name="+inputs[i].name+"]").removeClass('is-valid');

                $("input[name="+inputs[i].name+"]").addClass('is-invalid');
                $("select[name="+inputs[i].name+"]").addClass('is-invalid');

                passed =  false;
            } else {
                if ($("input[name="+inputs[i].name+"]").hasClass('is-invalid'))
                    $("input[name="+inputs[i].name+"]").removeClass('is-invalid');

                if ($("select[name="+inputs[i].name+"]").hasClass('is-invalid'))
                    $("select[name="+inputs[i].name+"]").removeClass('is-invalid');

                $("input[name="+inputs[i].name+"]").addClass('is-valid');
                $("select[name="+inputs[i].name+"]").addClass('is-valid');
            }

            if ( (inputs[i].name == 'password_1' || inputs[i].name == 'password_confirmation_1') && inputs[i].value != '') {
                
                var regex = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/ 

                if (!regex.test(inputs[i].value)){
                    if ($("input[name="+inputs[i].name+"]").hasClass('is-valid'))
                        $("input[name="+inputs[i].name+"]").removeClass('is-valid');

                    $("input[name="+inputs[i].name+"]").addClass('is-invalid');
                    $("#validation_"+inputs[i].name).remove();
                    $("input[name="+inputs[i].name+"]").after( "<div id='validation_"+inputs[i].name+"' class='invalid-feedback'>Password must be at least 8 characters and contain one capital letter, one number and one special character.</div>" );  
                    passed =  false;
                } else {
                    if ($("input[name="+inputs[i].name+"]").hasClass('is-invalid'))
                        $("input[name="+inputs[i].name+"]").removeClass('is-invalid');

                    $("input[name="+inputs[i].name+"]").addClass('is-valid');
                    $("#validation_"+inputs[i].name).remove();

                    if ($("input[name=password_1]").val() != $("input[name=password_confirmation_1]").val() ){

                        if ($("input[name="+inputs[i].name+"]").hasClass('is-valid'))
                            $("input[name="+inputs[i].name+"]").removeClass('is-valid');

                        $("input[name="+inputs[i].name+"]").addClass('is-invalid');
                        $("#validation_"+inputs[i].name).remove();
                        $("input[name="+inputs[i].name+"]").after( "<div id='validation_"+inputs[i].name+"' class='invalid-feedback'>Password and Confirm Password does not match</div>" );  

                        passed =  false;
                    } else {
                        if ($("input[name="+inputs[i].name+"]").hasClass('is-invalid'))
                            $("input[name="+inputs[i].name+"]").removeClass('is-invalid');

                        $("input[name="+inputs[i].name+"]").addClass('is-valid');
                        $("#validation_"+inputs[i].name).remove();

                    }
                }

            }

            if (inputs[i].type =='checkbox' ){
                if (!inputs[i].checked) {
                    $("#"+inputs[i].name).addClass('is-invalid');
                     passed =  false;
                }else {
                    if ($("#"+inputs[i].name).hasClass('is-invalid'))
                        $("#"+inputs[i].name).removeClass('is-invalid');
                }
            } 
        });   

        return passed;
    }


    confirmbtn.bind("click", function() { 
       $(this).html("<div class='spinner-border text-light spinner-size' role='status'></div> Confirm");
       $(this).attr('disabled');
       
       $('#submitresult').html('');
        $.ajax({
            url: '/subscribe',
            type: 'POST',
            data: $( "#frmSubscription" ).serialize(),
            success: function (data) {
                var html_result = '';
                var json_data = data;
                if( json_data.error == true ) {
                    html_result = '<div class="alert alert-danger"><ul><li>'+json_data.message+'</li></ul></div>';
                }
                else {
                    window.location.href="/m-checkout-last" ;

                   //  html_result = '<div class="alert alert-success"><ul><li>'+json_data.message+'</li></ul></div>';
                   //  $( "#frmSubscription" ).trigger('reset');
                   //      if( $('#periodic').val().trim() == 'yearly' ) {
                   //          yearly_reset_prices();
                   //  }
                     
                   // var downloadTimer = setInterval(function(){
                        
                   //      if(timeleft <= 0) {
                   //        clearInterval(downloadTimer);
                   //       //temporary commented
                   //      }

                   //  },1000);

                }
                $('#submitresult').html(html_result);
                $('html, body').animate({ scrollTop: $("#submitresult").offset().top }, 1);

                grecaptcha.reset();
                
               $(this).removeAttr('disabled');
            },
            error: function (request, status, error) {
                if(request.status == 422) {
                    var errors = $.parseJSON(request.responseText);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    $.each(errors, function (index, value) {
                        if( index == 'errors' ) {
                            $.each(value, function (ind, val) { errorsHtml += '<li>' + val + '</li>';  });
                        }
                    });
                    errorsHtml += '</ul></div>'
                    $('#submitresult').html(errorsHtml);

                } else {
                    $('#submitresult').html('<div class="alert alert-danger">An error occurred while processing the request.</div>');
                    /*
                    if (request.responseText.message == '') {
                        $('#submitresult').html('<div class="alert alert-danger">An error occurred while processing the request.</div>');
                    } else {
                        $('#submitresult').html('<div class="alert alert-danger">'+request.responseText.message+'</div>');
                    }
                    */
                }
                $('html, body').animate({ scrollTop: $("#submitresult").offset().top }, 1);

                grecaptcha.reset();
                $(this).removeAttr('disabled');
                $(this).html("Confirm");
            }

        });
    });
}); 
    