$( "#apply_discount" ).keyup(function() {
    if( $('#periodic').val().trim() == 'yearly' ) {
        //if( $(this).val().trim() == 'FCFAN19' ) {
        if( $(this).val().trim() == 'FCFANS19' ) {
            $('#discount_indicator').html('<i class="fas fa-check text-success" style="margin-left:5p;x"> </i>');
            //$('.sub_total').text('$100');
            $('.sub_total').text('$106.00');
            //$('#tax_applied').text('$6');
            $('.total_due').text('$106.00');
        } else {
            yearly_reset_prices();
        }
    }
});

function yearly_reset_prices() {
    $('#discount_indicator').html('');
    //$('.sub_total').text('$120');
    $('.sub_total').text('$127.20');
    //$('#tax_applied').text('$7.20');
    $('.total_due').text('$127.20');
}

function subscribe() {
    $('#btnPlaceOrder').attr('disabled');
    $('#loading_img').html('<img src="'+base_url+'/img/loading.gif" width="32">');

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
                html_result = '<div class="alert alert-success"><ul><li>'+json_data.message+'</li></ul></div>';
                $( "#frmSubscription" ).trigger('reset');
                if( $('#periodic').val().trim() == 'yearly' ) {
                    yearly_reset_prices();
                }

                  $('#thankYou').modal({ backdrop: 'static', keyboard: false }); //temporary commented
                  $('#thankYou').modal('show', {backdrop: 'static', keyboard: false}); //temporary commented
                  var timeleft = 15;
                  var downloadTimer = setInterval(function(){
                    timeleft--;
                    document.getElementById("countdowntimer").textContent = timeleft;
                    if(timeleft <= 0) {
                      clearInterval(downloadTimer);
                      //window.location.href="/videos" //temporary commented
                      window.location.href="/songs" //temporary commented
                    }
                  },1000);

            }
            $('#submitresult').html(html_result);
            $('html, body').animate({ scrollTop: $("#submitresult").offset().top }, 1);

            grecaptcha.reset();
            $('#btnPlaceOrder').removeAttr('disabled');
            $('#loading_img').html('');
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
            $('#btnPlaceOrder').removeAttr('disabled');
            $('#loading_img').html('');
        }

    });


}

function submitInfusionsoftForm() {
    var inf_field_FirstName = $('#inf_field_FirstName').val().trim();
    var inf_field_LastName = $('#inf_field_LastName').val().trim();
    var inf_field_Email = $('#inf_field_Email').val().trim();

    if (inf_field_FirstName  == '') {
        alert('Firstname is required.');
    }
    else if (inf_field_LastName  == '') {
        alert('Lastname is required.');
        return false;
    }
    else if( !ValidateEmail( inf_field_Email )  )	{
        alert('Please provide a valid email');
        return false;
    }
    else {
    	$('#btnInfusionSoft').attr('disabled','disabled');
		$.post("/validate-sign-up",  $( "#inf_form_bc186b4af60c114f6297f3c069d44426" ).serialize() ).done(function(data){
			console.log(data);
			var json_data = $.parseJSON(data);
			if( json_data.status == 'success' ) {
				document.getElementById("inf_form_bc186b4af60c114f6297f3c069d44426").submit();
			}
			else {
				alert(json_data.message);
			}
			$('#btnInfusionSoft').removeAttr('disabled');
		});
    }
}

function ValidateEmail(email) {
	var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	return expr.test(email);
};
