<div class="tab-pane fade" id="document">
	<form id="documentForm" method="post" action="<?php echo $this->home; ?>/update_config">
    <div class="row">
    	<div class="col-sm-3">
				<span class="form-control left-label">Sales Order</span>
			</div>
      <div class="col-sm-2">
				<input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_ORDER" required value="<?php echo $PREFIX_ORDER; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" required name="RUN_DIGIT_ORDER" value="<?php echo $RUN_DIGIT_ORDER; ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Consignment[TR]</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_CONSIG_TR" required value="<?php echo $PREFIX_CONSIGN_TR; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_CONSIGN_TR" required value="<?php echo $RUN_DIGIT_CONSIGN_TR; ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Consignment[INV]</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_CONSIGN_SO" required value="<?php echo $PREFIX_CONSIGN_SO; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_CONSIGN_SO" required value="<?php echo $RUN_DIGIT_CONSIGN_SO; ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Consignment Sold(DO)</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_CONSIGN_SOLD" required value="<?php echo $PREFIX_CONSIGN_SOLD; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_CONSIGN_SOLD" required value="<?php echo $RUN_DIGIT_CONSIGN_SOLD; ?>" /></div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">Consignment Sold</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_CONSIGNMENT_SOLD" required value="<?php echo $PREFIX_CONSIGNMENT_SOLD; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_CONSIGNMENT_SOLD" required value="<?php echo $RUN_DIGIT_CONSIGNMENT_SOLD; ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Googs Receipt PO</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_RECEIVE_PO" required value="<?php echo $PREFIX_RECEIVE_PO; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_RECEIVE_PO" required value="<?php echo $RUN_DIGIT_RECEIVE_PO; ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Goods Receipt Transform</span></div>
      <div class="col-sm-2">
      	<input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_RECEIVE_TRANSFORM" required value="<?php echo $PREFIX_RECEIVE_TRANSFORM; ?>" />
      </div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2">
      	<input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_RECEIVE_TRANSFORM" required value="<?php echo $RUN_DIGIT_RECEIVE_TRANSFORM; ?>" />
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Transform(sell)</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center" name="PREFIX_TRANSFORM" required value="<?php echo $PREFIX_TRANSFORM; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center" name="RUN_DIGIT_TRANSFORM" required value="<?php echo $RUN_DIGIT_TRANSFORM; ?>" /></div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">Transform(stock)</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center" name="PREFIX_TRANSFORM_STOCK" required value="<?php echo $PREFIX_TRANSFORM_STOCK; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center" name="RUN_DIGIT_TRANSFORM_STOCK" required value="<?php echo $RUN_DIGIT_TRANSFORM_STOCK; ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Lend</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_LEND" required value="<?php echo $PREFIX_LEND; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_LEND" required value="<?php echo $RUN_DIGIT_LEND; ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Sponsor</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_SPONSOR" required value="<?php echo $PREFIX_SPONSOR; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_SPONSOR" required value="<?php echo $RUN_DIGIT_SPONSOR; ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Comprementary</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_SUPPORT" required value="<?php echo $PREFIX_SUPPORT; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_SUPPORT" required value="<?php echo $RUN_DIGIT_SUPPORT; ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Goods Return</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_RETURN_ORDER" required value="<?php echo $PREFIX_RETURN_ORDER; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_RETURN_ORDER" required value="<?php echo $RUN_DIGIT_RETURN_ORDER; ?>" /></div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">Goods Return Consignment</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_RETURN_CONSIGNMENT" required value="<?php echo $PREFIX_RETURN_CONSIGNMENT; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_RETURN_CONSIGNMENT" required value="<?php echo $RUN_DIGIT_RETURN_CONSIGNMENT; ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Goods Return Lend</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_RETURN_LEND" required value="<?php echo $PREFIX_RETURN_LEND; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_RETURN_LEND" required value="<?php echo $RUN_DIGIT_RETURN_LEND; ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Product Reconcile</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_CONSIGN_CHECK" required value="<?php echo $PREFIX_CONSIGN_CHECK; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_CONSIGN_CHECK" required value="<?php echo $RUN_DIGIT_CONSIGN_CHECK; ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Inventory Transfer</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_TRANSFER" required value="<?php echo $PREFIX_TRANSFER; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_TRANSFER" required value="<?php echo $RUN_DIGIT_TRANSFER; ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Move Product</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_MOVE" required value="<?php echo $PREFIX_MOVE; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_MOVE" required value="<?php echo $RUN_DIGIT_MOVE; ?>" /></div>
      <div class="divider-hidden"></div>


			<div class="col-sm-3"><span class="form-control left-label">Inventory Adjust</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_ADJUST" required value="<?php echo $PREFIX_ADJUST; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_ADJUST" required value="<?php echo $RUN_DIGIT_ADJUST; ?>" /></div>
      <div class="divider-hidden"></div>


			<div class="col-sm-3"><span class="form-control left-label">Inventory Adjust(Consignment)</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_ADJUST_CONSIGNMENT" required value="<?php echo $PREFIX_ADJUST_CONSIGNMENT; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_ADJUST_CONSIGNMENT" required value="<?php echo $RUN_DIGIT_ADJUST_CONSIGNMENT; ?>" /></div>
      <div class="divider-hidden"></div>


			<div class="col-sm-3"><span class="form-control left-label">Goods Issue Transform</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_ADJUST_TRANSFORM" required value="<?php echo $PREFIX_ADJUST_TRANSFORM; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_ADJUST_TRANSFORM" required value="<?php echo $RUN_DIGIT_ADJUST_TRANSFORM; ?>" /></div>
      <div class="divider-hidden"></div>


      <div class="col-sm-3"><span class="form-control left-label">Discount Policy</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_POLICY" required value="<?php echo $PREFIX_POLICY; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_POLICY" required value="<?php echo $RUN_DIGIT_POLICY; ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Discount Rule</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_RULE" required value="<?php echo $PREFIX_RULE; ?>" /></div>
      <div class="col-sm-1 col-1-harf padding-5"><span class="form-control left-label width-100 text-right">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-small text-center digit" name="RUN_DIGIT_RULE" required value="<?php echo $RUN_DIGIT_RULE; ?>" /></div>
      <div class="divider-hidden"></div>
			<div class="divider-hidden"></div>
			<div class="divider-hidden"></div>

      <div class="col-sm-4 col-sm-offset-3">
				<?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
      	<button type="button" class="btn btn-sm btn-success input-small text-center" onClick="checkDocumentSetting()"><i class="fa fa-save"></i> Save</button>
				<?php endif; ?>
      </div>
      <div class="divider-hidden"></div>

    </div><!--/ row -->
  </form>
</div>
