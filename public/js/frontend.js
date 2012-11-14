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

App.render = function render(template, data) {
	return $(ich[template](data)).clone().wrap('<p>').parent().html();
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

	if($('body').hasClass('template-requests')) {


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
					dimension: filter.dimension(function(d){ return parseInt(d.wt, 10); })
				},
				cpu: {
					dimension: filter.dimension(function(d){ return parseInt(d.cpu, 10); })
				},
				mu: {
					dimension: filter.dimension(function(d){ return parseInt(d.mu, 10); })
				},
				pmu: {
					dimension: filter.dimension(function(d){ return parseInt(d.pmu, 10); })
				}
			};

			var date_scale = {name: 'day', interval: 1000*3600*24};

			data.id.group	= data.id.dimension.group();
			data.date.group	= data.date.dimension.group(function(d){ return d3.time[date_scale.name](d); });
			data.wt.group	= data.wt.dimension.group(function(d){ return Math.floor(d/100000)*100000; });
			data.cpu.group	= data.cpu.dimension.group(function(d){ return Math.floor(d/100000)*100000; });
			data.mu.group	= data.mu.dimension.group(function(d){ return Math.floor(d); });
			data.pmu.group	= data.pmu.dimension.group(function(d){ return Math.floor(d); });

			var all = filter.groupAll();
			var table = dc.dataTable("#data-table");
			var counter = dc.dataCount("#data-count");
			var small_charts = [
				{
					chart: dc.barChart("#histogram-mu"),
					dimension: data.mu.dimension,
					group: data.mu.group,
					top: data.mu.dimension.top(1)[0].mu,
					format: format.bytes
				},
				{
					chart: dc.barChart("#histogram-pmu"),
					dimension: data.pmu.dimension,
					group: data.pmu.group,
					top: data.pmu.dimension.top(1)[0].pmu,
					format: format.bytes
				},
				{
					chart: dc.barChart("#histogram-wt"),
					dimension: data.wt.dimension,
					group: data.wt.group,
					top: data.wt.dimension.top(1)[0].wt,
					format: format.microseconds
				},
				{
					chart: dc.barChart("#histogram-cpu"),
					dimension: data.cpu.dimension,
					group: data.cpu.group,
					top: data.cpu.dimension.top(1)[0].cpu,
					format: format.microseconds
				}
			];

			$.each(small_charts, function(i, data) {
				data.chart
					.width(400).height(200)
					.dimension(data.dimension).group(data.group)
					.elasticX(true).elasticY(true)
					.x(d3.scale.linear()
						.domain([0, data.top])
						.rangeRound([0, data.top])
					)
					.xAxis()
						.tickFormat(function(d, i) {
						if (i === 0) return '';
						if (i % 2 == 1)	return data.format(d);
					});
				$("a.reset", data.chart.anchor()).click(function() {
					data.chart.filterAll();
					dc.redrawAll();
				});
			});

			counter
				.dimension(filter)
				.group(all);

			table
				// set dimension
				.dimension(data.id.dimension)
				// data table does not use crossfilter group but rather a closure
				// as a grouping function
				.group(function() {})
				// dynamic columns creation using an array of closures
				.columns([
					function(d) {
						return App.render('link', {
							url: App.buildURL('request', {request_id: d.request_id}),
							text: d.request_id
						});
					},
					function(d) {
						return App.render('link', {
							url: App.buildURL('uris', {host_id: d.host_id}),
							text: d.host
						});
					},
					function(d) {
						return App.render('link', {
							url: App.buildURL('uris', {host_id: d.host_id, uri_id: d.uri_id}),
							text: d.uri
						});
					},
					function(d) { return d.request_method; },
					function(d) { return format.microseconds(d.wt); },
					function(d) { return format.microseconds(d.cpu); },
					function(d) { return format.bytes(d.mu); },
					function(d) { return format.bytes(d.pmu); },
					function(d) { return d.request_timestamp; }
				])
				.order(d3.descending)
				// (optional) sort using the given field, :default = function(d){return d;}
				.sortBy(function(d){ return parseInt(d.request_timestamp, 10); });
				// (optional) sort order, :default ascending

			dc.renderAll();
		});
	} else if($('body').hasClass('template-hosts')) {
		$.getJSON('data.php' + window.location.search, function(o) {
			var filter	= crossfilter(o.discrete);
			var dimension = filter.dimension(function(d) {
				return d.id;
			});
			var table = dc.dataTable("#data-table");
			table.dimension(dimension).group(function() {})
				.columns([
					function(d) {
						return App.render('link', {
							url: App.buildURL('uris', {host_id: d.host_id}),
							text: d.host
						});
					},
					function(d) {
						return d.request_count;
					},
					function(d) { return format.microseconds(d.wt); },
					function(d) { return format.microseconds(d.cpu); },
					function(d) { return format.bytes(d.mu); },
					function(d) { return format.bytes(d.pmu); }
				]);
			dc.renderAll();
		});
	}
});
