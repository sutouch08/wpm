<div class="row">
	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
    	<label>Document No.</label>
      <input type="text" class="form-control input-sm text-center" value="<?php echo $order->code; ?>" disabled />
    </div>
    <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    	<label>Date</label>
			<input type="text" class="form-control input-sm text-center edit" name="date" id="date" value="<?php echo thai_date($order->date_add); ?>" disabled readonly />
    </div>

		<?php if($order->role == 'S' OR $order->role == 'C' OR $order->role == 'N') : ?>
			<?php if($order->role == 'S') : ?>
				<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
					<label>Customer</label>
					<input type="text" class="form-control input-sm text-center edit" id="customer_code" name="customer_code" value="<?php echo $order->customer_code; ?>" disabled />
				</div>

		    <div class="col-lg-5-harf col-md-5 col-sm-4-harf col-xs-12 padding-5">
		    	<label>Customer Name</label>
					<input type="text" class="form-control input-sm edit" id="customer" name="customer" value="<?php echo $order->customer_name; ?>" required disabled />
		    </div>
		    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		    	<label>Cust ref</label>
		      <input type="text" class="form-control input-sm edit" id="customer_ref" name="customer_ref" value="<?php echo str_replace('"', '&quot;',$order->customer_ref); ?>" disabled />
		    </div>

				<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
		    	<label>Currency</label>
					<input type="text" class="form-control input-sm" value="<?php echo $order->DocCur; ?>" disabled/>
		    </div>

				<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
		    	<label>Rate</label>
					<input type="text" class="form-control input-sm" value="<?php echo $order->DocRate; ?>" disabled/>
		    </div>

		    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
		    	<label>Channels</label>
					<input type="text" class="form-control input-sm" value="<?php echo $order->channels_name; ?>" disabled/>
		    </div>
		    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
		    	<label>Payments</label>
					<input type="text" class="form-control input-sm" value="<?php echo $order->payment_name; ?>" disabled />
		    </div>
				<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
					<label>Order ref</label>
				  <input type="text" class="form-control input-sm text-center edit" name="reference" id="reference" value="<?php echo $order->reference; ?>" disabled />
				</div>
			<?php endif; ?>

			<?php if($order->role == 'C' OR $order->role == 'N') : ?>
				<div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 padding-5">
					<label>Customer</label>
					<input type="text" class="form-control input-sm text-center edit" id="customer_code" name="customer_code" value="<?php echo $order->customer_code; ?>" disabled />
				</div>

		    <div class="col-lg-4 col-md-6-harf col-sm-6-harf col-xs-8 padding-5">
		    	<label class="not-show">Customer Name</label>
					<input type="text" class="form-control input-sm edit" id="customer" name="customer" value="<?php echo $order->customer_name; ?>" required disabled />
		    </div>
				<div class="col-lg-4-harf col-md-9 col-sm-9 col-xs-12 padding-5">
					<label>Consignment Bin Location</label>
					<input type="text" class="form-control input-sm" value="<?php echo $order->zone_name; ?>" disabled />
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if($order->role == 'L' OR $order->role == 'U' OR $order->role == 'P' OR $order->role == 'Q' OR $order->role == 'T') : ?>
				<?php if($order->role != 'L') : ?>
				<div class="col-lg-1-้harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
					<label>Customer Code</label>
					<input type="text" class="form-control input-sm text-center edit" id="customer_code" name="customer_code" value="<?php echo $order->customer_code; ?>" disabled />
				</div>
		    <div class="col-lg-5 col-md-5 col-sm-4-harf col-xs-8 padding-5">
		    	<label>Customer Name</label>
					<input type="text" class="form-control input-sm edit" id="customer" name="customer" value="<?php echo $order->customer_name; ?>" required disabled />
		    </div>
				<div class="col-lg-2-harf col-md-2 col-sm-2 col-xs-6 padding-5">
				 	<label>Owner</label>
					<input type="text" class="form-control input-sm edit" value="<?php echo $order->user_ref; ?>" disabled />
				</div>
			<?php else : ?>
				<div class="col-lg-2-harf col-md-2 col-sm-2 col-xs-6 padding-5">
				 	<label>Owner</label>
					<input type="text" class="form-control input-sm edit" value="<?php echo $order->empName; ?>" disabled />
				</div>
				<div class="col-lg-2-harf col-md-2 col-sm-2 col-xs-6 padding-5">
				 	<label>Reference</label>
					<input type="text" class="form-control input-sm" value="<?php echo $order->user_ref; ?>" disabled />
				</div>
				<div class="col-lg-4-harf col-md-4-harf col-sm-4-harf col-xs-6 padding-5">
					<label>Bin Location</label>
					<input type="text" class="form-control input-sm" value="<?php echo $order->zone_name; ?>" disabled />
				</div>

			<?php endif; ?>
		<?php endif; ?>



		<div class="col-lg-3-harf col-md-4-harf col-sm-4-harf col-xs-6 padding-5">
			<label>Warehouse</label>
	    <input type="text" class="form-control input-sm" value="<?php echo $order->warehouse_name; ?>" disabled />
	  </div>

		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		 	<label>User</label>
		  <input type="text" class="form-control input-sm" value="<?php echo $order->user; ?>" disabled />
		</div>

		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		 	<label>Update by</label>
		  <input type="text" class="form-control input-sm" value="<?php echo $order->update_user; ?>" disabled />
		</div>
		<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
		 	<label>Delivery date</label>
		  <input type="text" class="form-control input-sm text-center" id="ship-date" value="<?php echo thai_date($order->shipped_date, FALSE); ?>" disabled />
		</div>
		<div class="col-lg-1-harf col-md-2 col-sm-1-harf col-xs-6 padding-5">
			<label class="display-block not-show">x</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" id="btn-edit-ship-date" onclick="activeShipDate()">Change Date</button>
			<button type="button" class="btn btn-xs btn-success btn-block hide" id="btn-update-ship-date" onclick="updateShipDate()">Update</button>
		</div>
