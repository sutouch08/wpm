//var intv = setInterval(function(){ goBack(); }, 60000*5); //--- Reload every 5 minutes.


$("#fromDate").datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $("#toDate").datepicker('option', 'minDate', sd);
  }
});


$("#toDate").datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $("#fromDate").datepicker('option', 'maxDate', sd);
  }
});



$(".search-box").keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});


function getSearch(){
  $("#searchForm").submit();
}



function clearFilter(){
  $.get(HOME + 'clear_filter', function(){
    goBack();
  });
}


$(document).ready(function() {
	//---	reload ทุก 5 นาที
	setTimeout(function(){ goBack(); }, 300000);
});
