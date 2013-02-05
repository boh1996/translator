<div class="modal hide fade">
	<div class="modal-header">
		 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    	<h3><?= $this->lang->line("front_create_token"); ?></h3>
	</div>

	<div class="modal-body">
		<form class="form-horizontal" id="create_token_form">
			<fieldset class="control-group">
			    <label class="control-label" for="token_name"><?= $this->lang->line("front_token"); ?>:</label>
			    <div class="controls">
					<input type="text" class="input-large" required placeholder="<?= $this->lang->line("front_token"); ?>" name="token_name" id="token_name">
				</div>
			</fieldset>

			<fieldset class="control-group">
			    <label class="control-label" for="token_description"><?= $this->lang->line("front_description"); ?>:</label>
			    <div class="controls">
					<textarea id="token_description" placeholder="<?= $this->lang->line("front_description");?>" rows="3" class="input-large no-resize"></textarea>
				</div>
			</fieldset>
		</form>
	</div>

	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal"><?= $this->lang->line("common_close"); ?></a>
   		<a href="#" class="btn btn-primary" id="create_token_save_button"><?= $this->lang->line("common_create"); ?></a>
	</div>
</div>