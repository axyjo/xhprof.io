var App = App || {};

App.buildURL = function(template, xhprof_query, xhprof) {
  var query, url;
  if (xhprof === null) {
    xhprof = {};
  }
  url = App.config.base_url;
  if (template === null) {
    return url;
  }
  query = {
    xhprof: xhprof
  };
  if (query.xhprof) {
    query.xhprof.template = template;
  } else {
    query.xhprof = {
      template: template
    };
  }
  if (xhprof_query) {
    query.xhprof.query = xhprof_query;
  }
  return url + '?' + PHP.http_build_query(query);
};

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

	if($('body').hasClass('template-requests')) {
		var format	= {
			bytes: function(number) {
				var precision	= 2;
				var base		= Math.log(Math.abs(number)) / Math.log(1024);
				var suffixes	= ['b', 'k', 'M', 'G', 'T'];

				return (Math.pow(1024, base - Math.floor(base))).toFixed(precision) + ' ' + suffixes[Math.floor(base)];
			},
			microseconds: function(number) {
				var pad		= false;
				var suffix	= '&mu;s';

				if (number >= 1000) {
					number	= number / 1000;
					suffix	= 'ms';

					if (number >= 1000) {
						pad		= true;

						number	= number / 1000;
						suffix	= 's';

						if (number >= 60) {
							number	= number / 60;
							suffix	= 'm';
						}
					}
				}

				return pad ? number.toFixed(2) + ' ' + suffix : number + ' ' + suffix;
			}
		};

		var units = {
			megabytes: function(start, end) {
				return new Array(Math.abs((end >> 20) - (start >> 20)));
			}
		};

		$.getJSON('data.php' + window.location.search, function(o) {
			var filter	= crossfilter(o.discrete);
			var data	= {
				id: {
					dimension: filter.dimension(function(d) {
						return d.id;
					})
				},
				date: {
					dimension: filter.dimension(function(d){ return new Date(d.request_timestamp*1000); })
				},
				wt: {
					dimension: filter.dimension(function(d){ return d.wt; })
				},
				cpu: {
					dimension: filter.dimension(function(d){ return d.cpu; })
				},
				mu: {
					dimension: filter.dimension(function(d){ return parseInt(d.mu, 10); })
				},
				pmu: {
					dimension: filter.dimension(function(d){ return d.pmu; })
				}
			};

			var date_scale = {name: 'day', interval: 1000*3600*24};

			data.id.group	= data.id.dimension.group();
			data.date.group	= data.date.dimension.group(function(d){ return d3.time[date_scale.name](d); });
			data.wt.group	= data.wt.dimension.group(function(d){ return Math.floor(d / 1000)*1000; });
			data.cpu.group	= data.cpu.dimension.group(function(d){ return Math.floor(d / 100)*100; });
			data.mu.group	= data.mu.dimension.group(function(d){ return Math.floor(d / 2500000)*2500000; });
			data.pmu.group	= data.pmu.dimension.group(function(d){ return Math.floor(d / 1000)*1000; });

			var mu_top = data.mu.dimension.top(1)[0];
			var mu_bottom = data.mu.dimension.bottom(1)[0];
			var mu_chart = dc.barChart("#histogram-mu");
			var table = dc.dataTable("#data-table");

			mu_chart
				.width(400)
				.height(225)
				.margins({top: 10, right: 30, bottom: 30, left: 30})
				.dimension(data.mu.dimension)
				.group(data.mu.group)
				.elasticY(true)
				.centerBar(true)
				.gap(1)
				.round(dc.round.floor)
				.x(d3.scale.linear().domain([0, mu_top.mu]))
				.xUnits(units.megabytes)
				.xAxis()
					.tickFormat(function(d, i) {
						if (i === 0) return '';
						if (i % 2 == 1)	return format.bytes(d);
					});

			table
				// set dimension
				.dimension(data.date.dimension)
				// data table does not use crossfilter group but rather a closure
				// as a grouping function
				.group(function(d) {
					//return (new Date(d.request_timestamp)).getFullYear();
				})
				// (optional) max number of records to be shown, :default = 25
				.size(10)
				// dynamic columns creation using an array of closures
				.columns([
					function(d) { return d.request_id; },
					function(d) { return d.host; },
					function(d) { return d.uri; },
					function(d) { return d.request_method; },
					function(d) { return format.microseconds(d.wt); },
					function(d) { return format.microseconds(d.cpu); },
					function(d) { return format.bytes(d.mu); },
					function(d) { return format.bytes(d.pmu); },
					function(d) { return d.request_timestamp; }
				])
				// (optional) sort using the given field, :default = function(d){return d;}
				.sortBy(function(d){ return d.request_timestamp; })
				// (optional) sort order, :default ascending
				.order(d3.descending);

			dc.renderAll();
		});
	}
});
