function getCurrencyRate(currency, date) {
  return new Promise((resolve, reject) => {
    let rate = 0.00;    
    if(currency != "" && currency != undefined) {
      $.ajax({
        url:BASE_URL + 'tools/getCurrencyRate',
        type:'GET',
        cache:false,
        data:{
          "currency" : currency,
          "date" : date
        },
        success:function(rs) {
          rate = parseDefault(parseFloat(rs), 0.00);
          resolve(rate);
        },
        error:function() {
          resolve(rate);
        }
      });
    }
    else {
      resolve(rate);
    }
  });
}
