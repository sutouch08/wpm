<div class="row">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
    <span class="form-control label-right">
      <h4 class="title">Add images for this product</h4>
    </span>
  </div>
  <div class="divider-hidden visible-xs"></div>

  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <button type="button" class="btn btn-primary btn-block" onClick="showUploadBox()">
      <i class="fa fa-cloud-upload"></i> Add Images
    </button>
  </div>
  <div class="divider-hidden visible-xs"></div>
  <div class="col-sm-4 col-xs-12">
    <span class="help-block" style="margin-top:15px; margin-bottom:0px;">allow : jpg, png, gif maximum size 2 MB</span>
  </div>
</div><!--/ row -->

<hr/>
<div class="row" id="imageTable">
<?php if(!empty($images) ) : ?>
<?php		foreach( $images as $img ) : 	?>
<?php			$cover	= $img->cover == 1 ? 'btn-success' : ''; ?>
  <div class="col-sm-3 col-xs-6" id="div-image-<?php echo $img->id; ?>">
    <div class="thumbnail">
      <a data-rel="colorbox" href="<?php echo get_image_path($img->id, 'large'); ?>">
        <img class="img-rounded" src="<?php echo get_image_path($img->id, 'medium'); ?>" />
      </a>
      <div class="caption">
        <button
          type="button"
          id="btn-cover-<?php echo $img->id; ?>"
          class="btn btn-sm <?php echo $cover; ?> btn-cover"
          onClick="setAsCover('<?php echo $style->code; ?>', <?php echo $img->id; ?>)">
            <i class="fa fa-check"></i>
        </button>
        <button
          type="button"
          class="btn btn-sm btn-danger"
          style="position:relative; float:right;"
          onClick="removeImage('<?php echo $style->code; ?>', <?php echo $img->id; ?>)">
          <i class="fa fa-trash"></i>
        </button>
      </div>
    </div>
  </div>
<?php		endforeach; ?>
<?php	else : ?>
  <div class="col-sm-12">
    <h4 style="text-align:center; padding-top:50px; color:#AAA;">
      <i class="fa fa-file-image-o fa-2x"></i> No image now
    </h4>
  </div>
<?php endif; ?>
</div><!--/ row -->


<div class="modal fade" id="uploadBox" tabindex="-1" role="dialog" aria-labelledby="uploader" aria-hidden="true">
	<div class="modal-dialog" style="width:800px; max-width:90vw;">
  	<div class="modal-content">
      <div class="modal-body">
      	<form class="dropzone" id="imageForm" action="">
        </form>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-sm btn-default" onClick="clearUploadBox()">Close</button>
        <button type="button" class="btn btn-sm btn-primary" onClick="doUpload()">Upload</button>
      </div>
    </div>
  </div>
</div>

<script id="imageTableTemplate" type="text/x-handlebars-temlate">
{{#each this}}
	{{#if id_img}}
		<div class="col-sm-3" id="div-image-{{ id_img }}">
			<div class="thumbnail">
				<a data-rel="colorbox" href="{{ bigImage }}">
					<img class="img-rounded" src="{{ thumbImage }}" />
				</a>
				<div class="caption">
					<button type="button" id="btn-cover-{{ id_img }}" class="btn btn-sm {{ isCover }} btn-cover" style="position:relative;" onClick="setAsCover('{{ id_pd }}', {{ id_img }})"><i class="fa fa-check"></i></button>
					<button type="button" class="btn btn-sm btn-danger" style="position:absolute; right:25px;" onClick="removeImage({{ id_pd }}, {{ id_img }})"><i class="fa fa-trash"></i></button>
				</div>
			</div>
		</div>
	{{else}}
		<div class="col-sm-12"><h4 style="text-align:center; padding-top:50px; color:#AAA;"><i class="fa fa-file-image-o fa-2x"></i> No image now</h4></div>
	{{/if}}
{{/each}}
</script>