</div>



















<div class="row">
	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
    	<label>Document No.</label>
      <input type="text" class="form-control input-sm text-center" value="<?php echo $order->code; ?>" disabled />
    </div>
    <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    	<label>Date</label>
			<input type="text" class="form-control input-sm text-center edit" name="date" id="date" value="<?php echo thai_date($order->date_add); ?>" disabled readonly />
    </div>

		<?php if($order->role == 'S' OR $order->role == 'C' OR $order->role == 'N') : ?>
			<?php if($order->role == 'S') : ?>
				<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
					<label>Customer</label>
					<input type="text" class="form-control input-sm text-center edit" id="customer_code" name="customer_code" value="<?php echo $order->customer_code; ?>" disabled />
				</div>

		    <div class="col-lg-5-harf col-md-5 col-sm-4-harf col-xs-12 padding-5">
		    	<label>Customer Name</label>
					<input type="text" class="form-control input-sm edit" id="customer" name="customer" value="<?php echo $order->customer_name; ?>" required disabled />
		    </div>
		    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		    	<label>Cust ref</label>
		      <input type="text" class="form-control input-sm edit" id="customer_ref" name="customer_ref" value="<?php echo str_replace('"', '&quot;',$order->customer_ref); ?>" disabled />
		    </div>

				<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
		    	<label>Currency</label>
					<input type="text" class="form-control input-sm" value="<?php echo $order->DocCur; ?>" disabled/>
		    </div>

				<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
		    	<label>Rate</label>
					<input type="text" class="form-control input-sm" value="<?php echo $order->DocRate; ?>" disabled/>
		    </div>

		    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
		    	<label>Channels</label>
					<input type="text" class="form-control input-sm" value="<?php echo $order->channels_name; ?>" disabled/>
		    </div>
		    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
		    	<label>Payments</label>
					<input type="text" class="form-control input-sm" value="<?php echo $order->payment_name; ?>" disabled />
		    </div>
				<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
					<label>Order ref</label>
				  <input type="text" class="form-control input-sm text-center edit" name="reference" id="reference" value="<?php echo $order->reference; ?>" disabled />
				</div>
			<?php endif; ?>

			<?php if($order->role == 'C' OR $order->role == 'N') : ?>
				<div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 padding-5">
					<label>Customer</label>
					<input type="text" class="form-control input-sm text-center edit" id="customer_code" name="customer_code" value="<?php echo $order->customer_code; ?>" disabled />
				</div>

		    <div class="col-lg-4 col-md-6-harf col-sm-6-harf col-xs-8 padding-5">
		    	<label class="not-show">Customer Name</label>
					<input type="text" class="form-control input-sm edit" id="customer" name="customer" value="<?php echo $order->customer_name; ?>" required disabled />
		    </div>
				<div class="col-lg-4-harf col-md-9 col-sm-9 col-xs-12 padding-5">
					<label>Consignment Bin Location</label>
					<input type="text" class="form-control input-sm" value="<?php echo $order->zone_name; ?>" disabled />
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if($order->role == 'L' OR $order->role == 'U' OR $order->role == 'P' OR $order->role == 'Q' OR $order->role == 'T') : ?>
				<?php if($order->role != 'L') : ?>
				<div class="col-lg-1-้harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
					<label>Customer Code</label>
					<input type="text" class="form-control input-sm text-center edit" id="customer_code" name="customer_code" value="<?php echo $order->customer_code; ?>" disabled />
				</div>
		    <div class="col-lg-5 col-md-5 col-sm-4-harf col-xs-8 padding-5">
		    	<label>Customer Name</label>
					<input type="text" class="form-control input-sm edit" id="customer" name="customer" value="<?php echo $order->customer_name; ?>" required disabled />
		    </div>
				<div class="col-lg-2-harf col-md-2 col-sm-2 col-xs-6 padding-5">
				 	<label>Owner</label>
					<input type="text" class="form-control input-sm edit" value="<?php echo $order->user_ref; ?>" disabled />
				</div>
			<?php else : ?>
				<div class="col-lg-2-harf col-md-2 col-sm-2 col-xs-6 padding-5">
				 	<label>Owner</label>
					<input type="text" class="form-control input-sm edit" value="<?php echo $order->empName; ?>" disabled />
				</div>
				<div class="col-lg-2-harf col-md-2 col-sm-2 col-xs-6 padding-5">
				 	<label>Reference</label>
					<input type="text" class="form-control input-sm" value="<?php echo $order->user_ref; ?>" disabled />
				</div>
				<div class="col-lg-4-harf col-md-4-harf col-sm-4-harf col-xs-6 padding-5">
					<label>Bin Location</label>
					<input type="text" class="form-control input-sm" value="<?php echo $order->zone_name; ?>" disabled />
				</div>

			<?php endif; ?>
		<?php endif; ?>



		<div class="col-lg-3-harf col-md-4-harf col-sm-4-harf col-xs-6 padding-5">
			<label>Warehouse</label>
	    <input type="text" class="form-control input-sm" value="<?php echo $order->warehouse_name; ?>" disabled />
	  </div>

		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		 	<label>User</label>
		  <input type="text" class="form-control input-sm" value="<?php echo $order->user; ?>" disabled />
		</div>

		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		 	<label>Update by</label>
		  <input type="text" class="form-control input-sm" value="<?php echo $order->update_user; ?>" disabled />
		</div>
		<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
		 	<label>Delivery date</label>
		  <input type="text" class="form-control input-sm text-center" id="ship-date" value="<?php echo thai_date($order->shipped_date, FALSE); ?>" disabled />
		</div>
</div>
