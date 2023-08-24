<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-sm-6">
    	<h3 class="title" >
        <?php echo $this->title; ?>
      </h3>
	</div>
    <div class="col-sm-6">
      	<p class="pull-right top-p">
			    <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
          <?php if(($this->pm->can_add OR $this->pm->can_edit) && $doc->status == 0) : ?>
						<!--<button type="button" class="btn btn-sm btn-primary" onclick="getDiffList()"><i class="fa fa-archive"></i> ยอดต่าง</button>-->
            <button type="button" class="btn btn-sm btn-success" onclick="saveAdjust()"><i class="fa fa-save"></i> Save</button>
          <?php endif; ?>
        </p>
    </div>
</div>
<hr />

<div class="row">
    <div class="col-sm-1 col-1-harf padding-5 first">
    	<label>Doc No.</label>
        <input type="text" class="form-control input-sm text-center" value="<?php echo $doc->code; ?>" disabled />
    </div>
		<div class="col-sm-1 padding-5">
    	<label>Date</label>
      <input type="text" class="form-control input-sm text-center edit" id="date_add" value="<?php echo thai_date($doc->date_add) ?>" readonly disabled/>
    </div>
		<div class="col-sm-2 padding-5">
			<label>Reference</label>
			<input type="text" class="form-control input-sm edit" id="reference" value="<?php echo $doc->reference; ?>" disabled />
		</div>
		<div class="col-sm-6 padding-5">
    	<label>Remark</label>
        <input type="text" class="form-control input-sm" id="remark" placeholder="" value="<?php echo $doc->remark; ?>" disabled/>
    </div>
    <?php if($doc->status == 0) : ?>
		<div class="col-sm-1 col-1-harf padding-5 last">
			<label class="display-block not-show">add</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> Edit</button>
      <button type="button" class="btn btn-xs btn-success btn-block hide" id="btn-update" onclick="updateHeader()"><i class="fa fa-save"></i> Update</button>
		</div>
    <?php endif; ?>

    <input type="hidden" id="code" value="<?php echo $doc->code; ?>" />
    <input type="hidden" id="zone_code" value="" />
</div>

<?php if($doc->status == 0) : ?>
<hr class="margin-top-15 margin-bottom-15"/>
<div class="row">
  <div class="col-sm-3 padding-5 first">
    <label>Location</label>
    <input type="text" class="form-control input-sm text-center" id="zone" value="" autofocus />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label class="display-block not-show">change</label>
    <button type="button" class="btn btn-xs btn-yellow btn-block hide" id="btn-change-zone" onclick="changeZone()">change</button>
    <button type="button" class="btn btn-xs btn-info btn-block" id="btn-set-zone" onclick="set_zone()">Set</button>
  </div>
  <div class="col-sm-3 padding-5">
    <label>SKU</label>
    <input type="text" class="form-control input-sm text-center" id="pd-code" value="" disabled />
  </div>
	<div class="col-sm-1 padding-5">
		<label>Stock</label>
		<input type="number" class="form-control input-sm text-center" id="stock-qty" value="" disabled />
	</div>
  <div class="col-sm-1 padding-5">
    <label>Increse</label>
    <input type="number" class="form-control input-sm text-center" id="qty-up" value="" disabled />
  </div>
  <div class="col-sm-1 padding-5">
    <label>Decrese</label>
    <input type="number" class="form-control input-sm text-center" id="qty-down" value="" disabled />
  </div>
  <div class="col-sm-1 col-1-harf padding-5 last">
    <label class="display-block not-show">OK</label>
    <button type="button" class="btn btn-xs btn-primary btn-block" id="btn-add" onclick="add_detail()" disabled>Add</button>
  </div>
</div>
<?php endif; ?>
<hr class="margin-top-15 margin-bottom-15"/>
<div class="row">
  <div class="col-sm-12 first last">
    <p class="pull-right top-p">
      <span style="margin-right:30px;"><i class="fa fa-check green"></i> = Saved</span>
      <span><i class="fa fa-times red"></i> = Unsave</span>
    </p>
  </div>
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr>
          <th class="width-5 text-center">#</th>
          <th class="width-20">Item code</th>
          <th class="">Description</th>
          <th class="width-20 text-center">Bin code</th>
          <th class="width-10 text-center">Increse</th>
          <th class="width-10 text-center">Decrese</th>
          <th class="width-5 text-center">Status</th>
          <th class="width-5 text-right"></th>
        </tr>
      </thead>
      <tbody id="detail-table">
<?php if(!empty($details)) : ?>
<?php   $no = 1;    ?>
<?php   foreach($details as $rs) : ?>
      <tr class="font-size-12 rox" id="row-<?php echo $rs->id; ?>">
        <td class="middle text-center no">
          <?php echo $no; ?>
        </td>
        <td class="middle">
          <?php echo $rs->product_code; ?>
        </td>
        <td class="middle">
          <?php echo $rs->product_name; ?>
        </td>
        <td class="middle text-center">
          <?php echo $rs->zone_name; ?>
        </td>
        <td class="middle text-center" id="qty-up-<?php echo $rs->id; ?>">
          <?php echo $rs->qty > 0 ? intval($rs->qty) : 0 ; ?>
        </td>
        <td class="middle text-center" id="qty-down-<?php echo $rs->id; ?>">
          <?php echo $rs->qty < 0 ? ($rs->qty * -1) : 0 ; ?>
        </td>
        <td class="middle text-center">
          <?php echo is_active($rs->valid); ?>
        </td>
        <td class="middle text-right">
        <?php if(($this->pm->can_add OR $this->pm->can_edit) && $doc->status == 0) : ?>
          <button type="button" class="btn btn-xs btn-danger" onclick="deleteDetail(<?php echo $rs->id; ?>, '<?php echo $rs->product_code; ?>')">
            <i class="fa fa-trash"></i>
          </button>
        <?php endif; ?>
        </td>
      </tr>
<?php     $no++; ?>
<?php   endforeach; ?>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<form id="diffForm" method="post" action="<?php echo base_url(); ?>inventory/check_stock_diff/diff_list/<?php echo $doc->code; ?>">
	<input type="hidden" name="adjust_code" value="<?php echo $doc->code; ?>">
</form>

<script id="detail-template" type="text/x-handlebars-template">
<tr class="font-size-12 rox" id="row-{{id}}">
  <td class="middle text-center no">{{no}}</td>
  <td class="middle">{{ pdCode }}</td>
  <td class="middle">{{ pdName }}</td>
  <td class="middle text-center">{{ zoneName }}</td>
  <td class="middle text-center" id="qty-up-{{id}}">{{ up }}</td>
  <td class="middle text-center" id="qty-down-{{id}}">{{ down }}</td>
  <td class="middle text-center">
    {{#if valid}}
    <i class="fa fa-times red"></i>
    {{else}}
    <i class="fa fa-check green"></i>
    {{/if}}
  </td>
  <td class="middle text-right">
  <?php if(($this->pm->can_add OR $this->pm->can_edit) && $doc->status == 0) : ?>
    <button type="button" class="btn btn-xs btn-danger" onclick="deleteDetail({{ id }}, '{{ pdCode }}')">
      <i class="fa fa-trash"></i>
    </button>
  <?php endif; ?>
  </td>
</tr>
</script>


<script src="<?php echo base_url(); ?>scripts/inventory/adjust/adjust.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/inventory/adjust/adjust_add.js?v=<?php echo date('YmdH'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
