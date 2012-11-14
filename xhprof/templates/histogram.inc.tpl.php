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
		<div class="column" id="histogram-pmu">
			<div class="label">Peak Memory Usage</div>
			<svg class="histogram-pmu"></svg>
		</div>
	</div>
</div>
