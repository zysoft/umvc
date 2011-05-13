umvc.bind("umvc.validator.error", function(event, data) {
  alert(data.message);
  $("*[name="+data.name+"]").addClass("error");
});

umvc.bind("umvc.validator.success", function(event, data) {
  alert(data.message);
});