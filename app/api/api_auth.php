<?php

include_once _models . 'model_users.php';
include_once _models . 'model_user_pass.php';

class api_auth extends BaseApi
{
    public function check_login()
    {
        session_start();
        if (isset($_SESSION['user'])) {
            ;
            http_response_code(200);
            return false;
        } else {
            http_response_code(200);
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

        if (!($this->check_login())) {
            http_response_code(200);
            header('Location: http://direct.ru/');
            exit;
        }

        $params = $this->get_params($gets, array('name', 'psw'));

        $users = new model_users();
        $pass = new model_user_pass();

        $id = ($users->get_id_by_name($params['name']))->id;

        if ($id && $pass->get_row_by_id($id)->pass == md5($params['psw'])) {
            session_start();
            $_SESSION['user'] = $params['name'];
            http_response_code(200);
            header('Location: http://direct.ru/');
        } else {
            http_response_code(401);
            header('Location: http://direct.ru/login?error=wrong_data');
        }

    }

    public function register($gets)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        if (!($this->check_login())) {
            header('Location: http://direct.ru/');
            exit;
        }

        $params = $this->get_params($gets, array('name', 'psw', 'phone', 'email'));

        $user = new model_users();
        $pass = new model_user_pass();

        $id = ($user->get_id_by_name($params['name']))->id;

        if (!$id) {
            $user->insert(array($params['name'], $params['email'], $params['phone']));
            $id = ($user->get_id_by_name($params['name']))->id;
            $pass->insert(array($id, md5($params['psw'])));

            session_start();
            $_SESSION['user'] = $params['name'];

            http_response_code(200);
            header('Location: http://direct.ru/');
        } else {
            http_response_code(401);
            header('Location: http://direct.ru/register?error=already_exist');
        }
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