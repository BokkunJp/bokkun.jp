<?php
namespace Application\Summer;

class Summer extends Base\BaseController
{
  public function indexAction() {    
    $viewModel = new ViewModel();

    return $viewModel;
  }
}
