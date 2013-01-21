<ul class="breadcrumb project-navigation" style="width:50%; min-width:410px; ">
	<li><a data-target=""><?= $this->lang->line("common_projects"); ?></a> <span class="divider">/</span></li>
	<li class="active">{{name}}</li>
</ul>

<section class="well well-white" style="width:50%; padding-right:10px; margin-left:15%;">
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