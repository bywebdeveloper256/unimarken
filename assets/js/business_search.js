jQuery( document ).ready( function( $ )
{
    /**
     * Redirection to company search page
     */

     $('#unimarken-search-sb').on('click', function()
     {
        let url, ubTicker, ubIntName, ubCountry, ubSection, ubDivision, ubGroup, ubClass, ubIsin, ubBag, ubCurrency;

        ubTicker      = ( $('#business-ticker').val() !== '' )      ? '&ubTicker='      + $('#business-ticker').val() : '';
        ubIntName     = ( $('#business-name').val() !== '' )        ? '&ubIntName='     + $('#business-name').val() : '';
        ubCountry     = ( $('#business-country').val() !== '' )     ? '&ubCountry='     + $('#business-country').val() : '';
        ubSection     = ( $('#business-section').val() !== '' )     ? '&ubSection='     + $('#business-section').val() : '';
        ubDivision    = ( $('#business-division').val() !== '' )    ? '&ubDivision='    + $('#business-division').val() : '';
        ubGroup       = ( $('#business-group').val() !== '' )       ? '&ubGroup='       + $('#business-group').val() : '';
        ubClass       = ( $('#business-class').val() !== '' )       ? '&ubClass='       + $('#business-class').val() : '';
        ubIsin        = ( $('#business-isin').val() !== '' )        ? '&ubIsin='        + $('#business-isin').val() : '';
        ubBag         = ( $('#business-bag').val() !== '' )         ? '&ubBag='         + $('#business-bag').val() : '';
        ubCurrency    = ( $('#business-currency').val() !== '' )    ? '&ubCurrency='    + $('#business-currency').val() : '';
        ubType        = ( $('#business-type-name').val() === '0' || $('#business-type-name').val() === '1' )    ? $('#business-type-name').val() : '0';

        url = $(location).attr('href') + '/business-results/?paged=1&type=' + ubType + ubIntName + ubIsin + ubTicker + ubCountry + ubSection + ubDivision + ubGroup + ubClass + ubBag + ubCurrency;

        $( location ).attr( 'href', url );
    });
});