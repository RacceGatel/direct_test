<?php

class Route
{
    static function index()
    {
        $routes = explode('/', $_SERVER['REDIRECT_URL']);
        if (!empty($routes[1]) && $routes[1] == 'api')
            self::Api($routes);
        else
            self::MainRoute($routes);
    }

    static function MainRoute($routes)
    {
        require_once _app.'core/view.php';
        require_once _app.'core/controller.php';
        $controller_name = 'main';
        $action_name = 'index';

        $gets = explode('&', urldecode($_SERVER['QUERY_STRING'])); // получаем параметры GET


        if (!empty($routes[1])) {
            $controller_name = $routes[1];
        }


        // получаем имя экшена
        if (!empty($routes[2])) {
            $action_name = $routes[2];
        }


        // добавляем префиксы
        $controller_name = 'controller_' . $controller_name;
        $model_name = 'model_' . $controller_name;
        $action_name = 'action_' . $action_name;

        // подцепляем файл с классом модели (файла модели может и не быть)

        $model_file = strtolower($model_name) . '.php';
        $model_path = _app . "models/" . $model_file;

        if (file_exists($model_path)) {
            include_once $model_path;
        }

        // подцепляем файл с классом контроллера
        $controller_file = strtolower($controller_name) . '.php';
        $controller_path = _app . "controllers/" . $controller_file;

        if (file_exists($controller_path)) {
            include_once $controller_path;
        } else {
            Route::ErrorPage404();
        }

        $controller = new $controller_name;
        $action = $action_name;

        include_once(_app . 'controllers/controller_login.php');

        if (method_exists($controller, $action)) {
            $controller->$action($gets);

        } else {
            Route::ErrorPage404();
        }
    }

    static function Api($routes)
    {
        require_once _api.'core/BaseApi.php';
        $api_name = null;
        $action_name = null;

        $gets = explode('&', urldecode($_SERVER['QUERY_STRING'])); // получаем параметры GET

        if (!empty($routes[2])) {
            $api_name = $routes[2];
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No api"), JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!empty($routes[3])) {
            $action_name = $routes[3];
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No function"), JSON_UNESCAPED_UNICODE);
            exit;
        }

        $api_name = 'api_'.$api_name;
        $api_path = _api.$api_name.'.php';

        if (file_exists($api_path)) {
            include_once $api_path;
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Api not found"), JSON_UNESCAPED_UNICODE);
            exit;
        }

        $api = new $api_name;

        if (method_exists($api, $action_name)) {
            $api->$action_name($gets);
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Method not found"), JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    static function ErrorPage404()
    {
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:' . $host . '404');
    }
}