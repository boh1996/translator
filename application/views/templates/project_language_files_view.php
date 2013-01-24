<ul class="breadcrumb project-navigation" style="width:50%; min-width:410px; ">
	<li><a data-target=""><?= $this->lang->line("common_projects"); ?></a> <span class="divider">/</span></li>
	<li><a data-target="project/{{project_id}}">{{name}}</a> <span class="divider">/</span></li>
	<li class="active">{{language_name}}</li>
</ul>

<section class="well well-white" style="width:50%; padding-right:10px; margin-left:15%;">
	{{#files.length}}
		<table class="table table-condensed">
			<thead>
				<tr>
					<th>#</th>
					<th><?= $this->lang->line("front_file"); ?></th>
					<th><?= $this->lang->line("front_edit"); ?></th>
					<th><?= $this->lang->line("front_delete"); ?></th>
					<th><?= $this->lang->line("front_progress"); ?></th>
				<tr>
			</thead>

			<tbody>
				{{#files}}
					<tr>
						<td>{{count}}</td>
						<td><a data-target="project/{{project_id}}/{{language_id}}/{{id}}">{{name}}</a></td>
						<td><a data-target="project/{{project_id}}/{{language_id}}/{{id}}/edit"><?= $this->lang->line("front_edit"); ?></a></td>
						<td><a data-target="project/{{project_id}}/{{language_id}}/{{id}}/delete"><?= $this->lang->line("front_delete"); ?></a></td>
						<td>
							<div class="progress">
								{{#progress.done}}
									<div class="bar bar-success" style="width: {{progress.done}}%;" rel="tooltip" data-placement="top" data-original-title="<?= $this->lang->line("front_progress_done_tooltip"); ?>"></div>
								{{/progress.done}}
								{{#progress.missing_approval}}
							  		<div class="bar bar-warning" style="width: {{progress.missing_approval}}%;" rel="tooltip" data-placement="top" data-original-title="<?= $this->lang->line("front_progress_missing_approval_tooltip"); ?>"></div>
							  	{{/progress.missing_approval}}
							  	{{#progress.missing}}
							  		<div class="bar bar-danger" style="width: {{progress.missing}}%;" rel="tooltip" data-placement="top" data-original-title="<?= $this->lang->line("front_progress_missing_tooltip"); ?>"></div>
							  	{{/progress.missing}}
							</div>
						</td>
					</tr>
				{{/files}}
			</tbody>
		</table>
	{{/files.length}}

	<!-- Check for user project role -->
	<div class="btn-group">
		<a class="btn span2" data-target="project/{{project_id}}/{{language_id}}/add/file" style="margin-left:10px;"><?php echo $this->lang->line('front_add_file'); ?></a>
	</div>

</section>