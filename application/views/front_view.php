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
		"create_token_missing_token_name" : '<?php echo $this->lang->line('front_create_token_missing_token_name'); ?>',
		"token_created" : '<?php echo $this->lang->line('front_token_created'); ?>',
		"language_key_created" : '<?php echo $this->lang->line('front_language_key_created'); ?>',
		"token_updated" : '<?php echo $this->lang->line('front_token_updated'); ?>',
		"token_added" : '<?php echo $this->lang->line('front_token_added'); ?>',
		"token_not_found" : '<?php echo $this->lang->line('front_token_not_found'); ?>'
	};

	var language = <?= json_encode($this->lang->language); ?>;
</script>

<script type="text/javascript">
	var token = "LAL123";
</script>

<script type="text/javascript">
	document.write('<script src="'+ location.origin + ':35729/livereload.js?snipver=2"><' + '/script>');
</script>

<?= $this->load->view("nav_view",true); ?>

<div class="wrapper">
	<div id="page">
		<div class="container page-container">
			<div id="project" class="disabled_page">
				
			</div>

			<div class="disabled_page" id="project_language">
				
			</div>

			<div class="disabled_page" id="language_key_edit">

			</div>

			<div class="disabled_page" id="language_file">

			</div>

			<div id="project_edit" class="disabled_page">
				
			</div>

			<div id="add_language_key" class="disabled_page">

			</div>

			<div id="error_page" class="disabled_page"></div>

			<div id="project_create" class="disabled_page">
				<?= $this->user_control->LoadTemplate("create_project_view"); ?>
			</div>

			<div class="disabled_page" id="project_language_add_file">
				
			</div>

			<div class="disabled_page" id="project_add_language">
				<?= $this->user_control->LoadTemplate("project_add_language_view"); ?>
			</div>

			<div id="home" class="disabled_page">
				
			</div>
		</div>
	</div>
</div>

<?= $this->load->view("loading_view",true); ?>

<div style="display:none;">
	<?= $this->user_control->LoadTemplate("alerts_view"); ?>
</div>

<script type="mustache/template" id="projectAddFileTemplate">
	<?= $this->user_control->LoadTemplate("project_language_add_file_view"); ?>
</script>

<script type="mustache/template" id="errorTemplate">
	<?= $this->user_control->LoadTemplate("error_view"); ?>
</script>

<script type="mustache/template" id="projectsTemplate">
	<?= $this->user_control->LoadTemplate("projects_view"); ?>
</script>

<script type="mustache/template" id="viewProjectTemplate">
	<?= $this->user_control->LoadTemplate("project_view"); ?>
</script>

<script type="mustache/template" id="projectLanguageFilesTemplate">
	<?= $this->user_control->LoadTemplate("project_language_files_view"); ?>
</script>

<script type="mustache/template" id="languageFileTemplate">
	<?= $this->user_control->LoadTemplate("language_file_view"); ?>
</script>

<script type="mustache/template" id="editProjectViewTemplate">
	<?= $this->user_control->LoadTemplate("project_edit_view"); ?>
</script>

<script type="mustache/template" id="addLanguageKeyTemplate">
	<?= $this->user_control->LoadTemplate("add_language_key_view"); ?>
</script>

<script type="mustache/template" id="editLanguageKeyTemplate">
	<?= $this->user_control->LoadTemplate("edit_language_key_view"); ?>
</script>

<script type="mustache/template" id="deleteProjectConfirmModalTemplate">
	<?= $this->user_control->LoadTemplate("delete_confirm_view"); ?>
</script>

<script type="musctache/template" id="tokenTemplate">
	<?= $this->user_control->LoadTemplate("token_view"); ?>
</script>

<script type="musctache/template" id="createTokenModalTemplate">
	<?= $this->user_control->LoadTemplate("create_token_modal_view"); ?>
</script>

<script type="musctache/template" id="editTokenModalTemplate">
	<?= $this->user_control->LoadTemplate("edit_token_view"); ?>
</script>

<script type="musctache/template" id="searchTokenModalTemplate">
	<?= $this->user_control->LoadTemplate("search_for_token_modal_view"); ?>
</script>