<?php
$allChannels = $rule->all_channels == 0 ? 'N' : 'Y';
$id = $rule->id;
//--- กำหนดช่องทางการขาย
$channels = $this->discount_rule_model->getRuleChannels($id);
$channelsNo = count($channels);
 ?>
<div class="tab-pane fade" id="channels">
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <h4 class="title">Set sales channel conditions</h4>
    </div>
    <div class="divider margin-top-5"></div>
    <div class="col-lg-2 col-md-2-harf col-sm-3 col-xs-4 padding-5">
      <span class="form-control left-label text-right">Sales Channels</span>
    </div>
    <div class="col-lg-2 col-md-2-harf col-sm-3 col-xs-5 padding-5">
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm width-50" id="btn-all-channels" onclick="toggleChannels('Y')">All</button>
        <button type="button" class="btn btn-sm width-50" id="btn-select-channels" onclick="toggleChannels('N')">Specify</button>
      </div>
    </div>
    <div class="col-lg-2 col-md-2-harf col-sm-3 col-xs-3 padding-5">
      <button type="button" class="option btn btn-xs btn-info btn-block padding-right-5" id="btn-show-channels" onclick="showSelectChannels()" >
        Select <span class="badge pull-right" id="badge-channels"><?php echo $channelsNo; ?></span>
      </button>
    </div>
    <div class="divider-hidden"></div>
    <div class="col-lg-2 col-md-2-harf col-sm-3 col-xs-4 padding-5">&nbsp;</div>
    <div class="col-lg-2 col-md-2-harf col-sm-3 col-xs-4 padding-5">
      <button type="button" class="btn btn-sm btn-success btn-block" onclick="saveChannels()"><i class="fa fa-save"></i> Save</button>
    </div>


  </div>

  <input type="hidden" id="all_channels" value="<?php echo $allChannels; ?>" />

</div><!--- Tab-pane --->
<?php $this->load->view('discount/rule/channels_rule_modal'); ?>
