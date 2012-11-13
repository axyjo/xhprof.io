<?php
namespace ay\xhprof;
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="public/css/frontend.css" type="text/css" charset="utf-8">
	<title>XHProf.io</title>
</head>
<body class="template-<?=$template['file']?>">
	<?php require __DIR__ . '/header.inc.tpl.php';?>

	<?=\ay\display_messages()?>

	<?=$template['body']?>

	<?php require __DIR__ . '/footer.inc.tpl.php';?>
	<script type="text/javascript" src="public/js/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="public/js/ICanHaz.js"></script>
	<script type="text/javascript" src="public/js/d3.v2.js"></script>
	<script type="text/javascript" src="public/js/crossfilter.v1.js"></script>
	<script type="text/javascript" src="public/js/dc.js"></script>
	<script type="text/javascript" src="public/js/frontend.js"></script>
</body>
</html>
