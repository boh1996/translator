<ul class="breadcrumb project-navigation view-navigation view-navigation-view-padding">
	<li><a data-target=""><?= $this->lang->line("common_projects"); ?></a> <span class="divider">/</span></li>
	<li class="active">{{name}}</li>
</ul>

<section class="well well-white inner-view view-padding">
	{{#languages.length}}
	<table class="table table-condensed">
		<thead>
			<tr>
				<th>#</th>
				<th><?= $this->lang->line("common_language"); ?></th>
				<th><?= $this->lang->line("front_delete"); ?></th>
			<tr>
		</thead>

		<tbody>
			{{#languages}}
				<tr>
					<td>{{count}}</td>
					<td><a data-target="project/{{project_id}}/{{id}}">{{name}}</a></td>
					<td><a data-target="project/{{project_id}}/delete/language/{{id}}"><?= $this->lang->line("front_delete"); ?></a></td>
					{{#progress}}
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
					{{/progress}}
				</tr>
			{{/languages}}
		</tbody>
	</table>
	{{/languages.length}}

	<!-- Check for user project role -->
	<div class="btn-group">
		<a class="btn span2" data-target="project/{{id}}/add/language" style="margin-left:10px;"><?php echo $this->lang->line('front_add_language'); ?></a>
	</div>

</section>