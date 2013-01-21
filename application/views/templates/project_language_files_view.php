<ul class="breadcrumb project-navigation" style="width:50%; min-width:410px; ">
	<li><a data-target=""><?= $this->lang->line("common_projects"); ?></a> <span class="divider">/</span></li>
	<li><a data-target="project/{{project_id}}">{{project_name}}</a> <span class="divider">/</span></li>
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
			<tr>
		</thead>

		<tbody>
			{{#fiLes}}
				<tr>
					<td>{{count}}</td>
					<td><a data-target="">{{name}}</a></td>
					<td><a data-target=""><?= $this->lang->line("front_edit"); ?></a></td>
					<td><a data-target=""><?= $this->lang->line("front_delete"); ?></a></td>
				</tr>
			{{/files}}
		</tbody>
	</table>
	{{/files.length}}

	<!-- Check for user project role -->
	<div class="btn-group">
		<a class="btn span2" data-target="" style="margin-left:10px;"><?php echo $this->lang->line('front_add_file'); ?></a>
	</div>

</section>