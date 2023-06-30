
<!-- แสดงผลกล่อง  -->
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="box-row">
  <?php if(!empty($box_list)) : ?>
  <?php   foreach($box_list as $rs) : ?>
        <button type="button" class="btn btn-sm btn-default" id="btn-box-<?php echo $rs->id; ?>" onclick="printBox(<?php echo $rs->id; ?>)">
          <i class="fa fa-print"></i>&nbsp;Box No <?php echo $rs->box_no; ?>&nbsp; : &nbsp;
          <span id="box-<?php echo $rs->id; ?>"><?php echo number($rs->qty); ?></span>&nbsp; Pcs.
        </button>
  <?php   endforeach; ?>
  <?php else : ?>
    <span id="no-box-label">No packing data</span>
  <?php endif; ?>
  </div>
</div>

<hr/>

<script id="box-template" type="text/x-handlebars-template">
  {{#each this}}
<button type="button" class="btn btn-sm {{ class }}" id="btn-box-{{id_box}}" onclick="printBox({{id_box}})">
  <i class="fa fa-print"></i> &nbsp; Box No {{ no }}&nbsp; : &nbsp;
  <span id="box-{{id_box}}">{{qty}}</span>&nbsp; Pcs.
</button>
{{/each}}
</script>
<!-- แสดงผลกล่อง  -->
