<?php
include_once _api.'api_auth.php';

class controller_main extends Controller {

    function action_index($gets=null)
    {
        $data = ((new api_auth())->check_login());
        $this->view->generate('main.php','layout.php', $data);
    }
}