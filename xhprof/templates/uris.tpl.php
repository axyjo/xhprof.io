<?php
namespace ay\xhprof;

require __DIR__ . '/form.inc.tpl.php';
require __DIR__ . '/summary.inc.tpl.php';
?>

<table id="data-table" class="uris table table-striped table-bordered">
	<thead>
		<tr>
			<?php if(empty($_GET['xhprof']['query']['host_id'])):?>
			<th rowspan="2">Host</th>
			<?php endif;?>
			<th rowspan="2">URI</th>
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
