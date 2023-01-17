jQuery( document ).ready( function( $ )
{
    $('#add-bag').on( 'click', function(e)
    {
        let xhr;

        if( xhr && xhr.readystate != 1 )
        { 
            xhr.abort(); 
        }

        xhr = $.ajax({

            type: "POST",
            dataType: 'json',
            url: unimarken_addBagBack.url,
            data: {
                action      : unimarken_addBagBack.action,
                nonce       : unimarken_addBagBack.nonce,
            },
            beforeSend: function(xhr){
                $("#unimarken_fields_actions").prepend('<div id="overlay-spinner"><div class="lds-dual-ring"></div></div>');
            },
            success:  function ( obj ){   
                
                if( obj.r ){

                    $("#content-bags").append( obj.html );
                }
            },
            complete: function(r){
                $("#overlay-spinner").remove();
                
                /**
                 * Delete row bag
                 */
                $('div[row="bag"]').each( function(){
                    $( this ).find( '.delete-bag' ).on( 'click', function(e)
                    {
                        $( this ).parents('div[row="bag"]').remove();
                    });
                });
            },
        });
    });

    $('div[row="bag"]').each( function(){
        $( this ).find( '.delete-bag' ).on( 'click', function(e)
        {
            $( this ).parents('div[row="bag"]').remove();
        });
    });
});