<?php
namespace ay\xhprof;

require __DIR__ . '/form.inc.tpl.php';

require __DIR__ . '/summary.inc.tpl.php';
?>

<table id="data-table" class="hosts table table-striped table-bordered">
	<thead>
		<tr>
			<th rowspan="2">Host</th>
			<th rowspan="2">Request Count</th>
			<th colspan="4">Average</th>
		</tr>
		<tr>
			<th>Wall Time</th>
			<th>CPU</th>
			<th>Memory Usage</th>
			<th>Peak Memory Usage</th>
		</tr>
	</thead>
</table>
