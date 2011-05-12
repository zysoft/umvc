<?
// one-to-one aliases for action names

// used by the view of another controller to determine a link to this module

switch ($lang)
{
  case 'sv-se': 
  {
    switch($action)
    {
      case 'index': return 'index';
      case 'todo_list': return 'att-gora-lista';
      case 'language': return 'sprak';
    }
  }
}   
