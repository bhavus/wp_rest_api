console.log('Running...');
console.log( rest_url.url );

jQuery( '#trigger' ).on( 'click', function() {
    jQuery.ajax({
        url : rest_url.url
    }).done({
        function( data ) {
        }
    });
})