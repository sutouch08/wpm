<?php
$pm = get_permission('SOREST', get_cookie('uid'), get_cookie('id_profile')); //--- ย้อนสถานะออเดอร์ได้หรือไม่
$px	= get_permission('SORECT', get_cookie('uid'), get_cookie('id_profile')); //--- ย้อนสถานะออเดอร์ที่เปิดบิลแล้วได้หรือไม่
$pc = get_permission('SOREUP', get_cookie('uid'), get_cookie('id_profile')); //--- ปล่อยออเดอร์ที่ยังไม่ชำระเงิน (เงินสด)
$pr = get_permission('SOREPR', get_cookie('uid'), get_cookie('id_profile')); //--- ปล่อยออเดอร์ได้หรือไม่

$canSetPrepare = ($pr->can_add + $pr->can_edit + $pr->can_delete) > 0 ? TRUE : FALSE;
$canChange	= ($pm->can_add + $pm->can_edit + $pm->can_delete) > 0 ? TRUE : FALSE;
$canUnbill	= ($px->can_add + $px->can_edit + $px->can_delete) > 0 ? TRUE : FALSE;
$canSkip = ($pc->can_add + $pc->can_edit + $pc->can_delete) > 0 ? TRUE : FALSE;

 ?>
<div class="row" style="padding:15px;">
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 padding-5">
    	<table class="table" style="margin-bottom:0px;">
        <?php if( $this->pm->can_add OR $this->pm->can_edit OR $this->pm->can_delete ) : ?>
        	<tr>
            	<td class="width-25 middle text-right" style="border:0px; padding:5px;">Status : </td>
                <td class="width-50" style="border:0px; padding:5px;">
                	<select class="form-control input-sm" style="padding-top:0px; padding-bottom:0px;" id="stateList">
                    	<option value="0">Select</option>
							<?php if($this->_SuperAdmin) : ?>
											<option value="1">Pending</option>
											<option value="2">Waiting for payments</option>
											<option value="3">Waiting to pick</option>
											<option value="7">Ready to ship</option>
											<option value="9">Cancelled</option>
							<?php elseif( $order->state != 9 && $order->is_expired == 0 && $order->status == 1) : ?>

                 <?php if( $order->state <=3) : ?>
                        <?php if($order->state != 1): ?>
													<option value="1">Pending</option>
												<?php endif; ?>

												<?php if($order->state != 2 && $order->is_term == 0) : ?>
                        <option value="2">Waiting for payments</option>
												<?php endif; ?>

												<?php if($order->state != 3 && $order->role == 'S') : ?>

													<?php /*if($order->is_paid == 1 OR $order->is_term == 1 OR $canSkip) : ?>
                        		<option value="3">รอจัดสินค้า</option>
													<?php endif; */?>

													<?php if($order->is_paid == 1 OR $canSetPrepare OR $canSkip) : ?>
                        		<option value="3">Waiting to pick</option>
													<?php endif; ?>

												<?php elseif($order->state != 3 && $order->is_approved == 1) : ?>
														<option value="3">Waiting to pick</option>
												<?php endif; ?>

								 <?php elseif($order->state > 3 && $order->state < 8 && $canChange ) : ?>
											 <option value="1">Pending</option>
											 <option value="2">Waiting for payments</option>
											 <option value="3">Waiting to pick</option>
								 <?php elseif($order->state > 3 && $order->state >= 8 && $canUnbill ) : ?>
                       <option value="1">Pending</option>
                       <option value="2">Waiting for payments</option>
                       <option value="3">Waiting to pick</option>
								 <?php endif; ?>

                 <?php if( $order->state < 8 && $this->pm->can_delete ) : ?>
                        <option value="9">Cancelled</option>
								 <?php elseif( $order->state >= 8 && $canUnbill) : ?>
												<option value="9">Cancelled</option>
                 <?php endif; ?>

							<?php elseif($order->is_expired == 1 && $this->pm->can_delete) : ?>
												<option value="9">Cancelled</option>
							<?php elseif($order->state == 9 && $this->pm->can_edit) : ?>
												<option value="1">Pending</option>
							<?php endif; ?>
                    </select>
                </td>
                <td class="width-25" style="border:0px; padding:5px;">
                <?php if( $order->status == 1 && $order->is_expired == 0 ) : ?>
                	<button class="btn btn-xs btn-primary btn-block" onclick="changeState()">Change Status</button>
								<?php elseif($order->is_expired == 1 && $this->pm->can_delete) : ?>
									<button class="btn btn-xs btn-primary btn-block" onclick="changeState()">Change Status</button>
								<?php elseif($order->state == 9 && $this->pm->can_delete) : ?>
									<button class="btn btn-xs btn-primary btn-block" onclick="changeState()">Change Status</button>
                <?php endif; ?>
                </td>
            </tr>
       <?php else : ?>
       			<tr>
            	<td class="width-30 text-center" style="border:0px;">Status</td>
              <td class="width-40 text-center" style="border:0px;">User</td>
              <td class="width-30 text-center" style="border:0px;">Time stamp</td>
            </tr>
       <?php endif; ?>
      </table>
	</div>

  <?php $link = $order->state == 9 ? 'onclick="showReason()"' : ''; ?>
  <?php $pointer = $order->state == 9 ? 'pointer' : ''; ?>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5 font-size-14 <?php echo $pointer; ?>"
    <?php echo $link; ?>	style="height: 49px; border:solid 2px white; <?php echo state_color($order->state); ?>"	>
		<center>Current status</center>
		<center><?php echo get_state_name($order->state); ?></center>
	</div>


