<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-sm-6">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-sm-6">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class="margin-bottom-15"/>

<div class="form-horizontal">
	<div class="row">
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Code</label>
			<div class="col-xs-12 col-sm-3">
				<input type="text" name="code" id="code" class="width-100 r" value="" placeholder="Required" onkeyup="validCode(this)" autofocus />
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red e" id="code-error"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Old Code</label>
			<div class="col-xs-12 col-sm-3">
				<input type="text" name="old_code" id="old-code" class="width-100" value="" placeholder="Optional" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Description</label>
			<div class="col-xs-12 col-sm-3">
				<input type="text" name="name" id="name" class="width-100 r" value="" placeholder="Required" required />
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red e" id="name-error"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Model</label>
			<div class="col-xs-12 col-sm-3">
				<input type="text" name="style" id="style" class="width-100" value="" placeholder="Optional"/>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red" id="style-error"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Old Model</label>
			<div class="col-xs-12 col-sm-3">
				<input type="text" name="old_style" id="old-style" class="width-100" value="" placeholder="Optional"/>
			</div>
		</div>


		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Color</label>
			<div class="col-xs-12 col-sm-3">
				<input type="text" name="color" id="color" class="width-100 r" value="" placeholder="Required" required />
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red e" id="color-error"></div>
		</div>


		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Size</label>
			<div class="col-xs-12 col-sm-3">
				<input type="text" name="size" id="size" class="width-100 r" value="" placeholder="Required" required />
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red e" id="size-error"></div>
		</div>


		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Barcode</label>
			<div class="col-xs-12 col-sm-3">
				<input type="text" name="barcode" id="barcode" class="width-100" value="" placeholder="Optional" onchange="validateBarcode()" />
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red" id="barcode-error"></div>
		</div>


		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Cost</label>
			<div class="col-xs-12 col-sm-3">
				<input type="number" step="any" name="cost" id="cost" class="width-100 r" value="" placeholder="Required" required />
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red e" id="cost-error"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Price</label>
			<div class="col-xs-12 col-sm-3">
				<input type="number" step="any" name="price" id="price" class="width-100 r" value="" placeholder="Required" required />
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red e" id="price-error"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Unit</label>
			<div class="col-xs-12 col-sm-3">
				<select class="width-100 r" name="unit_code" id="unit" required>
					<option value="">Required</option>
					<?php echo select_unit(); ?>
				</select>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red e" id="unit-error"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Brand</label>
			<div class="col-xs-12 col-sm-3">
				<select name="brand_code" id="brand" class="width-100 r" required>
					<option value="">Required</option>
					<?php echo select_product_brand(); ?>
				</select>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red e" id="brand-error"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Group</label>
			<div class="col-xs-12 col-sm-3">
				<select name="group_code" id="group" class="width-100 r" required>
					<option value="">Required</option>
					<?php echo select_product_group(); ?>
				</select>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red e" id="group-error"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Main Group</label>
			<div class="col-xs-12 col-sm-3">
				<select name="main_group_code" id="mainGroup" class="width-100 r" required>
					<option value="">Required</option>
					<?php echo select_product_main_group(); ?>
				</select>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red e" id="mainGroup-error"></div>
		</div>


		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Sub Group</label>
			<div class="col-xs-12 col-sm-3">
				<select name="sub_group_code" id="subGroup" class="width-100">
					<option value="">Optional</option>
					<?php echo select_product_sub_group(); ?>
				</select>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red" id="subGroup-error"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Category</label>
			<div class="col-xs-12 col-sm-3">
				<select name="category_code" id="category" class="width-100 r" required>
					<option value="">Required</option>
					<?php echo select_product_category(); ?>
				</select>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red e" id="category-error"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Kind</label>
			<div class="col-xs-12 col-sm-3">
				<select name="kind_code" id="kind" class="width-100 r" required>
					<option value="">Required</option>
					<?php echo select_product_kind(); ?>
				</select>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red" id="kind-error"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Type</label>
			<div class="col-xs-12 col-sm-3">
				<select name="type_code" id="type" class="width-100 r" required>
					<option value="">Required</option>
					<?php echo select_product_type(); ?>
				</select>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red" id="type-error"></div>
		</div>


		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Year</label>
			<div class="col-xs-12 col-sm-3">
				<select name="year" id="year" class="width-100">
					<?php echo select_years(date('Y')); ?>
				</select>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red" id="year-error"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Inventroy Item</label>
			<div class="col-xs-12 col-sm-3">
				<label style="padding-top:5px;">
					<input name="count_stock" id="count-stock" class="ace ace-switch ace-switch-7" type="checkbox" value="1" checked />
					<span class="lbl"></span>
				</label>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Sell Item</label>
			<div class="col-xs-12 col-sm-3">
				<label style="padding-top:5px;">
					<input name="can_sell" id="can-sell" class="ace ace-switch ace-switch-7" type="checkbox" value="1" checked />
					<span class="lbl"></span>
				</label>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red"></div>
		</div>


		<div class="form-group hide">
			<label class="col-sm-3 control-label no-padding-right">API</label>
			<div class="col-xs-12 col-sm-3">
				<label style="padding-top:5px;">
					<input name="is_api" id="is-api" class="ace ace-switch ace-switch-7" type="checkbox" value="1" />
					<span class="lbl"></span>
				</label>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right">Active</label>
			<div class="col-xs-12 col-sm-3">
				<label style="padding-top:5px;">
					<input name="active" id="active" class="ace ace-switch ace-switch-7" type="checkbox" value="1" checked />
					<span class="lbl"></span>
				</label>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red"></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label not-show">Save</label>
			<div class="col-xs-12 col-sm-3">
				<button type="button" class="btn btn-sm btn-success btn-100" id="btn-submit" onclick="add()">Add</button>
			</div>
			<div class="help-block col-xs-12 col-sm-reset inline red"></div>
		</div>

		<input type="hidden" name="valid" id="valid" value=""/>
	</div>
</div><!-- form -->

<script src="<?php echo base_url(); ?>scripts/masters/items.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/code_validate.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
