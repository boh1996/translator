<ul class="breadcrumb project-navigation" style="width:50%; min-width:410px; ">
	<li><a data-target=""><?= $this->lang->line("common_projects"); ?></a> <span class="divider">/</span></li>
	<li><a data-target="project/{{project_id}}">{{project_name}}</a> <span class="divider">/</span></li>
	<li><a data-target="project/{{project_id}}/{{language_id}}">{{language_name}}</a> <span class="divider">/</span></li>
	<li class="active"><strong>{{name}}</strong></li>
</ul>

<section class="well well-white" style="width:50%; padding-right:10px; margin-left:15%;">
	<div class="translations">
		{{#keys.length}}
			<form class="form-horizontal" id="translation_form">
				{{#keys}}
					<div class="language-key">

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
							<textarea class="field span12 translate-area" rows="1" placeholder="<?= $this->lang->line("front_enter_translation"); ?>">{{#translation}}{{translation}}{{/translation}}</textarea>
						</div>

						{{#tokens.length}}
							<div class="tokens">
								<table class="tokens-table">
									{{#tokens}}
										<tr>
											<td><a class="token">{{token}}</a></td>
											<td>-</td>
											<td><i>{{description}}</i></td>
										</tr>
									{{/tokens}}
								</table>
							</div>
						{{/tokens.length}}
					</div>
				{{/keys}}
			</form>

			<hr>

			<div class="submit-area">
				<div class="form-actions">
					<button class="btn btn-primary"><?= $this->lang->line("common_save"); ?></button>
				  	<a class="btn" data-target="project/{{project_id}}/{{language_id}}" ><?= $this->lang->line("common_cancel"); ?></a>
				</div>
			</div>
		{{/keys.length}}

		{{^keys}}
			<h2><?= $this->lang->line("front_no_keys_available"); ?></h2>
		{{/keys}}
	</div>
</section>