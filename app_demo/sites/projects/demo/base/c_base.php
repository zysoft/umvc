<?

/****************************************************************************/
/* This is the base controller all modules should inherit from.             */
/* Use it to set default behavior                                           */
/****************************************************************************/

class base_controller extends uf_controller
{
  public function before_action()
  {
    $this->title = $this->language['base']['title'];
    $this->meta_description = 'UMVC';
    $this->meta_keywords = 'php, mvc, framework, web development';
  }
}
?>