<?php if( !empty($state) ) : ?>
  <?php foreach($state as $rs) : ?>
	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5 font-size-10" style="height: 49px; border:solid 2px white; white-space: nowrap; overflow: hidden; <?php echo state_color($rs->state); ?>" >
    <center><?php echo get_state_name($rs->state); ?></center>
    <center><?php echo $this->user_model->get_name($rs->update_user); ?></center>
    <center><?php echo thai_date($rs->date_upd,TRUE, '/'); ?></center>
  </div>
<?php	endforeach; ?>
<?php endif; ?>
</div>


<div class="modal fade" id="cancle-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog" style="max-width:95%; margin-left:auto; margin-right:auto;">
   <div class="modal-content">
       <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
       <h4 class="modal-title">Reason for cancellation</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type="text" class="form-control input-sm" id="cancle-reason" maxlength="100" value=""/>
            <input type="hidden" id="cancle-code" value="" />
          </div>
        </div>
       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-info" onclick="doCancle()">Submit</button>
      </div>
   </div>
 </div>
</div>

<div class="modal fade" id="cancle-reason-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog" style="max-width:800px;">
   <div class="modal-content">
       <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
       <h4 class="modal-title">Reason for cancellation</h4>
      </div>
      <div class="modal-body" style="border-top:solid 1px #CCC; padding:13px; padding-top:0px;">
        <div class="row">
          <div class="col-sm-12 no-padding">
            <table class="table table-bordered">
							<thead>
	            	<tr>
	            		<th class="width-60">Reason</th>
									<th class="width-20">User</th>
									<th class="width-20">Time stamp</th>
	            	</tr>
							</thead>
							<tbody>
							<?php if(!empty($cancle_reason)) : ?>
								<?php foreach($cancle_reason as $reason) : ?>
									<tr>
										<td><?php echo $reason->reason; ?></td>
										<td><?php echo $this->user_model->get_name($reason->user); ?></td>
										<td><?php echo thai_date($reason->cancle_date, TRUE); ?></td>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr><td colspan="3" class="text-center">--No reason found--</td></tr>
							<?php endif; ?>
							</tbody>
            </table>
          </div>
        </div>

       </div>

   </div>
 </div>
</div>
