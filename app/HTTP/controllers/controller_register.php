<?php
include_once _api.'api_auth.php';

class controller_register extends Controller
{
    public function action_index($gets=null)
    {
        if(!((new api_auth())->check_login()))
            header('Location: http://direct.ru/');

        $this->view->generate('register.php', 'layout.php');
    }

}