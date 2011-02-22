<?php
uf_application::add_route(function($source) {
  if($source == '/career-guide')
  {
    return '/my-pages';
  }
});
?>