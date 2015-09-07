
EasySocial.require()
.done( function( $ ){

	EasySocial.Controller( 'Story.App.Links' ,
	{
		defaultOptions :
		{
			"{previousImage}"	: "[data-story-link-image-prev]",
			"{nextImage}"		: "[data-story-link-image-next]",
			"{image}"			: "[data-story-link-image]",
			"{imagesWrapper}"	: "[data-story-link-images]",
			"{imageIndex}"		: "[data-story-link-image-index]",
			"{removeThumbnail}"	: "[data-story-link-remove-image]"
		}
	},
	function( self )
	{
		return {
			init: function()
			{
				// console.log( self );
			},

			"{removeThumbnail} click" : function()
			{
				var isChecked 	= self.removeThumbnail().is( ':checked' );

				if( isChecked )
				{
					self.imagesWrapper().hide();
				}
				else
				{
					self.imagesWrapper().show();
				}
			},

			"{previousImage} click" : function()
			{
				var prevImage 		= self.image('.active' ).prev(),
					currentImage 	= self.image( '.active' ),
					index 			= parseInt( self.imageIndex().html() ),
					nextIndex 		= index - 1;

				if( prevImage.length > 0 )
				{
					$( currentImage ).removeClass( 'active' );
					$( prevImage ).addClass( 'active' );


					self.imageIndex().html( nextIndex );
				}
				else
				{
					// Disable this button.
				}
			},

			"{nextImage} click" : function()
			{
				var nextImage 		= self.image('.active' ).next(),
					currentImage 	= self.image( '.active' ),
					index 			= parseInt( self.imageIndex().html() ),
					nextIndex 		= index + 1;

				if( nextImage.length > 0 )
				{
					$( currentImage ).removeClass( 'active' );
					$( nextImage ).addClass( 'active' );


					self.imageIndex().html( nextIndex );
				}
				else
				{
					// Disable this button.
				}
			}
		}
	});

	$( '[data-story-link-item]' ).implement( EasySocial.Controller.Story.App.Links );
});
