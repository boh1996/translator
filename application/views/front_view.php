<script type="text/javascript">var method = "<?php echo $method; ?>";</script>
<script type="text/javascript">
	var translations = {
		"error_sorry_error_occured" : '<?php echo $this->lang->line('errors_sorry_an_error_occured'); ?>',
		"error_no_permission" : '<?php echo $this->lang->line('errrors_no_permission'); ?>',
		"error_warining" : '<?php echo $this->lang->line('errors_warning'); ?>',
		"project_saved" : '<?php echo $this->lang->line('front_project_saved'); ?>',
		"error_project_found" : '<?php echo $this->lang->line('front_project_error_exists'); ?>',
		"error_no_project_found" : '<?php echo $this->lang->line('errors_sorry_no_project_found'); ?>',
		"error_no_translation" : '<?php echo $this->lang->line('errors_translation_empty'); ?>',
		"error_multiple_translation_fields_empty" : '<?php echo $this->lang->line('errors_one_or_more_translation_empty'); ?>',
		"data_saved" : '<?php echo $this->lang->line('front_data_has_been_saved'); ?>',
	};
</script>

<!--<div class="navbar navbar-fixed-top navbar-inverse">-->
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
 
	      <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        	<span class="icon-bar"></span>
        	<span class="icon-bar"></span>
        	<span class="icon-bar"></span>
      	</a>
 
      	<!-- Be sure to leave the brand out there if you want it shown -->
      	<a class="brand" href="#">
      		<?php echo $this->lang->line('ui_brand_name'); ?>
      	</a>

	    <!-- Everything you want hidden at 940px or less, place within here -->
	    <div class="nav-collapse">
	     	<ul class="nav">
	     		<li class="active">
		    		<a data-target="home" href="#"><?php echo $this->lang->line('pages_home'); ?></a>
		  		</li>
			</ul>

			<div class="pull-right">
				<ul class="nav">
				  	<li class="dropdown">
						<a class="dropdown-toggle" href="#" data-toggle="dropdown"><?= $this->user_control->user->name; ?>&nbsp;<strong class="caret"></strong></a>
						<ul class="dropdown-menu" role="menu">
			  				<li><a tabindex="-1" data-target="settings"><?php echo $this->lang->line('pages_settings'); ?></a></li>
						    <li class="divider"></li>
						    <li><a href="<?php echo $base_url; ?>logout"><?php echo $this->lang->line('user_logout'); ?></a></li>
			  			</ul>
					</li>
				</ul>
			</div>
      	</div>
 
    </div>
  </div>
</div>

<div class="wrapper">
	<div id="page">
		<div class="page-container">
			<?php if ($this->user_control->user->has_one_mode(array("edit","create","view","delete"))) : ?>
				<div id="project" class="disabled_page">
					
				</div>

				<div class="disabled_page" id="project_language">
					
				</div>

				<div class="disabled_page" id="language_key_edit">

				</div>

				<div class="disabled_page" id="language_file">

				</div>
			<?php endif; ?>

			<?php if ($this->user_control->user->has_modes("edit")) : ?>
				<div id="project_edit" class="disabled_page">
					
				</div>
			<?php endif; ?>

			<?php if ($this->user_control->user->has_modes("create")) : ?>
				<div id="project_create" class="disabled_page">
					<?= $this->user_control->LoadTemplate("create_project_view"); ?>
				</div>

				<div class="disabled_page" id="project_language_add_file">
					<?= $this->user_control->LoadTemplate("project_language_add_file_view"); ?>
				</div>

				<div class="disabled_page" id="project_add_language">
					<?= $this->user_control->LoadTemplate("project_add_language_view"); ?>
				</div>
			<?php endif; ?>

			<?php if ($this->user_control->user->has_one_mode(array("edit","create","view","delete"))) : ?>
				<div id="home" class="disabled_page">
					<?= $this->user_control->LoadTemplate("projects_view"); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<div id="loading" style="display:none;">
	<div id="floatingCirclesG">
		<div class="f_circleG" id="frotateG_01"></div>
		<div class="f_circleG" id="frotateG_02"></div>
		<div class="f_circleG" id="frotateG_03"></div>
		<div class="f_circleG" id="frotateG_04"></div>
		<div class="f_circleG" id="frotateG_05"></div>
		<div class="f_circleG" id="frotateG_06"></div>
		<div class="f_circleG" id="frotateG_07"></div>
		<div class="f_circleG" id="frotateG_08"></div>
	</div>
</div>

<div class="modal-backdrop in" style="display:none;" id="loading-background"></div>

<div style="display:none;">
	<?= $this->user_control->LoadTemplate("alerts_view"); ?>
</div>

<?php if ($this->user_control->user->has_one_mode(array("edit","create","view","delete"))) : ?>
	<script type="mustache/template" id="viewProjectTemplate">
		<?= $this->user_control->LoadTemplate("project_view"); ?>
	</script>

	<script type="mustache/template" id="projectLanguageFilesTemplate">
		<?= $this->user_control->LoadTemplate("project_language_files_view"); ?>
	</script>

	<script type="mustache/template" id="languageFileTemplate">
		<?= $this->user_control->LoadTemplate("language_file_view"); ?>
	</script>
<?php endif; ?>

<?php if ($this->user_control->user->has_modes("edit")) : ?>
	<script type="mustache/template" id="editProjectViewTemplate">
		<?= $this->user_control->LoadTemplate("project_edit_view"); ?>
	</script>

	<script type="mustache/template" id="editLanguageKeyTemplate">
		<?= $this->user_control->LoadTemplate("edit_language_key_view"); ?>
	</script>
<?php endif; ?>

<?php if ($this->user_control->user->has_modes("delete")) : ?>
	<script type="mustache/template" id="deleteProjectConfirmModalTemplate">
		<?= $this->user_control->LoadTemplate("delete_confirm_view"); ?>
	</script>
<?php endif; ?>