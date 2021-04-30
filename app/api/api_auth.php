<?php

include_once _models . 'model_users.php';
include_once _models . 'model_user_pass.php';

class api_auth extends BaseApi
{
    public function check_login($gets = null)
    {
        session_start();
        if (isset($_SESSION['user'])) {
            return false;
        } else {
            return true;
        }
    }

    public function login($gets)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        if (!($this->check_login()))
            header('Location: http://direct.ru/');

        $params = $this->get_params($gets,array('uname', 'psw'));

        $users = new model_users();
        $pass = new model_user_pass();

        $id = ($users->get_id_by_name($params['uname']))->id;


        if ($id != false) {
            $row = $pass->get_row_by_id($id);

            if ($row->pass == md5($params['psw'])) {
                session_start();
                $_SESSION['user'] = $params['uname'];

                http_response_code(200);
                header('Location: http://direct.ru/');
            }
        } else {
            http_response_code(401);
            header('Location: http://direct.ru/login');
        }
        header('Location: http://direct.ru/login');

    }

    public function register($gets)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        if (!($this->check_login()))
            header('Location: http://direct.ru/');


        $params = $this->get_params($gets,array('uname', 'psw', 'phone', 'email'));

        $users = new model_users();
        $pass = new model_user_pass();

        $id = ($users->get_id_by_name($params['uname']))->id;

        if ($id == false) {
            $users->insert(array($params['uname'], $params['email'], $params['phone']));
            $id = ($users->get_id_by_name($params['uname']))->id;;
            $pass->insert(array($id, md5($params['psw'])));
            http_response_code(200);
            header('Location: http://direct.ru/login');
        } else
            http_response_code(401);

    }

    public function logout()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        session_start();
        if (session_destroy())
            http_response_code(200);
        else
            http_response_code(500);
        header('Location: http://direct.ru/');

    }
}