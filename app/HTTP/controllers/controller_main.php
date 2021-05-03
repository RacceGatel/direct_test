<?php
include_once _api.'api_auth.php';

class controller_main extends Controller {

    public function action_index($gets=null)
    {
        session_start();
        $this->view->generate('main.php','layout.php');
    }
}