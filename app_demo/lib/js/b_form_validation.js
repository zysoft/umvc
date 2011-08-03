umvc.bind("umvc.validator.error", function(event, data) {
  $("form#" + data.form_id + " *[name="+data.name+"]")
    .addClass("error")
    .next()[0].innerHTML = data.message;
});

umvc.bind("umvc.validator.success", function(event, data) {
  alert(data.message);
});