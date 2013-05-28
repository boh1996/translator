<ul class="breadcrumb project-navigation view-navigation view-navigation-view-padding">
	<li><a data-target=""><?= $this->lang->line("common_projects"); ?></a> <span class="divider">/</span></li>
	<li><a data-target="project/{{project.id}}">{{project.name}}</a> <span class="divider">/</span></li>
	<li class="active"><?= $this->lang->line("front_add_language_to_project"); ?></li>
</ul>

<section class="well well-white inner-view view-padding">
	<form class="form-horizontal" id="add_language_to_project_form">
		<input type="hidden" id="add_language_to_project_id" value="{{project.id}}">

	    <legend><?= $this->lang->line("front_add_language_to_project_name"); ?></legend>

	   	<div class="control-group">
		    <label class="control-label" for="language_name"><?= $this->lang->line("front_language"); ?>:</label>
		    <div class="controls">
				<input type="text" class="input-large" autocomplete="off" required placeholder="<?= $this->lang->line("front_name"); ?>" name="language_name" id="language_name">
			</div>
		</div>
		
		<div class="form-actions">
			<button class="btn btn-primary" id="add_language_to_project_save"><?= $this->lang->line("front_add"); ?></button>
		  	<a class="btn" data-target="" ><?= $this->lang->line("common_cancel"); ?></a>
		</div>
	</form>
</section>