<div class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?= $this->lang->line("front_confirm_delete"); ?></h3>
  </div>
  <div class="modal-body">
    <p><?= $this->lang->line("front_please_confirm_project_delete"); ?></p>
  </div>
  <div class="modal-footer">
    <a href="#" data-dismiss="modal" aria-hidden="true" class="btn"><?= $this->lang->line("common_close"); ?></a>
    <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-primary"><?= $this->lang->line("common_confirm"); ?></a>
  </div>
</div>