<ul class="breadcrumb project-navigation" style="width:50%; min-width:410px; ">
	<li><a data-target=""><?= $this->lang->line("common_projects"); ?></a> <span class="divider">/</span></li>
	<li class="active"><?= $this->lang->line("front_edit_project_title"); ?></li>
</ul>

<section class="well" style="width:400px; margin-left:15%;">
	
	<form class="form-horizontal" id="edit_project_form">
	    <legend><?= $this->lang->line("front_edit_project"); ?></legend>

	    <input type="hidden" value="{id}">

	    <div class="control-group">
		    <label class="control-label" for="project_name"><?= $this->lang->line("front_name"); ?>:</label>
		    <div class="controls">
				<input type="text" class="input-large" required value="{{name}}" placeholder="<?= $this->lang->line("front_name"); ?>" name="edit_project_name" id="edit_project_name">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="k"><?= $this->lang->line("front_location"); ?>:</label>
			<div class="controls">
				<input class="input-large" type="text" name="edit_project_location" value="{{location}}" placeholder="<?= $this->lang->line("front_location"); ?>" id="edit_project_location">
			</div>
		</div>

		<div class="form-actions">
			<button class="btn btn-primary" id="edit_project_save"><?= $this->lang->line("common_save"); ?></button>
		  	<a class="btn" data-target="" ><?= $this->lang->line("common_cancel"); ?></a>
		</div>
	</form>

</section>