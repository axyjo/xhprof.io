$(function(){
	$('#navigation .button-filter').on('click', function(){
		$('#filter').toggle();
		$(this).toggleClass('active');
	});

	$('#navigation .button-summary').on('click', function(){
		$('#metrics-summary').toggle();
		$(this).toggleClass('active');
	});

	if($('table.aggregated-callstack').length)
	{
		var alternate	= $('[data-ay-alternate]');

		alternate.on('ay-alternate', function(e, stage){
			var data	= $(this).data('ay-alternate');

			$(this).data('ay-alternate-stage', stage);

			$(this).html(data[stage]);
		});

		alternate.on('click', function(){
			var data	= $(this).data('ay-alternate');
			var stage	= $(this).data('ay-alternate-stage');

			if(typeof stage == 'undefined')
			{
				stage	= 0;
			}
			else if(typeof data[stage+1] != 'undefined')
			{
				++stage;
			}
			else
			{
				stage	= 0;
			}

			alternate.trigger('ay-alternate', stage);
		});
	}
});
