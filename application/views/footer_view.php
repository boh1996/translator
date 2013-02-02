		<div class="navbar navbar-fixed-bottom fixed">
		  <div class="navbar-inner">
		    <div class="container">
		 
		      	<!-- Be sure to leave the brand out there if you want it shown -->
		      	<a class="brand pull-right">
		      		<?php echo $this->lang->line('ui_copyright_line'); ?>
		      	</a>
		    </div>
		  </div>
		</div>
	
		<!-- Include jquery,boostrap and script -->
		<?php 
			if ($_SERVER["HTTP_HOST"] == "127.0.0.1" || $_SERVER["HTTP_HOST"] == "localhost") {
				echo '<script type="text/javascript" src="'.$asset_url.'js/jquery.min.js"></script>';
			} else {
				echo '<script type="text/javascript" src="'.$jquery_url.'"></script>';
			}
		?>
		<script type="text/javascript" src="<?php echo $asset_url; ?>js/wysihtml5.js"></script>
		<script type="text/javascript" src="<?php echo $asset_url; ?>bootstrap/js/bootstrap.js"></script>
		<script src="<?php echo $asset_url;?>js/mustache.js"></script>
		<script type="text/javascript" src="<?php echo $asset_url; ?>js/jquery.history.js"></script>
		<script type="text/javascript" src="<?php echo $asset_url; ?>js/signals.min.js"></script>
		<script type="text/javascript" src="<?php echo $asset_url; ?>js/crossroads.min.js"></script>
		<?php
			if ( isset($script_includes) ) {
				foreach ($script_includes as $script) {
					echo '<script src="'.$asset_url.'js/'.$script.'"></script>';
				}
			}
		?>
	</body>
</html>