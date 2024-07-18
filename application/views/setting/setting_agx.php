
<div class="tab-pane fade" id="AGX">
	<?php
		$agx_on = $AGX_API == 1 ? 'btn-success' : '';
		$agx_off = $AGX_API == 0 ? 'btn-danger' : '';
	 ?>
	<form id="agxForm" method="post" action="<?php echo $this->home; ?>/update_config">
  	<div class="row">
			<div class="col-sm-4">
        <span class="form-control left-label">AGX API</span>
      </div>
      <div class="col-sm-8">
				<div class="btn-group">
					<button type="button" class="btn btn-sm <?php echo $agx_on; ?>" style="width:50%;" id="btn-agx-on" onClick="toggleAgxApi(1)">ON</button>
					<button type="button" class="btn btn-sm <?php echo $agx_off; ?>" style="width:50%;" id="btn-agx-off" onClick="toggleAgxApi(0)">OFF</button>
				</div>
				<input type="hidden" name="AGX_API" id="agx-api" value="<?php echo $AGX_API; ?>" />
				<span class="help-block">เปิดใช้งาน AGX API หรือไม่</span>
      </div>
      <div class="divider-hidden"></div>


      <div class="col-sm-4">
        <span class="form-control left-label">AGX WAREHOUSE</span>
      </div>
      <div class="col-sm-8">
				<select class="form-control input-sm input-large" id="agx-warehouse" name="AGX_WAREHOUSE">
					<option value="">Please Select</option>
					<?php echo select_sell_warehouse($AGX_WAREHOUSE); ?>
				</select>
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-4">
        <span class="form-control left-label">AGX ZONE</span>
      </div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-large" id="agx-zone" name="AGX_ZONE" value="<?php echo $AGX_ZONE; ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-4">
        <span class="form-control left-label">AGX CHANNELS</span>
      </div>
      <div class="col-sm-8">
				<select class="form-control input-sm input-large" id="agx-channels" name="AGX_CHANNELS">
					<option value="">Please Select</option>
					<?php echo select_channels($AGX_CHANNELS); ?>
				</select>
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-4">
        <span class="form-control left-label">AGX PAYMENT</span>
      </div>
      <div class="col-sm-8">
				<select class="form-control input-sm input-large" id="agx-payment" name="AGX_PAYMENT">
					<option value="">Please Select</option>
					<?php echo select_payment_method($AGX_PAYMENT); ?>
				</select>
      </div>
      <div class="divider-hidden"></div>




      <div class="col-sm-8 col-sm-offset-4">
				<?php if($this->pm->can_add OR $this->pm->can_edit) : ?> <?php //if($this->_SuperAdmin) : ?>
        <button type="button" class="btn btn-sm btn-success input-small" onClick="updateConfig('agxForm')">
          <i class="fa fa-save"></i> บันทึก
        </button>
				<?php endif; ?>
      </div>
      <div class="divider-hidden"></div>

  	</div><!--/ row -->
  </form>
</div>
