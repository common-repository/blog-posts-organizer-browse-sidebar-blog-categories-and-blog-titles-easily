jQuery( document ).ready(
    function ($) {
        'use strict';

        function debounce(func, wait) {
            let timeout;
            return function () {
                const context = this, args = arguments;
                clearTimeout( timeout );
                timeout = setTimeout( () => func.apply( context, args ), wait );
            };
        }

        function performSearch() {
            var query = $( '#atw-search-input' ).val();

            $.ajax(
                {
					url: bpo_org_live_search.ajax_url,
					type: 'POST',
					data: {
						action: 'bpo_org_live_search',
						query: query,
						security: bpo_org_live_search.nonce
					},
					success: function (response) {
						$( '#atw-search-results' ).html( response );
					}
                    }
            );
        }

        $( '#atw-search-input' ).on( 'keyup', debounce( performSearch, 300 ) );

        $( "#accordion" ).accordion(
            {
				collapsible: true,
				heightStyle: "content",
				active: false,
				activate: function (event, ui) {
					if (ui.newPanel.length) {
						// when opening a panel
						//var top = ui.newPanel.offset().top - 100; // adjust offset for fixed headers, etc.
						//$('html, body').animate({ scrollTop: top }, 500);

						jQuery( 'body.single-post' ).addClass( "rm-documentation" )
					}
				}
                }
        );

        // Open the panel containing the active post
        var activePost = $( "#accordion .active" ).closest( "ul" ).closest( "div" );
        if (activePost.length) {
            var activeIndex = $( "#accordion > div" ).index( activePost );
            $( "#accordion" ).accordion( "option", "active", activeIndex );
        }

    }
);
