/**
 * @package     OpenSolution
 * @author:     dss <dss@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 18 05 2015
 */


var templateEmptyTime = '';
var templateNewDate = '';

$(function() {
    "use strict";

    templateNewDate = strTrimLager( $( '#template-new-date' ).html() );
    templateEmptyTime = strTrimLager( $( '#template-empty-time' ).html() );

    //compiling data from open hour restaurant
    $( '.restaurants-time-block' ).each( function(){
        var day = [];
        var restaurant = $( this );
        restaurant.find( '.week' ).each( function(){
            var week = $( this );
            if(!day[week.attr( 'data-service' )]){
                day[week.attr( 'data-service' )] = [];
            }
            day[week.attr( 'data-service' )][week.attr('data-day')] = strTrimLager( week.html() );
        });

        for ( var service in day ) {
            for ( var dayOfWeek in day[service] ){
                restaurant.find( '.day[data-service="'+service+'"][data-day="'+dayOfWeek+'"]' ).html( day[service][dayOfWeek] );
            }
        }

        restaurant.find('.weekend').each(function(){
            var weekend = $(this);
            restaurant.find( '.holidays td[data-service="'+weekend.attr( 'data-service' )+'"]' ).
                append( strTrimLager(weekend.html()) );
        });

        $( '.day:empty' ).html( templateEmptyTime );

        restaurant.find( 'td' ).attr( 'data-index', restaurant.attr( 'data-index' ) );

    });

    $( '.add-holiday-button' ).on( 'click',function(){
            var form = $( this ).parents( 'form'),
                url  = "openhours/datechange";
            $.ajax({
               url: url,
                method: "POST",
                data: form.serialize()
            }).done( function( response ) {
                var start    = form.find('input[name="start"]').val(),
                    finish   = form.find('input[name="finish"]').val(),
                        reason   = form.find('input[name="reason"]').val(),
                        service  = form.find('select').val(),
                        dateform = form.find('input[name="date"]').val(),
                        id       = form.find('input[name="id"]').val(),
                        timetemp = start == finish ? 'closed' : start + " - " + finish;
                var div = '<div data-id="' + response + '">' + reason + '(' + dateform + ')'+
                        templateNewDate + timetemp +
                        '</div>';
                $( '.holidays td[data-service="' + service + '"][data-index="' + id + '"]' ).append( div );
                Initial()
            });
        Initial();
    })

    $( '.agree' ).click( function(){
        var btn           = $(this),
            idRestaurant  = btn.attr('data-index'),
            service       = btn.attr('data-service'),
            data          = {},
            url           = 'openhours/change',
            params = {
                id:  idRestaurant,
                data:     data,
                service: service
            };

        $( 'td[data-service="'+service+'"][data-index="'+idRestaurant+'"][data-day]').each( function(){
            var day = $(this),
                i   = day.attr('data-day');
            data[i]           = {};
            data[i]['start']  = day.find('.start-time').val();
            data[i]['finish'] = day.find('.end-time').val();

        });

        $.ajax({
            url: url,
            method: "POST",
            data: params
        }).done( function() {
            $( '.agree[data-index="'+idRestaurant+'"][data-service="'+service+'"]' ).prop( "disabled", true );
        });
    });

    $( '.start-time, .end-time' )
        .datetimepicker( {
            format: "HH:mm",
            stepping: "30",
        } ).bind('keyup input change paste click',function(){
        var td           = $( this ).parents( "td"),
            idRestaurant = td.attr('data-index'),
            service      = td.attr('data-service');
        $( '.agree[data-index="'+idRestaurant+'"][data-service="'+service+'"]' ).prop( "disabled", false );
    });

    $( 'input.date-control' ).datetimepicker({
        format: 'DD.MM.YYYY'
    });

    Initial();
});

function Initial(){

    "use strict";

    $( '.rm-date' ).on( 'click', function(){
        var div   = $( this ).parent( 'div' ),
            id   = div.attr( 'data-id' );
        $.ajax({
            url:    'openhours/delete',
            method: 'POST',
            data:   { id: id }
        });

        div.remove();
    } );
}

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] === undefined) {
            o[this.name] = this.value || '';
        } else {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        }
    });
    return o;
};

function strTrimLager( strtemp ){
    "use strict";

    return strtemp.replace(/\s{2,}/g, ' ');
}



