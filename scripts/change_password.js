var HOME = BASE_URL + 'change_password/';

function validatePassword(input)
{
	var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/;

	if(input.match(passw))
	{
		return true;
	}
	else
	{
		return false;
	}
}



function checkPassword() {
	const uname = $('#uname').val();
	const current = $('#current-password');
	const newPass = $('#new-password');
	const conPass = $('#confirm-password');
	const pasErr = $('#password-error');

	if(current.val().length === 0) {
		current.addClass('has-error');
		pasErr.text("กรุณาใส่รหัสผ่านปัจจุบัน");
		pasErr.removeClass('hide');
		current.focus();
		return false;
	}
	else {
		current.removeClass('has-error');
		pasErr.addClass('hide');
	}

	if(newPass.val().length === 0) {
		newPass.addClass('has-error');
		pasErr.text('กรุณากำหนดรหัสผ่าน');
		pasErr.removeClass('hide');
		newPass.focus();
		return false;
	}
	else {
		newPass.removeClass('has-error');
		pasErr.addClass('hide');
	}

	//--- check use same as current passsword ?
	if(newPass.val() === current.val()) {
		newPass.addClass('has-error');
		pasErr.text("รหัสใหม่ต้องไม่ซ้ำกับรหัสปัจจุบัน");
		pasErr.removeClass('hide');
		return false;
	}
	else {
		newPass.removeClass('has-error');
		pasErr.addClass('hide');
	}

	//--- check complexity
	if(!validatePassword(newPass.val())) {
		newPass.addClass('has-error');
		pasErr.text('รหัสผ่านต้องมีความยาว 8 - 20 ตัวอักษร และต้องประกอบด้วย ตัวอักษรภาษาอังกฤษ พิมพ์เล็ก พิมพ์ใหญ่ และตัวเลขอย่างน้อย อย่างละตัว');
		pasErr.removeClass('hide');
		newPass.focus();
		return false;
	}
	else {
		newPass.removeClass('has-error');
		pasErr.addClass('hide');
	}


	if(newPass.val() !== conPass.val()) {
		conPass.addClass('has-error');
		pasErr.text('ยืนยันรหัสผ่านไม่ตรงกับรหัสผ่านใหม่');
		pasErr.removeClass('hide');
		conPass.focus();
		return false;
	}
	else {
		conPass.removeClass('has-error');
		pasErr.addClass('hide');
	}

	$.ajax({
		url:HOME + 'check_current_password',
		type:"POST",
		cache:false,
		data: {
			"uname" : uname,
			"pwd" : current.val()
		},
		success:function(rs) {
			if(rs === 'valid') {
				$.ajax({
					url:HOME + 'change',
					type:'POST',
					cache:false,
					data:{
						'uname' : uname,
						'pwd' : current.val(),
						'new_pwd' : newPass.val()
					},
					success:function(rs) {
						var rs = $.trim(rs);
						if(rs === 'success') {
							window.location.href = BASE_URL;
						}
						else {
							current.addClass('has-error');
							pasErr.text(rs);
							pasErr.removeClass('hide');
							return false;
						}
					}
				})
			}
			else if(rs === 'invalid') {
				current.addClass('has-error');
				pasErr.text('รหัสผ่านไม่ถูกต้อง');
				pasErr.removeClass('hide');
				return false;
			}
			else {
				current.addClass('has-error');
				pasErr.text(rs);
				pasErr.removeClass('hide');
				return false;
			}
		}
	})
}
