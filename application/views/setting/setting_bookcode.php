<div class="tab-pane fade" id="bookcode">
	<form id="bookcodeForm" method="post" action="<?php echo $this->home; ?>/update_config">
    <div class="row">
    	<div class="col-sm-3"><span class="form-control left-label">Sales Orders</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_ORDER" value="<?php echo $BOOK_CODE_ORDER; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบสั่งขาย"</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Comprementary</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_SUPPORT" value="<?php echo $BOOK_CODE_SUPPORT; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบเบิกอภินันท์"</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Sponsor</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_SPONSOR" value="<?php echo $BOOK_CODE_SPONSOR; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบเบิกสปอนเซอร์"</span>
      </div>
      <div class="divider-hidden"></div>


      <div class="col-sm-3"><span class="form-control left-label">Goods Receipt PO</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_BI" value="<?php echo $BOOK_CODE_RECEIVE_PO; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบรับสินค้า"</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Inventory Transfer</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_TRANSFER" value="<?php echo $BOOK_CODE_TRANSFER; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบโอนสินค้าคลัง"</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Lend</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_LEND" value="<?php echo $BOOK_CODE_LEND; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบโอนสินค้า"</span>
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">Consignment(TR)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_CONSIGN_TR" value="<?php echo $BOOK_CODE_CONSIGN_TR; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบโอนสินค้า"</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Consignment(INV)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_CONSIGN_SO" value="<?php echo $BOOK_CODE_CONSIGN_SO; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบโอนสินค้า"</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Consignment sold (DO)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_CONSIGN_SOLD" value="<?php echo $BOOK_CODE_CONSIGN_SOLD; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ตัดยอดฝากขาย(Shop)"</span>
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">Consignment sold</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_CONSIGNMENT_SOLD" value="<?php echo $BOOK_CODE_CONSIGNMENT_SOLD; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ตัดยอดฝากขาย(ห้าง)"</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Transform(for sell)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_TRANSFORM" value="<?php echo $BOOK_CODE_TRANSFORM; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบเบิกแปรสภาพ"</span>
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">Transform(for stock)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_TRANSFORM_STOCK" value="<?php echo $BOOK_CODE_TRANSFORM_STOCK; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบเบิกแปรสภาพ"</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Receipt Transformed</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_RECEIVE_TRANSFORM" value="<?php echo $BOOK_CODE_RECEIVE_TRANSFORM; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบรับสินค้าจากการแปรสภาพ"</span>
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">Goods Issue Transform</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_ADJUST_TRANSFORM" value="<?php echo $BOOK_CODE_ADJUST_TRANSFORM; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบตัดยอดสินค้าแปรสภาพ"</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">Goods Return</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_RETURN_ORDER" value="<?php echo $BOOK_CODE_RETURN_ORDER; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบลดหนี้"</span>
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">Goods Return Consignment</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_RETURN_CONSIGNMENT" value="<?php echo $BOOK_CODE_RETURN_CONSIGNMENT; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบลดหนี้ฝากขายเทียม"</span>
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">Goods Return Lend</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_RETURN_LEND" value="<?php echo $BOOK_CODE_RETURN_LEND; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบลดหนี้"</span>
      </div>
      <div class="divider-hidden"></div>


			<div class="col-sm-3"><span class="form-control left-label">Inventory Adjust</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_ADJUST" value="<?php echo $BOOK_CODE_ADJUST; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ปรับยอดสต็อก"</span>
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">Inventory Adjust(Consignment)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small bookcode text-center" name="BOOK_CODE_ADJUST_CONSIGNMENT" value="<?php echo $BOOK_CODE_ADJUST_CONSIGNMENT; ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ปรับยอดสต็อก"</span>
      </div>
      <div class="divider-hidden"></div>


      <div class="col-sm-9 col-sm-offset-3">
				<?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
      	<button type="button" class="btn btn-sm btn-success input-small" onClick="updateConfig('bookcodeForm')"><i class="fa fa-save"></i> Save</button>
				<?php endif; ?>
      </div>
      <div class="divider-hidden"></div>

    </div><!--/ row -->
  </form>
</div>
