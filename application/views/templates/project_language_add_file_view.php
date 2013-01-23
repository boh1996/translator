<ul class="breadcrumb project-navigation" style="width:50%; min-width:410px; ">
	<li><a data-target=""><?= $this->lang->line("common_projects"); ?></a> <span class="divider">/</span></li>
	<li><a data-target="project/{{project_id}}">{{project_name}}</a> <span class="divider">/</span></li>
	<li><a data-target="project/{{project_id}}/{{language_id}}">{{language_name}}</a> <span class="divider">/</span></li>
	<li class="active"><?= $this->lang->line("front_add_file"); ?></li>
</ul>

<section class="well well-white" style="width:50%; padding-right:10px; margin-left:15%;">
	<form class="form-horizontal" id="create_project_form">
	    <legend><?= $this->lang->line("front_add_language_file_to_project"); ?></legend>

	   	<div class="control-group">
		    <label class="control-label" for="file_name"><?= $this->lang->line("front_name"); ?>:</label>
		    <div class="controls">
				<input type="text" class="input-large" required placeholder="<?= $this->lang->line("front_name"); ?>" name="file_name" id="file_name">
			</div>
		</div>

		<div class="control-group">
		    <label class="control-label" for="file_location"><?= $this->lang->line("front_file_location"); ?>:</label>
		    <div class="controls">
				<input type="text" class="input-large" required placeholder="<?= $this->lang->line("front_file_location"); ?>" name="file_location" id="file_location">
			</div>
		</div>

		<div class="form-actions">
			<button class="btn btn-primary" id="create_project_save"><?= $this->lang->line("front_create"); ?></button>
		  	<a class="btn" data-target="" ><?= $this->lang->line("common_cancel"); ?></a>
		</div>
	</form>

</section>