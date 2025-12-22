(function( $ ) {
    $( document ).on( 'click', '.notice-get-started-class .notice-dismiss', function () {
        // Read the "data-notice" information to track which notice is being dismissed
        var type = $( this ).closest( '.notice-get-started-class' ).data( 'notice' );
        
        // Make an AJAX call
        $.ajax( ajaxurl, {
            type: 'POST',
            data: {
                action: 'titan_tools_center_dismissable_notice',
                type: type,
                wpnonce: titan_tools_center.wpnonce // Make sure you localize the nonce
            }
        });
    });
})( jQuery );