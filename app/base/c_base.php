<?
class base_controller extends uf_controller
{
  public function before_action()
  {
    parent::before_action();
    $this->caller()->meta_description = 'UMVC';
    $this->caller()->meta_keywords = 'php, mvc, framework, web development';
  }
}
?>