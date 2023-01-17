jQuery( document ).ready( function( $ )
{
    $('#business-save').on( 'click', function(e)
    {
        let bagEmpty, i, xhr, check, ubNameLa, ubIntName, ubCountry, ubWeb, ubComments, ubLogo, ubSection, ubDivision, ubGroup, ubClass, ubSymbol, ubSymbolTwo, ubBag;

        ubNameLa        = $('#business-latin-name').val();
        ubIntName     = $('#business-name').val();
        ubCountry     = $('#business-country').val();
        ubWeb         = $('#business-web').val();
        ubComments    = $('#business-comments').val();
        ubSection     = $('#business-section').val();
        ubDivision    = $('#business-division').val();
        ubGroup       = $('#business-group').val();
        ubClass       = $('#business-class').val();
        ubLogo        = $('#business-logo');

        /*ubSymbol      = $('#business-symbol').val();
        ubSymbolTwo   = $('#business-symbol-two').val();*/

        check         = $('#invalidCheck').prop("checked"); 

        bagEmpty = true;

        $('select[dataId="bag"]').each( function(){
            if( $(this).val() === "" ){
                return bagEmpty = false;
            }
        });

        $('select[dataId="currency"]').each( function(){
            if( $(this).val() === "" ){
                return bagEmpty = false;
            }
        });
        
        if( check && ubNameLa && ubCountry && ubWeb && ubSection && ubDivision && bagEmpty )
        {
            e.preventDefault();
            
            if( xhr && xhr.readystate != 1 )
            { 
                xhr.abort(); 
            }

            i = 0;
            ubBag = [];

            $('div[row="bag"]').each( function(){

                ba = $( this ).find( 'select[dataId="bag"]' ).val();

                t1 = $( this ).find( 'input[dataId="ticker-1"]' ).val();
                t2 = $( this ).find( 'input[dataId="ticker-2"]' ).val();
                is = $( this ).find( 'input[dataId="isin"]' ).val();
                cu = $( this ).find( 'select[dataId="currency"]' ).val();

                ubBag[i] = { bag: ba, ticker: t1, ticker2: t2, isin: is, currency: cu };

                i++;
            });

            dataUbBag = JSON.stringify( ubBag );

            var file = ubLogo[0].files[0];

            var data = new FormData();

            data.append( 'action', unimarken_ajax_requests.action );
            data.append( 'nonce', unimarken_ajax_requests.nonce );
            data.append( 'ubNameLa', ubNameLa );
            data.append( 'ubIntName', ubIntName );
            data.append( 'ubCountry', ubCountry );
            data.append( 'ubWeb', ubWeb );
            data.append( 'ubComments', ubComments );
            data.append( 'ubSection', ubSection );
            data.append( 'ubDivision', ubDivision );
            data.append( 'ubGroup', ubGroup );
            data.append( 'ubClass', ubClass );
            //data.append( 'ubSymbol', ubSymbol );
            //data.append( 'ubSymbolTwo', ubSymbolTwo );
            data.append( 'ubBag', dataUbBag );
            data.append( 'ubLogo', file );

            xhr = $.ajax({

                type: "POST",
                dataType: 'json',
                url: unimarken_ajax_requests.url,
                contentType:false,
                processData:false,
                cache:false,
                data: data,
                beforeSend: function(xhr){
                    $("body").prepend('<div id="overlay-spinner"><div class="lds-dual-ring"></div></div>');
                },
                success:  function ( obj ){    
                   
                    if( obj.r )
                    {
                        alert( obj.m );
                        location.reload(true);
                    }else{
                        alert( obj.m );
                    }
                },
                complete: function(r){
                    $("#overlay-spinner").remove();
                },
            });
        }
    });
});