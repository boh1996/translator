<section class="project-table">
	<h2 class="section-header"><?php echo $this->lang->line('pages_projects'); ?>:</h2>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo $this->lang->line('front_name'); ?></th>
				<?php if ($this->user_control->user->has_modes("edit")) : ?>
					<th><?php echo $this->lang->line('front_edit'); ?></th>
				<?php endif; ?>
				<?php if ($this->user_control->user->has_modes("delete")) : ?>
					<th><?php echo $this->lang->line('front_delete'); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
			<?php $i = 0; ?>
			<?php foreach ($projects as $project) : ?>
				<?php $i++; ?>
				<tr>
					<td><?= $i; ?></td>
					<td><a data-target="project/<?= $project["id"]; ?>"><?= $project["name"]; ?></a></td>
					<?php if ($this->user_control->user->has_modes("edit")) : ?>
						<td><a href="#" data-index="<?= $project["id"]; ?>" data-target="project/edit/<?= $project["id"]; ?>"><?php echo $this->lang->line('front_edit'); ?></a></td>
					<?php else : ?>
						<td></td>
					<?php endif; ?>
					<?php if ($this->user_control->user->has_modes("delete")) : ?>
						<td><a href="#" data-index="<?= $project["id"]; ?>" data-target="project/delete/<?= $project["id"]; ?>"><?php echo $this->lang->line('front_delete'); ?></a></td>
					<?php else : ?>
						<td></td>
					<?php endif; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
	</table>

	<?php if ($this->user_control->user->has_modes("create")) : ?>
		<div class="btn-group">
			<a class="btn span2" data-target="project/create" style="margin-left:10px;"><?php echo $this->lang->line('front_create_project'); ?></a>
		</div>
	<?php endif; ?>
</section>