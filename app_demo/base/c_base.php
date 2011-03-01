<?
class base_controller extends uf_controller
{
  public function before_action()
  {
    parent::before_action();
    $this->meta_description = 'UMVC';
    $this->meta_keywords = 'php, mvc, framework, web development';
  }
}
?>