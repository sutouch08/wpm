
<div class="tab-pane fade active in" id="general">
	<?php
	    $noti_yes = $NOTI_BAR == 1 ? 'btn-success' : '';
	    $noti_no  = $NOTI_BAR == 0 ? 'btn-danger' : '';
	?>
	<form id="generalForm" method="post" action="<?php echo $this->home; ?>/update_config">
    <div class="row">
			<div class="col-sm-3"><span class="form-control left-label">Notification bar</span></div>
	    <div class="col-sm-9">
	      <div class="btn-group input-medium">
	        <button type="button" class="btn btn-sm <?php echo $noti_yes; ?>" style="width:50%;" id="btn-noti-yes" onClick="toggleNotiBars(1)">ON</button>
	        <button type="button" class="btn btn-sm <?php echo $noti_no; ?>" style="width:50%;" id="btn-noti-no" onClick="toggleNotiBars(0)">OFF</button>
	      </div>
	      <span class="help-block">เปิด/ปิด ระบบแจ้งเตือนบนเมนูบาร์ด้านบน</span>
	      <input type="hidden" name="NOTI_BAR" id="noti-bar" value="<?php echo $NOTI_BAR; ?>" />
	    </div>
	    <div class="divider-hidden"></div>

			<div class="col-sm-9 col-sm-offset-3">
        <?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
      	<button type="button" class="btn btn-sm btn-success" onClick="updateConfig('generalForm')"><i class="fa fa-save"></i> Save</button>
        <?php endif; ?>
      </div>
      <div class="divider-hidden"></div>
    </div><!--/row-->
  </form>
</div>
