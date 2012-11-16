<?php
namespace ay\xhprof;
?>

<div class="histogram-layout">
	<div class="row">
		<div class="span12">
			<div class="row">
				<div class="span6" id="histogram-mu">
					<div class="label">Memory Usage</div>
					<a class="reset" style="display: none;">reset</a>
					<svg class="histogram-mu"></svg>
					<span class="reset" style="display: none;">Current filter: <span class="filter"></span></span>
				</div>

				<div class="span6" id="histogram-cpu">
					<div class="label">CPU</div>
					<a class="reset" style="display: none;">reset</a>
					<svg class="histogram-cpu"></svg>
					<span class="reset" style="display: none;">Current filter: <span class="filter"></span></span>
				</div>
			</div>
			<div class="row">
				<div class="span6" id="histogram-pmu">
					<div class="label">Peak Memory Usage</div>
					<a class="reset" style="display: none;">reset</a>
					<svg class="histogram-pmu"></svg>
					<span class="reset" style="display: none;">Current filter: <span class="filter"></span></span>
				</div>
				<div class="span6" id="histogram-wt">
					<div class="label">Wall Time</div>
					<a class="reset" style="display: none;">reset</a>
					<svg class="histogram-wt"></svg>
					<span class="reset" style="display: none;">Current filter: <span class="filter"></span></span>
				</div>
			</div>
		</div>
	</div>
	<div id="data-count" class="dc-data-count row">
		<span class="filter-count"></span> selected out of <span class="total-count"></span> records
	</div>
</div>
