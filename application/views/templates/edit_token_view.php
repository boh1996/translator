<div class="modal hide fade" data-index="{{id}}">
	<div class="modal-header">
		 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    	<h3><?= $this->lang->line("front_edit_token"); ?></h3>
	</div>

	<div class="modal-body">
		<form class="form-horizontal" id="create_token_form">
			<fieldset class="control-group">
			    <label class="control-label" for="edit_token_name"><?= $this->lang->line("front_token"); ?>:</label>
			    <div class="controls">
					<input type="text" class="input-large" required placeholder="<?= $this->lang->line("front_token"); ?>" {{#token}}value="{{token}}"{{/token}} name="edit_token_name" id="edit_token_name">
				</div>
			</fieldset>

			<fieldset class="control-group">
			    <label class="control-label" for="edit_token_description"><?= $this->lang->line("front_description"); ?>:</label>
			    <div class="controls">
					<textarea id="edit_token_description" placeholder="<?= $this->lang->line("front_description");?>" rows="3" class="input-large no-resize">{{#description}}{{description}}{{/description}}</textarea>
				</div>
			</fieldset>
		</form>
	</div>

	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal"><?= $this->lang->line("common_close"); ?></a>
   		<a href="#" class="btn btn-primary" data-index="{{id}}" id="edit_token_save_button"><?= $this->lang->line("common_save"); ?></a>
	</div>
</div>