var umvc = (function() {
  var listeners = [];
  var validators = [];
  return {
    bind: function(event, callback) {
      if(!listeners[event]) {
        listeners[event] = [];
      }
      listeners[event][listeners[event].length] = callback;
    },
    unbind: function(event, callback) {
      for(var key in listeners[event]) {
        if(!callback || listeners[event][key] === callback) {
          delete listeners[event][key];          
        }
      }
    }, 
    trigger: function(event, data) {
      for (var key in listeners[event]) {
        listeners[event][key](event, data);
      }
    },
    add_validator: function(form, callback) {
      validators[form] = callback;
    },
    validate: function(form) {
      $('#'+form).hide();
      validators[form]();
    }
  };
})();