<?php
include_once _api.'api_auth.php';

class controller_login extends Controller
{
    public function action_index($gets=null)
    {
        session_start();
        if(isset($_SESSION['user'])) {
            header('Location: http://direct.ru/');
            exit;
        }
        $this->view->generate('login.php', 'layout.php');
    }

}