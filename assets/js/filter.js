jQuery( document ).ready( function( $ )
{
    let xhr, selectsFilters, ubCountry, ubSection, ubDivision, ubGroup, ubClass, ubBag, url, idCountry, idSection, idDivision, idGroup, idClass, idBag; 

    selectsFilters = [
        "#business-country",
        "#business-section",
        "#business-division",
        "#business-group",
        "#business-class",
        "#business-bag"
    ];

    $.each( selectsFilters, function( key, value )
    {
        $( value ).on( 'change', function(e)
        {
            ubCountry   = ( $( "#business-country" ).val() ) ? '&ubCountry=' + $( "#business-country" ).val() : '';
            ubSection   = ( $( "#business-section" ).val() ) ? '&ubSection=' + $( "#business-section" ).val() : '';
            ubDivision  = ( $( "#business-division" ).val() ) ? '&ubDivision=' + $( "#business-division" ).val() : '';
            ubGroup     = ( $( "#business-group" ).val() ) ? '&ubGroup=' + $( "#business-group" ).val() : '';
            ubClass     = ( $( "#business-class" ).val() ) ? '&ubClass=' + $( "#business-class" ).val() : '';
            ubBag       = ( $( "#business-bag" ).val() ) ? '&ubBag=' + $( "#business-bag" ).val() : '';
            ubIntName   = ( getParameterByName( 'ubIntName' ) ) ? '&ubIntName=' + getParameterByName( 'ubIntName' ) : '';
            ubIsin      = ( getParameterByName( 'ubIsin' ) ) ? '&ubIsin=' + getParameterByName( 'ubIsin' ) : '';

            idCountry   = ( $( "#business-country" ).val() ) ? $( "#business-country" ).val() : '';
            idSection   = ( $( "#business-section" ).val() ) ? $( "#business-section" ).val() : '';
            idDivision  = ( $( "#business-division" ).val() ) ? $( "#business-division" ).val() : '';
            idGroup     = ( $( "#business-group" ).val() ) ? $( "#business-group" ).val() : '';
            idClass     = ( $( "#business-class" ).val() ) ? $( "#business-class" ).val() : '';
            idBag       = ( $( "#business-bag" ).val() ) ? $( "#business-bag" ).val() : '';
            idIntName   = ( getParameterByName( 'ubIntName' ) ) ? getParameterByName( 'ubIntName' ) : '';
            idIsin      = ( getParameterByName( 'ubIsin' ) ) ? getParameterByName( 'ubIsin' ) : '';

            if( ubIntName || ubIsin || ubCountry || ubSection || ubDivision || ubGroup || ubClass || ubBag ){

                url = '/business-results/?paged=1' + ubIntName + ubIsin + ubCountry + ubSection + ubDivision + ubGroup + ubClass + ubBag;
            }else{
                url = '/business-results/?paged=1';
            }

            history.pushState( null, "Business result", url );

            if( xhr && xhr.readystate != 1 ) xhr.abort();

            xhr = $.ajax({
                type: "POST",
                dataType: 'json',
                url: unimarken_filter.url,
                data: {
                    action     : unimarken_filter.action,
                    nonce      : unimarken_filter.nonce,
                    ubCountry  : idCountry,       
                    ubSection  : idSection,       
                    ubDivision : idDivision,         
                    ubGroup    : idGroup,
                    ubClass    : idClass,   
                    ubBag      : idBag,
                    ubIntName  : idIntName,
                    ubIsin     : idIsin,
                },
                beforeSend: function (r){
                    $("body").prepend('<div id="overlay-spinner"><div class="lds-dual-ring"></div></div>');
                },
                success:  function ( obj ){  

                    $("#content-result-business").html('');

                    $('#count-business').html( obj.count );

                    if(obj.r)
                    {
                        $.each( obj.items, function( r, item) {
                                
                            $("#content-result-business").append( item );
                        });

                        if( obj.paginate != null ){
                            $("#content-result-business").append( '<div class="my-3">' + obj.paginate + '</div>');
                        }
    
                    }else{
                        $("#content-result-business").append('<p>'+ obj.m +'</p>');
                    }
                    
                },
                complete: function(r){
                    $("#overlay-spinner").remove();
                }
            });
        });
    });

});

