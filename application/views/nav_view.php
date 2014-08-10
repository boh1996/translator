<!--<div class="navbar navbar-fixed-top navbar-inverse">-->
<div class="navbar navbar-fixed-top navbar-inverse">
  <div class="navbar-inner">
    <div class="container">
	      <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
        	<span class="icon-th-list"></span>

      	</a>
      	<!-- Be sure to leave the brand out there if you want it shown -->
      	<a class="brand" href="#">
      		<?php echo $this->lang->line('ui_brand_name'); ?>
      	</a>

	    <!-- Everything you want hidden at 940px or less, place within here -->
	    <div class="nav-collapse navbar-responsive-collapse">
	     	<ul class="nav">
	     		<li class="active">
		    		<a data-target="home" href="#"><?php echo $this->lang->line('pages_home'); ?></a>
		  		</li>
			</ul>

			<ul class="nav pull-right">
			  	<li class="dropdown">
					<a class="dropdown-toggle" href="#" data-toggle="dropdown"><?= $this->user_control->user->name; ?>&nbsp;<strong class="caret"></strong></a>
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