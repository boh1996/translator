<script type="text/javascript">var method = "<?php echo $method; ?>";</script>
<script type="text/javascript">
	var translations = {
		"error_sorry_error_occured" : '<?php echo $this->lang->line('errors_sorry_an_error_occured'); ?>',
	};
</script>

<!--<div class="navbar navbar-fixed-top navbar-inverse">-->
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
 
	      <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        	<span class="icon-bar"></span>
        	<span class="icon-bar"></span>
        	<span class="icon-bar"></span>
      	</a>
 
      	<!-- Be sure to leave the brand out there if you want it shown -->
      	<a class="brand" href="#">
      		<?php echo $this->lang->line('ui_brand_name'); ?>
      	</a>

	    <!-- Everything you want hidden at 940px or less, place within here -->
	    <div class="nav-collapse">
	     	<ul class="nav">
	     		<li class="active">
		    		<a data-target="home" href="#"><?php echo $this->lang->line('pages_home'); ?></a>
		  		</li>
			</ul>

			<div class="pull-right">
				<ul class="nav">
				  	<li class="dropdown">
						<a class="dropdown-toggle" href="#" data-toggle="dropdown">{user_name}&nbsp;<strong class="caret"></strong></a>
						<ul class="dropdown-menu" role="menu">
			  				<li><a tabindex="-1" data-target="settings"><?php echo $this->lang->line('pages_settings'); ?></a></li>
						    <li class="divider"></li>
						    <li><a href="<?php echo $base_url; ?>logout"><?php echo $this->lang->line('user_logout'); ?></a></li>
			  			</ul>
					</li>
				</ul>
			</div>
      	</div>
 
    </div>
  </div>
</div>

<!--<div id="error_container"></div>-->
<div class="wrapper">
	<div id="page">
		<div class="page-container">

			<?php if ($this->user_control->user->has_one_mode(array("edit","create","view","delete"))) : ?>
				<div id="project" class="disabled_page">
					Project {id}
				</div>
			<?php endif; ?>

			<?php if ($this->user_control->user->has_modes("edit")) : ?>
				<div id="project_edit" class="disabled_page">
					Project Edit
				</div>
			<?php endif; ?>

			<?php if ($this->user_control->user->has_modes("create")) : ?>
				<div id="project_create" class="disabled_page">
					Project Create
				</div>
			<?php endif; ?>

			<div id="home" >
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
			</div>
		</div>
	</div>
</div>

<div id="loading" style="display:none;">
	<div id="floatingCirclesG">
		<div class="f_circleG" id="frotateG_01"></div>
		<div class="f_circleG" id="frotateG_02"></div>
		<div class="f_circleG" id="frotateG_03"></div>
		<div class="f_circleG" id="frotateG_04"></div>
		<div class="f_circleG" id="frotateG_05"></div>
		<div class="f_circleG" id="frotateG_06"></div>
		<div class="f_circleG" id="frotateG_07"></div>
		<div class="f_circleG" id="frotateG_08"></div>
	</div>
</div>

<div class="modal-backdrop in" style="display:none;" id="loading-background"></div>

<?php if ($this->user_control->user->has_modes("delete")) : ?>
	<script type="mustache/template" id="deleteProjectConfirmModalTemplate">
		<?= $this->user_control->LoadTemplate("delete_confirm_view"); ?>
	</script>
<?php endif; ?>