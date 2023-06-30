<div class="modal fade" id="boxListModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
  <div class="modal-dialog" style="width:800px; max-width:90vw;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Print label</h4>
      </div>
      <div class="modal-body">

          <!-- แสดงผลกล่อง  -->
          <div class="row">
            <div class="col-sm-12" id="box-row" style="padding:10px;">
            <?php if(!empty($box_list)) : ?>
            <?php   foreach($box_list as $rs) : ?>
                  <button type="button" class="btn btn-sm btn-success" id="btn-box-<?php echo $rs->id; ?>" onclick="printBox(<?php echo $rs->id; ?>)">
                    <i class="fa fa-print"></i>&nbsp;Box no. <?php echo $rs->box_no; ?>&nbsp; : &nbsp;
                    <span id="<?php echo $rs->id; ?>"><?php echo number($rs->qty); ?></span>&nbsp; Pcs.
                  </button>
            <?php   endforeach; ?>
            <?php else : ?>
              <span id="no-box-label">No packing data</span>
            <?php endif; ?>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- จบกล่อง -->

      </div>

    </div>
  </div>

</div>

<script src="<?php echo base_url(); ?>scripts/inventory/order_closed/box_list.js"></script>
