<?php
namespace App\Controller;

use Cake\Routing\Router;
use Cake\Cache\Cache;                                // キャッシュ
use Cake\Event\Event;                                // CakePHP EventHandler (beforeFilterを使用するのに必要)
use Cake\Http\Middleware\CsrfProtectionMiddleware;   // CSRFミドルウェア

use Cake\Utility\Hash;   // Hashアルゴリズム

class TutorialController extends Base\BaseController
{
    public function beforeFilter(Event $e)
    {
        parent::beforeFilter($e);
        $this->loadComponent('Config');
        $this->loadComponent('Auth');
        $this->loadComponent('Validate');

        $this->Config->Initialize([]);
    }
    public function Index()
    {
        $this->set('title', 'CakePHPチュートリアル');
    }
}
