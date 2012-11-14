<?php
namespace ay\xhprof;
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="public/css/frontend.css" type="text/css" charset="utf-8">
	<title>XHProf.io</title>

	<!-- Templates -->
	<script id="link" type="text/html">
      <a href="{{url}}">{{text}}</a>
    </script>
</head>
<body class="template-<?=$template['file']?>">
	<div class="container-fluid">
		<?php require __DIR__ . '/header.inc.tpl.php';?>

		<?=\ay\display_messages()?>

		<?=$template['body']?>

		<?php require __DIR__ . '/footer.inc.tpl.php';?>
	</div>
	<script type="text/javascript">
		var App = App || {};
		App.config = {
			base_url: "<?=BASE_URL?>"
		}
	</script>
	<script type="text/javascript" src="public/js/php.js"></script>
	<script type="text/javascript" src="public/js/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="public/js/ICanHaz.js"></script>
	<script type="text/javascript" src="public/js/d3.v2.js"></script>
	<script type="text/javascript" src="public/js/crossfilter.v1.js"></script>
	<script type="text/javascript" src="public/js/dc.js"></script>
	<script type="text/javascript" src="public/js/frontend.js"></script>
</body>
</html>
