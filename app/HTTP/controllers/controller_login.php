<?php
include_once _api.'api_auth.php';

class controller_login extends Controller
{
    public function action_index($gets=null)
    {
        if(!((new api_auth())->check_login()))
            header('Location: http://direct.ru/');

        $this->view->generate('login.php', 'layout.php');
    }

}