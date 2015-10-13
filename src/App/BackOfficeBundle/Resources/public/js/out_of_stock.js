/**
 * Created by dss on 02.09.15.
 */

  $( '.datetime' )
        .datetimepicker({
          format: 'HH:mm',
          stepping: "30"
      });

$( '.mandatory-change-button' ).on( 'click',function(){
    var form = $( this ).parents( 'form'),
    url  = "oosmandatory/change",
    $message = sendAjax(form, url);

    $('.alert_mandatory').html($message);

});

$( '.add-message-button' ).on( 'click',function(){
    var form = $( this ).parents( 'form'),
        url = 'oosoptional/change',
        $message = sendAjax(form, url);

    $('.alert_optional').html($message);

});

$( '.remove-message-button' ).on( 'click',function(){
    var url = 'oosoptional/remove',
        $message = sendAjax(false, url);

    $('.alert_optional').html($message);

});

function sendAjax(form, url){

    var data = '';
    if(form){
        data = form.serialize();
    }
    $.ajax({
        url: url,
        method: "POST",
        data: data
    }).done( function( response ) {
        return '<div class="alert alert-success"><strong>Success!</strong></div>';
    });
}