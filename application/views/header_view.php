<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $this->lang->line('ui_title_brand'); ?></title>

		<!-- charset -->
		<meta charset="utf-8">
		<!-- viewport -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<link rel="stylesheet" type="text/css" href="<?php echo $asset_url; ?>bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $asset_url; ?>bootstrap/css/bootstrap-responsive.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $asset_url; ?>css/scrollbar.css">
		<script>if (location.hostname == "127.0.0.1") { document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=2"></' + 'script>') }</script>
		<?php
			if ( isset($style_includes) ) {
				foreach ($style_includes as $include) {
					echo '<link rel="stylesheet" type="text/css" href="'.$asset_url.'css/'.$include.'">';
				}
			}
		?>
		<script type="text/javascript">var root = "<?php echo $base_url; ?>";</script>
		<script type="text/javascript">var language = "<?php echo $language; ?>";</script>
	</head>
	<body>