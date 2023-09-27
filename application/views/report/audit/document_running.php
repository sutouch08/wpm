<?php $this->load->view('include/header'); ?>
<div class="row hidden-print">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-lg-4 col-md-4 col-sm-4 padding-5">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-primary" onclick="doExport()"><i class="fa fa-file-excel-o"></i> Export</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class="padding-5 hidden-print"/>
<form class="hidden-print" id="reportForm" method="post" action="<?php echo $this->home; ?>/do_export">
<div class="row">
	<div class="col-lg-2 col-md-2 col-sm-3 padding-5">
    <label>Document Date</label>
    <div class="input-daterange input-group width-100">
      <input type="text" class="form-control input-sm width-50 text-center from-date" name="fromDate" id="fromDate" placeholder="From" required />
      <input type="text" class="form-control input-sm width-50 text-center" name="toDate" id="toDate" placeholder="To" required/>
    </div>
  </div>


	<div class="col-lg-2 col-md-2 col-sm-3 padding-5">
    <label class="display-block">Type</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-role-all" onclick="toggleAllRole(1)">All</button>
      <button type="button" class="btn btn-sm width-50" id="btn-role-range" onclick="toggleAllRole(0)">Select</button>
    </div>
  </div>

	<input type="hidden" id="allRole" name="allRole" value="1">
	<input type="hidden" id="token" name="token" value="<?php echo uniqid(); ?>">
</div>

<div class="modal fade" id="role-modal" tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog' id='modal' style="width:350px;">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='title' id='modal_title'>Document Type</h4>
            </div>
            <div class='modal-body' id='modal_body' style="padding:0px;">
							<div class="col-sm-12">
								<label>
									<label>
										<input type="checkbox" class="ace" onchange="checkAll($(this))" />
										<span class="lbl">    All</span>
									</label>
								</label>
							</div>

            <div class="col-sm-12">
							<label>
								<input type="checkbox" class="ace chk" id="role-s" name="role[WO]" value="WO" data-prefix="WO" style="margin-right:10px;" />
								<span class="lbl">   WO - Sales Order</span>
							</label>
						</div>
						<div class="col-sm-12">
							<label>
								<input type="checkbox" class="ace chk" id="role-p" name="role[WS]" value="WS" data-prefix="WS" style="margin-right:10px;" />
								<span class="lbl">   WS - Sponsor Order</span>
							</label>
						</div>
						<div class="col-sm-12">
							<label>
								<input type="checkbox" class="ace chk" id="role-u" name="role[WU]" value="WU" data-prefix="WU" style="margin-right:10px;" />
								<span class="lbl">   WU - Complementary</span>
							</label>
						</div>
						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-c" name="role[WC]" value="WC" data-prefix="WC" style="margin-right:10px;" />
                <span class="lbl">   WC - Consignment(IV)</span>
              </label>
						</div>
						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-n" name="role[WT]" value="WT" data-prefix="WT" style="margin-right:10px;" />
                <span class="lbl">   WT - Consignment(TR)</span>
              </label>
						</div>
						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-m" name="role[WM]" value="M" data-prefix="WM" style="margin-right:10px;" />
                <span class="lbl">   WM - Consignment Sales</span>
              </label>
						</div>
						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-d" name="role[WD]" value="WD" data-prefix="WD" style="margin-right:10px;" />
                <span class="lbl">   WD - Consignment Sales</span>
              </label>
						</div>
						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-l" name="role[WL]" value="WL" data-prefix="WL" style="margin-right:10px;" />
                <span class="lbl">   WL - Lend Products</span>
              </label>
						</div>
						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-t" name="role[WQ]" value="WQ" data-prefix="WQ" style="margin-right:10px;" />
                <span class="lbl">   WQ - Transform Products (For Sell)</span>
              </label>
						</div>
						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-q" name="role[WV]" value="WV" data-prefix="WV" style="margin-right:10px;" />
                <span class="lbl">   WV - Transform Products (For Inventory)</span>
              </label>
            </div>

						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-wr" name="role[WR]" value="WR" data-prefix="WR" style="margin-right:10px;" />
                <span class="lbl">   WR - Goods Receipt PO</span>
              </label>
            </div>

						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-ww" name="role[WW]" value="WW" data-prefix="WW" style="margin-right:10px;" />
                <span class="lbl">   WW - Inventory Transfer</span>
              </label>
            </div>

						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-wg" name="role[WG]" value="WG" data-prefix="WG" style="margin-right:10px;" />
                <span class="lbl">   WG - Goods Issue (Transform)</span>
              </label>
            </div>

						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-rt" name="role[RT]" value="RT" data-prefix="RT" style="margin-right:10px;" />
                <span class="lbl">   RT - Goods Receipt (Transform)</span>
              </label>
            </div>

						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-rn" name="role[RN]" value="RN" data-prefix="RN" style="margin-right:10px;" />
              <span class="lbl">     RN - Goods Return (Lend)</span>
              </label>
            </div>

						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-sm" name="role[SM]" value="SM" data-prefix="SM" style="margin-right:10px;" />
                <span class="lbl">   SM - Goods Return</span>
              </label>
            </div>

						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-cn" name="role[CN]" value="CN" data-prefix="CN" style="margin-right:10px;" />
                <span class="lbl">   CN - Goods Return (Consignment)</span>
              </label>
            </div>

						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-wa" name="role[WA]" value="WA" data-prefix="WA" style="margin-right:10px;" />
                <span class="lbl">   WA - Inventory Adjust</span>
              </label>
            </div>

						<div class="col-sm-12">
							<label>
                <input type="checkbox" class="ace chk" id="role-ac" name="role[AC]" value="AC" data-prefix="AC" style="margin-right:10px;" />
                <span class="lbl">   AC - Inventory Adjust (Consignment)</span>
              </label>
            </div>


        		<div class="divider" ></div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default btn-block' data-dismiss='modal'>OK</button>
            </div>
        </div>
    </div>
</div>

<hr>
</form>


<script>
	function checkAll(el) {
		if(el.is(':checked')) {
			$('.chk').prop('checked', true);
		}
		else {
			$('.chk').prop('checked', false);
		}
	}
</script>

<script src="<?php echo base_url(); ?>scripts/report/audit/document_running.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
