<input type="hidden" id="language_key_fproject_id" value="{{project.id}}">
<input type="hidden" id="language_key_flanguage_id" value="{{language.id}}">
<input type="hidden" id="language_key_file_id" value="{{file.id}}">

<div class="container-fluid">
 	<div class="row-fluid">
		<div class="span12">
			<ul class="breadcrumb">
				<li><a data-target=""><?= $this->lang->line("common_projects"); ?></a> <span class="divider">/</span></li>
				<li><a data-target="project/{{project.id}}">{{project.name}}</a> <span class="divider">/</span></li>
				<li><a data-target="project/{{project.id}}/{{language.id}}/{{file.id}}">{{file.name}}</a> <span class="divider">/</span></li>
				<li class="active"><strong><?= $this->lang->line("front_add_key"); ?></strong></li>
			</ul>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span6">
			<div class="well">
				<form class="form-horizontal" id="create_language_key_form">
				    <legend><?= $this->lang->line("front_add_language_key_to_project"); ?></legend>

				   	<fieldset class="control-group">
					    <label class="control-label span4" for="key_name"><?= $this->lang->line("front_key"); ?>:</label>
					    <div class="controls">
							<input type="text" class="input-large span10" required placeholder="<?= $this->lang->line("front_key"); ?>" name="key_name" id="key_name">
						</div>
					</fieldset>

					<fieldset class="control-group">
					    <label class="control-label span4" for="key_description"><?= $this->lang->line("front_description"); ?>:</label>
					    <div class="controls">
							<textarea id="key_description" placeholder="<?= $this->lang->line("front_description");?>" rows="3" class="input-large no-resize span10"></textarea>
						</div>
					</fieldset>

					<fieldset class="control-group">
					    <label class="control-label span4"><?= $this->lang->line("front_approve_first"); ?>:</label>
					    <div class="controls">
							<div class="btn-group" data-toggle="buttons-radio" id="approve_first_group">
							  	<button type="button" class="btn btn-success">Yes</button>
							 	<button type="button" class="btn btn-danger active" data-toggle="button">No</button>
							</div>
						</div>
					</fieldset>

					<div class="form-actions">
						<button type="submit" class="btn btn-primary" id="create_language_key"><?= $this->lang->line("front_create"); ?></button>
					  	<a class="btn" data-target="project/{{project.id}}/{{language.id}}/{{file.id}}" ><?= $this->lang->line("common_cancel"); ?></a>
					</div>
				</form>
			</div>
		</div>


		<div class="span5 offset1">
			<div class="well">
				<table class="tokens pull-down" id="create_language_key_tokens" cellspacing="10">
					<tr data-index="1"></tr>
					<tr data-index="2"></tr>
				</table>
				<a class="btn btn-primary btn-block" id="add_language_keycreate_token_button"><?= $this->lang->line("front_create_token"); ?></a>
			</div>
		</div>
	</div>
</div>