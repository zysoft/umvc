<?

$uf_config = array(
  'log'            => FALSE,
  'language'       => 'sv_SE',
  'always_bake'    => FALSE,
  'load_propel'    => TRUE,
  'app_dir'        => '/app_demo',
  'propel_app_dir' => '/app_demo',
  'propel_db'       => array(
                         'dsn' => 'pgsql:host=10.1.0.100;dbname=umvc',
                         'user' => 'postgres',
                         'password' => ''
                       )
  
);

?>