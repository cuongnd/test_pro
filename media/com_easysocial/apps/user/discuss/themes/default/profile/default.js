
EasySocial.require()
.library( 'sparkline' )
.done(function($)
{
	$( '[data-discuss-discussions-gravity-chart]' ).sparkline( 'html',
		{ 
			width: '100%',
			height: '80px',
			lineWidth	: 3,
			lineColor 	: "#2b8c69",
			barColor	: "rgba(178,189,199,1)",
			zeroColor	: "rgba(228,123,121,1)",
			spotRadius	: 5,
			type		: 'bar',
			barWidth 	: '20px',
			barSpacing	: '10px',
			chartRangeMin : 0,
			tooltipFormatter : function( sparkline , options, fields )
			{
				var field	= fields[0];

				return '<span style="color: ' + field.color + '">&#9679;</span> <strong>' + field.value + '</strong> <?php echo JText::_( 'APP_DISCUSS_CHART_DISCUSSIONS_TOOLTIP' ); ?>';
			}
		});

	$( '[data-discuss-replies-gravity-chart]' ).sparkline( 'html',
		{ 
			width: '100%',
			height: '80px',
			lineWidth	: 3,
			lineColor 	: "#2b8c69",
			barColor	: "rgba(77,175,140,1)",
			zeroColor	: "rgba(228,123,121,1)",
			spotRadius	: 5,
			type		: 'bar',
			barWidth 	: '20px',
			barSpacing	: '10px',
			chartRangeMin : 0,
			tooltipFormatter : function( sparkline , options, fields )
			{
				var field	= fields[0];

				return '<span style="color: ' + field.color + '">&#9679;</span> <strong>' + field.value + '</strong> <?php echo JText::_( 'APP_DISCUSS_CHART_REPLIES_TOOLTIP' ); ?>';
			}
		});
});