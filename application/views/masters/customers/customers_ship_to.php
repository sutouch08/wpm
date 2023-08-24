<div class='row'>
  <div class="col-sm-12">
  <div class="table-responsive">
    <table class='table table-bordered' style="margin-bottom:0px;">
      <thead>
        <tr>
          <td colspan="6" align="center">Shipping Address
            <p class="pull-right top-p">
              <button type="button" class="btn btn-info btn-xs" onClick="addNewAddress()"> Add New</button>
            </p>
          </td>
        </tr>
        <tr style="font-size:12px;">
          <td align="center" width="10%">Alias</td>
          <td width="12%">Consignee</td>
          <td width="35%">Shipping Address</td>
          <td width="15%">Email</td>
          <td width="15%">Phone</td>
          <td ></td>
        </tr>
      </thead>
      <tbody id="adrs">
<?php if(!empty($addr)) : ?>
<?php 	foreach($addr as $rs) : ?>
        <tr style="font-size:12px;" id="<?php echo $rs->id; ?>">
          <td align="center"><?php echo $rs->alias; ?></td>
          <td><?php echo $rs->name; ?></td>
          <td><?php echo $rs->address.' '. $rs->sub_district.' '.$rs->district.' '.$rs->province.' '. $rs->postcode; ?></td>
          <td><?php echo $rs->email; ?></td>
          <td><?php echo $rs->phone; ?></td>
          <td align="right">
    <?php if( $rs->is_default == 1 ) : ?>
            <button type="button" class="btn btn-mini btn-success btn-address" id="btn-<?php echo $rs->id; ?>" onClick="setDefault(<?php echo $rs->id; ?>)">
              <i class="fa fa-check"></i>
            </button>
    <?php else : ?>
            <button type="button" class="btn btn-mini btn-address" id="btn-<?php echo $rs->id; ?>" onClick="setDefault(<?php echo $rs->id; ?>)">
              <i class="fa fa-check"></i>
            </button>
    <?php endif; ?>
            <button type="button" class="btn btn-mini btn-warning" onClick="editAddress(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i></button>
            <button type="button" class="btn btn-mini btn-danger" onClick="removeAddress(<?php echo $rs->id; ?>)"><i class="fa fa-trash"></i></button>
          </td>
        </tr>
<?php 	endforeach; ?>
<?php else : ?>
        <tr><td colspan="6" align="center">Not found</td></tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
  </div>
</div><!-- /row-->


<!--  Add New Address Modal  --------->
<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:500px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title-site text-center" >Add/Edit Shipping Address</h4>
            </div>
            <div class="modal-body">
            <form id="addAddressForm"	>
            <input type="hidden" name="id_address" id="id_address" />
            <input type="hidden" name="customer_ref" id="customer_ref" value="<?php echo $ds->code; ?>" />
            <div class="row">
            	<div class="col-sm-12 col-xs-12">
                	<label class="input-label">Consignee</label>
                    <input type="text" class="form-control input-sm" name="Fname" id="Fname" placeholder="ชื่อผู้รับ (จำเป็น)" />
                </div>
                <div class="col-sm-12 col-xs-12">
                	<label class="input-label">Address</label>
                    <input type="text" class="form-control input-sm" name="address" id="address1" placeholder="เลขที่, หมู่บ้าน, ถนน (จำเป็น)" />
                </div>

                <div class="col-sm-6 col-xs-12">
                	<label class="input-label">Sub District</label>
                    <input type="text" class="form-control input-sm" name="sub_district" id="sub_district" placeholder="ตำบล" />
                </div>
                <div class="col-sm-6 col-xs-12">
                	<label class="input-label">District</label>
                    <input type="text" class="form-control input-sm" name="district" id="district" placeholder="อำเภอ (จำเป็น)" />
                </div>
                <div class="col-sm-6 col-xs-12">
                	<label class="input-label">Province</label>
                    <input type="text" class="form-control input-sm" name="province" id="province" placeholder="จังหวัด (จำเป็น)" />
                </div>
                <div class="col-sm-6 col-xs-12">
                	<label class="input-label">Post code</label>
                    <input type="text" class="form-control input-sm" name="postcode" id="postcode" placeholder="รหัสไปรษณีย์" />
                </div>
                <div class="col-sm-6 col-xs-12">
                	<label class="input-label">Phone</label>
                    <input type="text" class="form-control input-sm" name="phone" id="phone" placeholder="000 000 0000" />
                </div>
                <div class="col-sm-6 col-xs-12">
                	<label class="input-label">Email</label>
                    <input type="text" class="form-control input-sm" name="email" id="email" placeholder="someone@somesite.com" />
                </div>
                <div class="col-sm-6 col-xs-12">
                	<label class="input-label">Alias</label>
                    <input type="text" class="form-control input-sm" name="alias" id="alias" placeholder="ใช้เรียกที่อยู่ เช่น บ้าน, ที่ทำงาน (จำเป็น)" />
                </div>
            </div>
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" onClick="saveShipTo()" ><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<script id="addressTemplate" type="text/x-handlebars-template">
<tr style="font-size:12px;" id="{{ id }}">
	<td align="center">{{ alias }}</td>
	<td>{{ name }}</td>
	<td>{{ address }}</td>
	<td>{{ email }}</td>
	<td>{{ phone }}</td>
	<td align="right">
	{{#if default}}
		<button type="button" class="btn btn-xs btn-success btn-address" id="btn-{{ id }}" onClick="setDefault({{ id }})"><i class="fa fa-check"></i></button>
	{{else}}
		<button type="button" class="btn btn-xs btn-address" id="btn-{{ id }}" onClick="setDefault({{ id }})"><i class="fa fa-check"></i></button>
	{{/if}}
		<button type="button" class="btn btn-xs btn-warning" onClick="editAddress({{ id }})"><i class="fa fa-pencil"></i></button>
		<button type="button" class="btn btn-xs btn-danger" onClick="removeShipTo({{ id }})"><i class="fa fa-trash"></i></button>
	</td>
</tr>
</script>



<script id="addressTableTemplate" type="text/x-handlebars-template">
{{#each this}}
<tr style="font-size:12px;" id="{{ id }}">
	<td align="center">{{ alias }}</td>
	<td>{{ name }}</td>
	<td>{{ address }}</td>
	<td>{{ email }}</td>
	<td>{{ phone }}</td>
	<td align="right">
	{{#if default}}
		<button type="button" class="btn btn-xs btn-success btn-address" id="btn-{{ id }}" onClick="setDefault({{ id }})"><i class="fa fa-check"></i></button>
	{{else}}
		<button type="button" class="btn btn-xs btn-address" id="btn-{{ id }}" onClick="setDefault({{ id }})"><i class="fa fa-check"></i></button>
	{{/if}}
		<button type="button" class="btn btn-xs btn-warning" onClick="editAddress({{ id }})"><i class="fa fa-pencil"></i></button>
		<button type="button" class="btn btn-xs btn-danger" onClick="removeAddress({{ id }})"><i class="fa fa-trash"></i></button>
	</td>
</tr>
{{/each}}
</script>
