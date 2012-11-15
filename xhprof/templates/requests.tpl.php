<?php
namespace ay\xhprof;

if(!\ay\error_present())
{
	$data	= $xhprof_data_obj->getRequests($_GET['xhprof']['query']);

	if(empty($data))
	{
		\ay\message('No results matching your search were found.', AY_MESSAGE_NOTICE);
	}
}

require __DIR__ . '/form.inc.tpl.php';

if(empty($data['discrete']))
{
	return;
}

require __DIR__ . '/summary.inc.tpl.php';

require __DIR__ . '/histogram.inc.tpl.php';
?>
<div class="container-fluid">
	<table class="table table-striped table-bordered" id="data-table">
		<thead class="ay-sticky">
			<tr>
				<th class="request-id" rowspan="2">Request ID</th>
				<th class="host" rowspan="2">Host</th>
				<th class="" rowspan="2">URI</th>
				<th class="request-method" rowspan="2">Request Method</th>
				<th class="heading" colspan="4">Metrics</th>
				<th class="date-time" rowspan="2">Request Time</th>
			</tr>
			<tr>
				<th>Wall Time</th>
				<th>CPU</th>
				<th>Memory Usage</th>
				<th>Peak Memory Usage</th>
			</tr>
		</thead>
	</table>
</div>
