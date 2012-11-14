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
		<div class="column" id="histogram-cpu">
			<div class="label">CPU <a class="reset" style="display: none;">reset</a></div>
			<svg class="histogram-cpu"></svg>
			<span class="reset" style="display: none;">Current filter: <span class="filter"></span></span>
		</div>
		<div class="column" id="histogram-wt">
			<div class="label">Wall Time <a class="reset" style="display: none;">reset</a></div>
			<svg class="histogram-wt"></svg>
			<span class="reset" style="display: none;">Current filter: <span class="filter"></span></span>
		</div>
	</div>
	<div class="center">
		<div class="column" id="histogram-mu">
			<div class="label">Memory Usage <a class="reset" style="display: none;">reset</a></div>
			<svg class="histogram-mu"></svg>
			<span class="reset" style="display: none;">Current filter: <span class="filter"></span></span>
		</div>
		<div class="column" id="histogram-pmu">
			<div class="label">Peak Memory Usage <a class="reset" style="display: none;">reset</a></div>
			<svg class="histogram-pmu"></svg>
			<span class="reset" style="display: none;">Current filter: <span class="filter"></span></span>
		</div>
	</div>
	<div id="data-count">
		<span class="filter-count"></span> selected out of <span class="total-count"></span> records
	</div>
</div>
