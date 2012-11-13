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
<div class="table-wrapper">
	<table class="requests ay-sort" id="data-table">
		<thead class="ay-sticky">
			<tr>
				<th class="ay-sort ay-sort-desc request-id" rowspan="2">Request ID</th>
				<th class="ay-sort host" rowspan="2">Host</th>
				<th class="ay-sort" rowspan="2">URI</th>
				<th class="ay-sort request-method" rowspan="2">Request Method</th>
				<th class="heading" colspan="4">Metrics</th>
				<th class="ay-sort date-time" rowspan="2" data-ay-sort-index="8">Request Time</th>
			</tr>
			<tr>

				<th class="ay-sort" data-ay-sort-index="4">Wall Time</th>
				<th class="ay-sort" data-ay-sort-index="5">CPU</th>
				<th class="ay-sort" data-ay-sort-index="6">Memory Usage</th>
				<th class="ay-sort" data-ay-sort-index="7">Peak Memory Usage</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
