<?php
IncludeFiles('../Tag.php');
class DebugClass extends HTMLClass {
  private $debug_tag, $message;
  function __construct($mode='detail') {
    $this->Initialize(true);
    $this->debug_tag = 'span';
    if ($mode != 'nomal') {
      $this->AllowAuthority('pre');
      $this->debug_tag = 'pre';
    }
    $this->TagSet($this->debug_tag, 'debug_test', 'debug', true);
  }

  private function setMessage($message, $view) {
    if ($view === true) {
      $this->TagSet($this->debug_tag, implode(",", $message), 'debug', true);
      $this->message = $this->TagExec();
    } else {
      $this->message = $message;
    }

    if ($view === true) {
      print_r($this->message);
    }
  }

  public function Debug($message='', $view=false) {
    switch ($message) {
      case 'file':
      $this->message = get_included_files();
      break;
      case 'const':
      $this->message = get_defined_constants();
      break;
      case 'detail':
      $this->message = debug_backtrace();
      default:
      break;
    }
    $this->setMessage($this->message, $view);
    if ($view == false) {
      return $this->message;
    }
  }
}
