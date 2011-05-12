<?
// one-to-one aliases for action parameter names

// used by the view of another controller to determine a link to this module

switch ($lang)
{
  case 'sv-se':
  {
    switch($action)
    {
      case 'language':
      {
        switch ($param)
        {
          case 'num-speakers': return 'antal-talare';
          case 'word-count': return 'antal-ord';
        }
      }
      case 'todo_list':
      {
        switch ($param)
        {
          case 'page': return 'sida';
        }
      }
    }
  }
}

