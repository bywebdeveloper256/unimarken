jQuery( document ).ready( function ( $ )
{
    $( "#btn-alternativas" ).click( function()
    {
        $( "tr.product_alt" ).toggle( "slow" );

        if( $( "#btn-alternativas" ).text() == 'Ocultar' ){
            $( "#btn-alternativas" ).text('Ver alternativas');
        }else{
            $( "#btn-alternativas" ).text('Ocultar');
        }
    
    });
});