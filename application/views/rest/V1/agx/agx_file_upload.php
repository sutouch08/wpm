<div class="modal fade" id="upload-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog" style="width:400px; max-width:95vw;">
   <div class="modal-content">
       <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
       <h4 class="modal-title">Upload File</h4>
      </div>
      <div class="modal-body">
        <form id="upload-form" name="upload-form" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-lg-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="input-group width-100">
                <input type="text" class="form-control input-sm" id="show-file-name" onclick="getFile()" placeholder="No File.." readonly style="padding-left:30px; cursor:pointer !important;"/>
                <span class="input-group-btn">
                  <button type="button" class="btn btn-xs btn-primary btn-block" id="btn-get-file" onclick="getFile()">Choose</button>
                </span>
                <a class="remove" id="upload-icon" style="position:absolute; left:5px; top:5px; z-index:2;">
                  <i class="ace-icon fa fa-upload grey fa-lg"></i>
                </a>
                <a class="remove hide" id="clear-icon" href="javascript:clearFile()" style="position:absolute; left:5px; top:5px; z-index:2;">
                  <i class="ace-icon fa fa-times red fa-lg"></i>
                </a>
              </div>
            </div>
          </div>
          <input type="file" class="hide" name="uploadFile" id="uploadFile" accept=".csv, .xls, .xlsx" />
          <input type="hidden" name="555" />
        </form>
       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-sm btn-success" onclick="uploadfile()"><i class="fa fa-cloud-upload"></i> Upload</button>
       </div>
   </div>
 </div>
</div>

<script>
function getUploadFile(){
  $('#upload-modal').modal('show');
}

function getFile(){
  $('#uploadFile').click();
}

function clearFile() {
  $('#uploadFile').val('');
  $('#show-file-name').val('');
  $('#btn-get-file').text('Choose');
  $('#clear-icon').addClass('hide');
  $('#upload-icon').removeClass('hide');
}


$("#uploadFile").change(function(){
	if($(this).val() != '')
	{
		var file 		= this.files[0];
		var name		= file.name;
		var type 		= file.type;
		var size		= file.size;

		if( size > 5000000 )
		{
			swal("The file size is too large.", "Attachments must be no larger than 5 MB.", "error");
			$(this).val('');
			return false;
		}

    $('#show-file-name').val(name);
    $('#btn-get-file').text('Change');
    $('#upload-icon').addClass('hide');
    $('#clear-icon').removeClass('hide');
	}
});


function uploadfile()
{
  $('#upload-modal').modal('hide');

  var file	= $("#uploadFile")[0].files[0];
  var fd = new FormData();
  fd.append('uploadFile', $('input[type=file]')[0].files[0]);

  if( file !== '')
  {
    load_in();
    $.ajax({
      url:HOME + 'upload_file',
      type:"POST",
      cache:"false",
      data: fd,
      processData:false,
      contentType: false,
      success: function(rs) {
        load_out();
        var rs = $.trim(rs);
        if(rs === 'success'){
          swal({
            title: 'Success',
            type: 'success',
            timer:1000
          });

          setTimeout(function(){
            window.location.reload();
          }, 1200);

        }
        else {
          swal({
            title:'Error!!',
            text:rs,
            type:'error',
            html:true
          });
        }
      },
      error:function(rs) {
        load_out();
        swal({
          title:'Error!',
          text:rs.responseText,
          type:'error',
          html:true
        })
      }
    });
  }
}

</script>
