<?php
namespace ay\xhprof;
?>
<div class="histogram-layout">
	<div class="left">
		<div class="column">
			<div class="label">Number of Requests by Date</div>
			<svg class="histogram-date"></svg>
		</div>
	</div>
	<div class="right">
		<div class="column">
			<div class="label">CPU</div>
			<svg class="histogram-cpu"></svg>
		</div>
		<div class="column">
			<div class="label">Wall Time</div>
			<svg class="histogram-wt"></svg>
		</div>
	</div>
	<div class="center">
		<div class="column" id="histogram-mu">
			<div class="label">Memory Usage</div>
			<svg class="histogram-mu"></svg>
		</div>
		<div class="column">
			<div class="label">Peak Memory Usage</div>
			<svg class="histogram-pmu"></svg>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	var requests	= <?php
	echo json_encode($data['discrete']);
	?>;

	var format	=
	{
		bytes: function(number)
		{
			var precision	= 2;

		    var base		= Math.log(Math.abs(number)) / Math.log(1024);
		    var suffixes	= ['b', 'k', 'M', 'G', 'T'];

		   return (Math.pow(1024, base - Math.floor(base))).toFixed(precision) + ' ' + suffixes[Math.floor(base)];
		},
		microseconds: function(number)
		{
			var pad		= false;
			var suffix	= '&mu;s';

			if (number >= 1000)
			{
				number	= number / 1000;
				suffix	= 'ms';

				if (number >= 1000)
				{
					pad		= true;

					number	= number / 1000;
					suffix	= 's';

					if (number >= 60)
					{
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
	}

	var filter	= crossfilter(requests);
	var all = filter.groupAll();
	var data	=
	{
		id: {
			dimension: filter.dimension(function(d) {
				return d.id;
			})
		},
		date:
		{
			dimension: filter.dimension(function(d){ return new Date(d.request_timestamp*1000); })
		},
		wt:
		{
			dimension: filter.dimension(function(d){ return d.wt; })
		},
		cpu:
		{
			dimension: filter.dimension(function(d){ return d.cpu; })
		},
		mu:
		{
			dimension: filter.dimension(function(d){ return parseInt(d.mu, 10); })
		},
		pmu:
		{
			dimension: filter.dimension(function(d){ return d.pmu; })
		}
	};

	var date_scale	= date_scale	= {name: 'day', interval: 1000*3600*24};
	var days		= (requests[0].request_timestamp-requests[requests.length-1].request_timestamp)/(3600*24);

	if(days < 1)
	{
		date_scale	= {name: 'minute', interval: 1000*60};
	}
	else if(days < 10)
	{
		date_scale	= {name: 'hour', interval: 1000*3600};
	}

	data.id.group	= data.id.dimension.group();
	data.date.group	= data.date.dimension.group(function(d){ return d3.time[date_scale.name](d); });
	data.wt.group	= data.wt.dimension.group(function(d){ return Math.floor(d / 1000)*1000; });
	data.cpu.group	= data.cpu.dimension.group(function(d){ return Math.floor(d / 100)*100; });
	data.mu.group	= data.mu.dimension.group(function(d){ return Math.floor(d / 2500000)*2500000; });
	data.pmu.group	= data.pmu.dimension.group(function(d){ return Math.floor(d / 1000)*1000; });

	var all			= data.id.group.all();

	var mu_top = data.mu.dimension.top(1)[0];
	var mu_bottom = data.mu.dimension.bottom(1)[0];
	var mu_chart = dc.barChart("#histogram-mu");
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
				if (i == 0) return '';
				if (i % 2 == 1)	return format.bytes(d);
			});
	dc.renderAll();
});
</script>
