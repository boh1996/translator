<section class="project-table">
	<h2 class="section-header"><?php echo $this->lang->line('pages_projects'); ?>:</h2>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo $this->lang->line('front_name'); ?></th>
				<th><?php echo $this->lang->line('front_edit'); ?></th>
				<th><?php echo $this->lang->line('front_delete'); ?></th>
			</tr>
		</thead>
		<tbody>

			{{#projects}}
				<tr>
					<td>{{number}}</td>

					<td>
						<a data-target="project/{{id}}">{{name}}</a>
					</td>
					
					{{#edit}}
						<td>
							<a href="#" data-index="{{id}}" data-target="project/edit/{{id}}"><?php echo $this->lang->line('front_edit'); ?></a>
						</td>
					{{/edit}}

					{{^edit}}
						<td></td>
					{{/edit}}

					{{#delete}}
						<td>
							<a href="#" data-index="{{id}}" data-target="project/delete/{{id}}"><?php echo $this->lang->line('front_delete'); ?></a>
						</td>
					{{/delete}}
					
					{{^delete}}
						<td></td>
					{{/delete}}
				</tr>
			{{/projects}}

			</tbody>
	</table>

	<div class="btn-group">
		<a class="btn span2" data-target="project/create" style="margin-left:10px;"><?php echo $this->lang->line('front_create_project'); ?></a>
	</div>
</section>