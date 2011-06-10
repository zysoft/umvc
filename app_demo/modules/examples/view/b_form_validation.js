umvc.bind("umvc.validator.error", function(event, data) {
  alert(data.message);
  $("form#" + data.form_id + " *[name="+data.name+"]").addClass("error");
});

umvc.bind("umvc.validator.success", function(event, data) {
  alert(data.message);
});