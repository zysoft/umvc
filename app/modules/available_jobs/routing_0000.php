<?php
uf_application::add_route(function($source) {
  if($source == '/available-jobs')
  {
    return '/career-guide';    
  }
});
?>