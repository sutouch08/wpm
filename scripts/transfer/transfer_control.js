window.addEventListener('load', () => {
	from_zone_init();
	to_zone_init();
});

$('#item-code').keyup((e) => {
	if(e.keyCode == 13) {
		getProductInZone();
	}
});


//-------  ดึงรายการสินค้าในโซน
function getProductInZone(){
	let zone_code  = $("#from_zone_code").val();
	let transfer_code = $('#transfer_code').val();
	let item_code = $.trim($('#item-code').val());

	if( zone_code.length > 0 ) {
			if(item_code.length == 0) {
				swal("กรุณาระบุสินค้า หากต้องการทั้งหมดใส่ *");
				return false;
			}

		load_in();
		$.ajax({
			url: HOME + 'get_product_in_zone',
			type:"GET",
      cache:"false",
      data:{
				'transfer_code' : transfer_code,
        'zone_code' : zone_code,
				'item_code' : item_code
      },
			success: function(rs){
				load_out();
				var rs = 	$.trim(rs);
				if( isJson(rs) ) {
					var source = $("#zoneTemplate").html();
					var data		= $.parseJSON(rs);
					var output	= $("#zone-list");
					render(source, data, output);

					inputQtyInit();
					$('#myTab a[href="#zone-table"]').tab('show');
				}
				else {
					swal({
						title:'Error',
						text:rs,
						type:'error'
					});
				}
			}
		});
	}
}


function from_zone_init(){
	var code = $('#from_warehouse_code').val();
	$("#from-zone").autocomplete({
		source: HOME + 'get_transfer_zone/'+ code,
		autoFocus: true,
		close: function(){
			var rs = $(this).val();
			var rs = rs.split(' | ');
			if( rs.length == 2 ){
				$("#from_zone_code").val(rs[0]);
	      //--- แสดงชื่อโซนใน text box
				$(this).val(rs[1]);
				//---	แสดงชื่อโซนที่ หัวตาราง
				$('#zoneName').text(rs[1]);
			}else{

				$("#from_zone_code").val('');
				//---	ชื่อโซนที่ หัวตาราง
				$('#zoneName').text('');
				$(this).val('');
			}
		}
	});
}



$("#from-zone").keyup(function(e) {
    if( e.keyCode == 13 ){
		setTimeout(function(){
			$('#item-code').focus();
		}, 100);
	}
});



function to_zone_init(){
	var code = $('#to_warehouse_code').val();
	$("#to-zone").autocomplete({
		source: HOME + 'get_transfer_zone/' + code,
		autoFocus: true,
		close: function(){
			var rs = $(this).val();
			var rs = rs.split(' | ');
			if( rs.length == 2 ){
				$("#to_zone_code").val(rs[0]);
				$(this).val(rs[1]);
			}else{
				$("#to_zone_code").val('');
				$(this).val('');
			}
		}
	});

}



//------- สลับไปแสดงหน้า transfer_detail
async function showTransferTable(){
	await getTransferTable();
	$('#myTab a[href="#transfer-table"]').tab('show');
}


async function showTempTable(){
	await getTempTable();
	$('#myTab a[href="temp-table"]').tab('show');
}


function inputQtyInit(){
	$('.input-qty').keyup(function(){
		var qty = parseInt($(this).val());
		var limit = parseInt($(this).attr('max'));
		qty = isNaN(qty) ? 0 : qty;
		limit = isNaN(limit) ? 0 : limit;

		if(qty > limit)
		{
			swal('exceeds limit ' + limit);
			$(this).val(limit);
		}
	})
}


//---	ดึงข้อมูลสินค้าในโซนต้นทาง
function getZoneTo(){

	var barcode = $("#toZone-barcode").val();

	if( barcode.length > 0 ){
		//---	คลังปลายทาง
		var warehouse_code = $("#to_warehouse_code").val();

		$.ajax({
			url: BASE_URL + 'masters/zone/get_warehouse_zone',
			type:"GET",
			cache:"false",
			data:{
				"barcode" : barcode,
				"warehouse_code" : warehouse_code
			},
			success: function(rs){

				var rs = $.trim(rs);

				if( isJson(rs) ){

					//---	รับข้อมูลแล้วแปลงจาก json
					var ds = $.parseJSON(rs);

					//---	update id โซนปลายทาง
					$("#to_zone_code").val(ds.code);

					//---	update ชื่อโซน
					$("#zoneName-label").val(ds.name);

					//---	disabled ช่องยิงบาร์โค้ดโซน
					$("#toZone-barcode").attr('disabled', 'disabled');

					//--- active new zone button
					$('#btn-new-to-zone').removeAttr('disabled');

					$('#qty-to').removeAttr('disabled');

					$('#barcode-item-to').removeAttr('disabled');

					$('#barcode-item-to').focus();

					showTempTable();

				}else{

					swal("Error", rs, "error");

					//---	ลบไอดีโซนปลายทาง
					$("#to_zone_code").val("");

					//---	ไม่แสดงชื่อโซน
					$('#zoneName-label').val('');

					//--- disabled new zone buton
					$('#btn-new-to-zone').attr('disabled');

					//--- ซ่อนตารางสินค้าในโซน
					$("#zone-table").addClass('hide');

					beep();
				}
			}
		});
	}
}




