<?php
namespace ay\xhprof;

$navigation	= array
(
	array('url' => url('hosts'), 'name' => 'Hosts', 'class' => $template['file'] == 'hosts' ? 'template active' : 'template'),
	array('url' => url('uris'), 'name' => 'URIs', 'class' => $template['file'] == 'uris' ? 'template active' : 'template'),
	array('url' => url('requests'), 'name' => 'Requests', 'class' => $template['file'] == 'requests' || $template['file'] == 'request' ? 'template active' : 'template')
);
?>
<div id="navigation">
<?php foreach($navigation as $e):?>
	<div class="button <?=$e['class']?>">
		<a href="<?=$e['url']?>"<?php if(!empty($e['class'])):?> class="<?=$e['class']?>"<?php endif;?>><?=$e['name']?></a>
	</div>
<?php endforeach;?>

<?php if($template['file'] == 'request' && empty($_GET['xhprof']['query']['second_request_id'])):?>
	<a href="<?=url('request', array('request_id' => $request['id']), array('callgraph' => 1))?>" class="callgraph" target="_blank">Callgraph</a>
<?php endif;?>

	<div class="button button-filter"><a>Filter</a></div>
	<div class="button button-summary"><a>Summary</a></div>
</div>
<?php
unset($navigation); ?>
<script type="text/html" id="filters">
<div class="filters">
	<p>The following filters affect the displayed data:</p>
	<dl>
	{{#filters}}
		<dt>{{label}}</dt>
		<dd>{{{value}}}</dt>
	{{/filters}}
	</dl>
</div>
</script>
