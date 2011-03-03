<?

/****************************************************************************/
/* This is the base controller all modules should inherit from.             */
/* Use it to set default behavior                                           */
/****************************************************************************/

class base_controller extends uf_controller
{
  public function before_action()
  {
    parent::before_action();
    $this->meta_description = 'UMVC';
    $this->meta_keywords = 'php, mvc, framework, web development';
  }
  
  public function poop()
  {
    echo 'POOP';
    return FALSE;
  }
}
?>