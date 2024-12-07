<?php

// サーバの設定

namespace Error\Important;

class Session
{
    private $init;
    private $session;
    public function __construct()
    {
        $this->read();
        $this->init = $this->session;
    }

    private function write()
    {
        $_SESSION = $this->session;
    }

    public function add($sessionElm, $sessionVal)
    {
        $this->session[$sessionElm] = $sessionVal;
        $this->write();
    }

    public function read($sessionElm=null)
    {
        if (isset($_SESSION)) {
            $this->session = $_SESSION;
        } else {
            throw new Error('Session is not found.');
        }
        if (isset($sessionElm)) {
            return $this->session[$sessionElm];
        } else {
            return $this->session;
        }
    }

    public function delete($sessionElm=null)
    {
        if (!isset($_SESSION)) {
            throw new Error('Session is already deleted.');
            exit;
        }
        if (isset($sessionElm)) {
            unset($this->session[$sessionElm]);
            $this->write();
        } else {
            unset($this->session);
            $this->session = $this->init;
        }
    }

    // セッション閲覧用
    public function view($id=null)
    {
        if (isset($id)) {
            if (isset($this->session[$id])) {
                echo $this->session[$id];
            } else {
                return false;
            }
        } else {
            var_dump($this->session);
        }
        return true;
    }

    // セッションの完全な破棄s
    public function destroy()
    {
        session_unset();

        // セッションを切断するにはセッションクッキーも削除する。
        // Note: セッション情報だけでなくセッションを破壊する。
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-42000, '/');
        }

        // 最終的に、セッションを破壊する
        session_destroy();
    }
}
