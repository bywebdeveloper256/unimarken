jQuery( document ).ready( function( $ )
{
    let xhr, selectsFilters, ubCountry, ubSection, ubDivision, ubGroup, ubClass, ubBag, ubIntName, ubIsin, ubTicker; 

    selectsFilters = [
        "#business-country",
        "#business-section",
        "#business-division",
        "#business-group",
        "#business-class",
        "#business-bag",
        "#business-currency",
        "#unimarken-reset-sb",
        '#business-name',
        '#business-isin',
        '#business-ticker'
    ];

    $.each( selectsFilters, function( key, value )
    {
        if( value == "#unimarken-reset-sb" ){
            $on = 'click';
        }else{
            $on = 'change';
        }

        $( value ).on( $on, function()
        {
            if( value == "#unimarken-reset-sb" ){
                $('#business-country').val('');
                $('#business-name').val('');
                $('#business-section').val('');
                $('#business-division').val('');
                $('#business-group').val('');
                $('#business-class').val('');
                $('#business-isin').val('');
                $('#business-bag').val('');
                $('#business-currency').val('');
                $('#business-ticker').val('');
            }
            ubCountry   = $( "#business-country" ).val();
            ubSection   = $( "#business-section" ).val();
            ubDivision  = $( "#business-division" ).val();
            ubGroup     = $( "#business-group" ).val();
            ubClass     = $( "#business-class" ).val();
            ubBag       = $( "#business-bag" ).val();
            ubCurrency  = $('#business-currency').val();
            ubIntName   = ( getParameterByName( 'ubIntName' ) ) ? getParameterByName( 'ubIntName' ) : $('#business-name').val();
            ubIsin      = ( getParameterByName( 'ubIsin' ) ) ? getParameterByName( 'ubIsin' ) : $('#business-isin').val();
            ubTicker    = ( getParameterByName( 'ubTicker' ) ) ? getParameterByName( 'ubTicker' ) : $('#business-ticker').val();

            if( xhr && xhr.readystate != 1 ) xhr.abort();

            xhr = $.ajax({
                type: "POST",
                dataType: 'json',
                url: unimarken_ft.url,
                data: {
                    action     : unimarken_ft.action,
                    nonce      : unimarken_ft.nonce,
                    ubCountry  : ubCountry,       
                    ubSection  : ubSection,       
                    ubDivision : ubDivision,         
                    ubGroup    : ubGroup,
                    ubClass    : ubClass,   
                    ubBag      : ubBag,
                    ubCurrency : ubCurrency,
                    ubIntName  : ubIntName,
                    ubIsin     : ubIsin,
                    ubTicker   : ubTicker
                },
                beforeSend: function (r){
                    $("body").prepend('<div id="overlay-spinner"><div class="lds-dual-ring"></div></div>');
                },
                success:  function ( obj ){  

                    if(obj.r)
                    {
                        $("#business-country").html( '<option value=""> — </option>' );

                        $.each( obj.countries, function( r, term)
                        {
                            if( obj.selectCountry == term.term_id )
                            {
                                $("#business-country").append( '<option value="' + term.term_id + '" selected>' + term.name + '</option>' );
                            }else{
                                $("#business-country").append( '<option value="' + term.term_id + '">' + term.name + '</option>' );
                            }
                        });

                        $("#business-section").html( '<option value=""> — </option>' );

                        $.each( obj.sections, function( r, term)
                        {
                            if( obj.selectSection == term.term_id )
                            {
                                $("#business-section").append( '<option value="' + term.term_id + '" selected>' + term.name + '</option>' );
                            }else{
                                $("#business-section").append( '<option value="' + term.term_id + '">' + term.name + '</option>' );
                            }
                        });

                        $("#business-division").html( '<option value=""> — </option>' );

                        $.each( obj.divisions, function( r, term)
                        {
                            if( obj.selectDivision == term.term_id )
                            {
                                $("#business-division").append( '<option value="' + term.term_id + '" selected>' + term.name + '</option>' );
                            }else{
                                $("#business-division").append( '<option value="' + term.term_id + '">' + term.name + '</option>' );
                            }
                        });

                        $("#business-group").html( '<option value=""> — </option>' );

                        $.each( obj.groups, function( r, term)
                        {
                            if( obj.selectGroup == term.term_id )
                            {
                                $("#business-group").append( '<option value="' + term.term_id + '" selected>' + term.name + '</option>' );
                            }else{
                                $("#business-group").append( '<option value="' + term.term_id + '">' + term.name + '</option>' );
                            }
                        });

                        $("#business-class").html( '<option value=""> — </option>' );

                        $.each( obj.classes, function( r, term)
                        {
                            if( obj.selectClass == term.term_id )
                            {
                                $("#business-class").append( '<option value="' + term.term_id + '" selected>' + term.name + '</option>' );
                            }else{
                                $("#business-class").append( '<option value="' + term.term_id + '">' + term.name + '</option>' );
                            }
                        });

                        $("#business-bag").html( '<option value=""> — </option>' );

                        $.each( obj.bags, function( r, term)
                        {
                            if( obj.selectBag == term.term_id )
                            {
                                $("#business-bag").append( '<option value="' + term.term_id + '" selected>' + term.name + '</option>' );
                            }else{
                                $("#business-bag").append( '<option value="' + term.term_id + '">' + term.name + '</option>' );
                            }
                        });

                        $("#business-currency").html( '<option value=""> — </option>' );

                        $.each( obj.currency, function( r, term)
                        {
                            if( obj.selectCurrency == term.term_id )
                            {
                                $("#business-currency").append( '<option value="' + term.term_id + '" selected>' + term.name + '</option>' );
                            }else{
                                $("#business-currency").append( '<option value="' + term.term_id + '">' + term.name + '</option>' );
                            }
                        });
                        
                       
                    }
                    
                },
                complete: function(r){
                    $("#overlay-spinner").remove();
                }
            });
        });
    });
});