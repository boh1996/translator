<ul class="breadcrumb project-navigation view-nagivation view-navigation-view-padding">
	<li><a data-target=""><?= $this->lang->line("common_projects"); ?></a> <span class="divider">/</span></li>
	<li><a data-target="project/{{project_id}}">{{project_name}}</a> <span class="divider">/</span></li>
	<li><a data-target="project/{{project_id}}/{{language_id}}">{{language_name}}</a> <span class="divider">/</span></li>
	<li class="active"><strong>{{name}}</strong></li>
</ul>

<input type="hidden" id="language_id" value="{{language_id}}">
<input type="hidden" id="project_id" value="{{project_id}}">

<section class="well well-white inner-view view-padding">
	<div class="translations">
		{{#keys.length}}
			<form class="form-horizontal" id="translation_form">
				{{#keys}}
					<div class="language-key" data-index="{{id}}">

						{{#base_translation}}
							<h3>{{base_translation}}</h3>
						{{/base_translation}}

						{{^base_translation}}
							<h3>{{key}}</h3>
						{{/base_translation}}

						{{#description}}
							<p class="key-description">{{description}}</p>
						{{/description}}

						{{^description}}
							<p class="key-description"><i><?= $this->lang->line("front_no_description_available"); ?></i></p>
						{{/description}}

						<div class="translate-area-container">
							<div class="row-fluid">
								<div class="span10">
									<textarea class="field span12 translate-field" rows="1" placeholder="<?= $this->lang->line("front_enter_translation"); ?>">{{#translation}}{{translation.translation}}{{/translation}}</textarea>
								</div>	

								{{#approve_first}}
								  	<div class="span2">	
										<div class="btn-group approve-decline" data-toggle="buttons-radio">
											<!-- Approve access -->
											{{#translation.approved}}
												<button type="button" data-key-index="{{id}}" {{#translation}} data-translation-id="{{translation.id}}" {{/translation}} data-toggle="button" class="btn btn-primary approve active"><i class="icon-ok icon-white"></i></button>
												<button type="button" data-key-index="{{id}}" {{#translation}} data-translation-id="{{translation.id}}" {{/translation}} class="btn decline"><i class="icon-ban-circle"></i></button>
											{{/translation.approved}}

											{{^translation.approved}}
												<button type="button" data-key-index="{{id}}" {{#translation}} data-translation-id="{{translation.id}}" {{/translation}} class="btn btn-primary approve"><i class="icon-ok icon-white"></i></button>
												<button type="button" data-key-index="{{id}}" {{#translation}} data-translation-id="{{translation.id}}" {{/translation}} data-toggle="button" class="btn decline active"><i class="icon-ban-circle"></i></button>
											{{/translation.approved}}
										</div>
								  	</div>
							  	{{/approve_first}}
							</div>
						</div>

						{{#tokens.length}}
							<div class="tokens">
								<table class="tokens-table">
									{{#tokens}}
										<tr class="active-token">
											<td><a class="token" data-index="{{id}}" data-token="{{token}}">{{token}}</a></td>
											<td><strong>-</strong></td>
											<td><i>{{description}}</i></td>
										</tr>
									{{/tokens}}
								</table>
							</div>
						{{/tokens.length}}

						<div class="key">
							<strong class="space-right"><?= $this->lang->line("front_translation_key"); ?>:</strong><em>{{key}}</em>
						</div>

						<div class="language-key-edit">
							<a class="btn save-translation btn-primary" data-index="{{id}}"><?= $this->lang->line("common_save"); ?></a>
							<a class="btn" data-target="language/key/{{id}}/edit"><?= $this->lang->line("front_edit"); ?></a>
							<a class="btn" data-target="language/key/{{id}}/delete"><?= $this->lang->line("front_delete"); ?></a>
						</div>
					</div>

					<hr>
				{{/keys}}
			</form>

			<div class="submit-area">
				<div class="form-actions">
					<button class="btn btn-primary save-translations"><?= $this->lang->line("common_save"); ?></button>
				  	<a class="btn" data-target="project/{{project_id}}/{{language_id}}" ><?= $this->lang->line("common_cancel"); ?></a>
				  	<a class="btn" data-target="project/{{project_id}}/{{language_id}}/{{file_id}}/add/key" ><?= $this->lang->line("front_add_key"); ?></a>
				</div>
			</div>
		{{/keys.length}}

		{{^keys}}
			<h2><?= $this->lang->line("front_no_keys_available"); ?></h2>
		{{/keys}}
	</div>
</section>