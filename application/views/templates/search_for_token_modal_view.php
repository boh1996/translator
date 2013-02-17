<div class="modal hide fade">
	<div class="modal-header">
		 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    	<h3><?= $this->lang->line("front_search_for_token"); ?></h3>
	</div>

	<div class="modal-body" id="search_token_body">
		<form class="form-horizontal" >
			<fieldset class="control-group">
			    <label class="control-label" for="search_token"><?= $this->lang->line("front_token"); ?>:</label>
			    <div class="controls">
					<input type="text" class="input-large" required placeholder="<?= $this->lang->line("front_token"); ?>" name="search_token" id="search_token">
				</div>
			</fieldset>
		</form>
	</div>

	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal"><?= $this->lang->line("common_close"); ?></a>
	</div>
</div>