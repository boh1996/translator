<ul class="breadcrumb project-navigation" style="width:50%; min-width:410px; ">
	<li class="active"><strong><?= $this->lang->line("front_edit_language_key_title"); ?></strong></li>
</ul>

<section class="well" style="width:400px; margin-left:15%;">
	
	<form class="form-horizontal" id="edit_language_key_form">
	    <legend><?= $this->lang->line("front_edit_language_key"); ?></legend>

	    <input type="hidden" value="{id}">

	    <div class="control-group">
		    <label class="control-label" for="edit_language_key"><?= $this->lang->line("front_key"); ?>:</label>
		    <div class="controls">
				<input type="text" class="input-large" required value="{{name}}" placeholder="<?= $this->lang->line("front_key"); ?>" name="edit_language_key" id="edit_language_key">
			</div>
		</div>

		<div class="form-actions">
			<button class="btn btn-primary" id="edit_language_key_save"><?= $this->lang->line("common_save"); ?></button>
		  	<a class="btn" data-target="-back" ><?= $this->lang->line("common_cancel"); ?></a>
		</div>
	</form>

</section>