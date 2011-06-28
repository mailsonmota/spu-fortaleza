$.fn.dataTableExt.oPagination.text_only = {
	/*
	 * Function: oPagination.four_button.fnInit
	 * Purpose:  Initalise dom elements required for pagination with a list of the pages
	 * Returns:  -
	 * Inputs:   object:oSettings - dataTables settings object
	 *           node:nPaging - the DIV which contains this pagination control
	 *           function:fnCallbackDraw - draw function which must be called on update
	 */
	"fnInit": function ( oSettings, nPaging, fnCallbackDraw )
	{
		nPrevious = document.createElement( 'span' );
		nNext = document.createElement( 'span' );
		
		nPrevious.appendChild( document.createTextNode( oSettings.oLanguage.oPaginate.sPrevious ) );
		nNext.appendChild( document.createTextNode( oSettings.oLanguage.oPaginate.sNext ) );
		
		nPrevious.className = "paginate_button previous";
		nNext.className="paginate_button next";
		
		nPaging.appendChild( nPrevious );
		nPaging.appendChild( nNext );
		
		$(nPrevious).click( function() {
			oSettings.oApi._fnPageChange( oSettings, "previous" );
			fnCallbackDraw( oSettings );
		} );
		
		$(nNext).click( function() {
			oSettings.oApi._fnPageChange( oSettings, "next" );
			fnCallbackDraw( oSettings );
		} );
		
		/* Disallow text selection */
		$(nPrevious).bind( 'selectstart', function () { return false; } );
		$(nNext).bind( 'selectstart', function () { return false; } );
	},
	
	/*
	 * Function: oPagination.four_button.fnUpdate
	 * Purpose:  Update the list of page buttons shows
	 * Returns:  -
	 * Inputs:   object:oSettings - dataTables settings object
	 *           function:fnCallbackDraw - draw function which must be called on update
	 */
	"fnUpdate": function ( oSettings, fnCallbackDraw )
	{
		if ( !oSettings.aanFeatures.p )
		{
			return;
		}
		
		/* Loop over each instance of the pager */
		var an = oSettings.aanFeatures.p;
		for ( var i=0, iLen=an.length ; i<iLen ; i++ )
		{
			var buttons = an[i].getElementsByTagName('span');
			
			if ( oSettings.iNextId < 50 )
			{
				buttons[0].className = "paginate_button previous disabled";
				buttons[1].className = "paginate_button next disabled";
			} else
			{
				buttons[0].className = "paginate_button previous";
				buttons[1].className = "paginate_button next";
			}
		}
	}
};