$("#toZone-barcode").keyup(function(e) {
    if( e.keyCode == 13 ){
		getZoneTo();
		setTimeout(function(){ $("#barcode-item-to").focus(); }, 500);
	}
});


//---	ดึงข้อมูลสินค้าในโซนต้นทาง
function getZoneFrom(){

	var barcode = $("#fromZone-barcode").val();

	if( barcode.length > 0 ){
		//---	คลังต้นทาง
		var warehouse_code = $("#from_warehouse_code").val();

		$.ajax({
			url:BASE_URL + 'masters/zone/get_warehouse_zone',
			type:"GET",
			cache:"false",
			data:{
				"barcode" : barcode,
				"warehouse_code" : warehouse_code
			},
			success: function(rs){

				var rs = $.trim(rs);

				if( isJson(rs) ){

					//---	รับข้อมูลแล้วแปลงจาก json
					var ds = $.parseJSON(rs);

					//---	update id โซนต้นทาง
					$("#from_zone_code").val(ds.code);

					//---	update ชื่อโซน
					$("#zoneName").text(ds.name);


					$("#fromZone-barcode").attr('disabled', 'disabled');
					$('#btn-new-zone').removeAttr('disabled');
					$('#qty-from').removeAttr('disabled');
					$('#barcode-item-from').removeAttr('disabled');
					$('#barcode-item-from').focus();

					//---	แสดงรายการสินค้าในโซน
					getProductInZone();

				}else{
					swal("Error", rs, "error");

					//---	ลบไอดีโซนต้นทาง
					$("#from_zone_code").val("");

					//---	ไม่แสดงชื่อโซน
					$('#zoneName').val('');

					$("#zone-table").addClass('hide');

					beep();
				}
			}
		});
	}
}



$("#fromZone-barcode").keyup(function(e) {
    if( e.keyCode == 13 ){
		getZoneFrom();
		setTimeout(function(){ $("#barcode-item-from").focus(); }, 500);
	}
});


//------------------------------------- ยิงบาร์โค้ดสินค้า

$("#barcode-item-from").keyup(function(e) {
  if( e.keyCode == 13 ){
		//---	โซนต้นทาง
		var zone_code	= $("#from_zone_code").val();

		//---	ID เอกสาร
		var transfer_code = $("#transfer_code").val();

		//---	ตรวจสอบว่ายิงบาร์โค้ดโซนมาแล้วหรือยัง
		if( zone_code.length == 0 ){
			swal("Please specify destination");
			return false;
		}

		//---	จำนวนที่ป้อนมา
		var qty = parseInt($("#qty-from").val());

		//---	บาร์โค้ดสินค้า
		var barcode = $(this).val();

		//---	จำนวนที่เพิ่มไปแล้ว
		var curQty	= parseInt($("#qty_"+barcode).val());

		//---	เคลียร์ช่องให้พร้อมยิงตัวต่อไป
		$(this).val('');

		//---	เมื่อมีการใส่จำนวนมาตามปกติ
		if( qty != '' && qty != 0 ){

			//---	ถ้าจำนวนที่ใส่มา น้อยกว่าหรือเท่ากับ จำนวนที่มีอยู่
			//---	หรือ โซนนี้สามารถติดลบได้และติ๊กว่าให้ติดลบได้
			//---	หากโซนนี้ไม่สามารถติดลบได้ ถึงจะติ๊กให้ติดลบได้ก็ไม่สามารถให้ติดลบได้
			if( qty <= curQty ){
				//---	เพิ่มรายการเข้า temp
				$.ajax({
					url: HOME + 'add_to_temp',
					type:"POST",
					cache:"false",
					data:{
						"transfer_code" : transfer_code,
						"from_zone" : zone_code,
						"qty" : qty,
						"barcode" : barcode,
					},
					success: function(rs){

						var rs = $.trim(rs);

						if( rs == 'success'){

							//--- ลดยอดสินค้าคงเหลือในโซนบนหน้าเว็บ (ในฐานข้อมูลถูกลดแล้ว)
							curQty = curQty - qty;

							//---	แสดงผลยอดสินค้าคงเหลือในโซน
							$("#qty-label_"+barcode).text(curQty);

							//---	ปรับยอดคงเหลือในโซน สำหรับใช้ตรวจสอบการยิงครั้งต่อไป
							$("#qty_"+barcode).val(curQty);

							//---	reset จำนวนเป็น 1
							$("#qty-from").val(1);

							//---	focus ที่ช่องยิงบาร์โค้ด รอการยิงต่อไป
							$("#barcode-item-from").focus();

						}else{

							swal("Error", rs, "error");
						}
					}
				});
			}else{
				swal("Insufficient balance in the location");
			}
		}
	}
});
