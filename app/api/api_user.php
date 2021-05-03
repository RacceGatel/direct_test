<?php

include_once _models . 'model_users.php';
include_once _api . 'api_auth.php';

class api_user extends BaseApi
{
    private $id;
    private $name;
    private $email;
    private $phone;

    public function get_by_id($gets)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $user = new model_users();

        $params = $this->get_params($gets,array('id'));

        $data = $user->get_row_by_id($params['id'])->id;

        if ($data != null) {
            http_response_code(200);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(array('message' => 'not found'), JSON_UNESCAPED_UNICODE);
        }
    }

    public function get_all($gets = null)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        $user = new model_users();
        http_response_code(200);
        echo json_encode($user->get_all(), JSON_UNESCAPED_UNICODE);
    }

    public function put_data($gets = null)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $params = $this->get_params($gets,array('name','email','phone','type'));

        switch ($params['type']) {
            case 'db':
                $this->put_db($params);
                break;
            case 'cache':
                $this->put_cache($params);
                break;
            case 'json':
                $this->put_json($params);
                break;
            case 'xlsx':
                $this->put_xlsx($params);
                break;
        }
    }

    public function put_db($params)
    {
        $this->check_auth();
        $this->valid_params($params);
        $user = new model_users();
        $user->insert(array($params['name'], $params['email'], $params['phone']));
    }

    public function put_cache($params)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $this->check_auth();

        http_response_code(200);

        echo json_encode($params, JSON_UNESCAPED_UNICODE);
    }

    public function get_json()
    {
        $path = _storage . 'users.json';

        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=users.json");
        header("Content-Type: application/json");
        header("Content-Transfer-Encoding: binary");

        readfile($path);
    }

    public function put_json($params)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $this->check_auth();

        $path = _storage . 'users.json';

        try {
            $json = file_get_contents($path);
            $data = json_decode($json);

            if ($data != null)
                array_push($data, array("id" => count($data) + 1, "name" => $params['name'], "email" => $params['email'], "phone" => $params['phone']));
            else
                $data = [array("id" => 1, "name" => $params['name'], "email" => $params['email'], "phone" => $params['phone'])];

            file_put_contents($path, json_encode($data));
            http_response_code(200);
        } catch (Exception $ex) {
            http_response_code(404);
            echo $ex;
        }
    }

    public function get_xlsx()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $path = _storage . 'users.xlsx';

        require_once _root.'/Classes/PHPExcel.php';

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load($path);

        $objPHPExcel->setActiveSheetIndex(0);

        $rowCount = 1;
        if($objPHPExcel->getActiveSheet()->getCell('A1')->getValue()!=NULL) {
            while ($row = $objPHPExcel->getActiveSheet()->getCell('A' . $rowCount)->getValue() != NULL) {
                $rowCount++;
            }
        } else
            $rowCount = 1;

        $data = [];
        $active = $objPHPExcel->getActiveSheet();

        for($i=1; $i<$rowCount; $i++)
        {
            array_push($data, array("id"=>$active->getCell('A'. $i)->getValue(),
                "name"=>$active->getCell('B' . $i)->getValue(),
                "email"=>$active->getCell('C' . $i)->getValue(),
                "phone"=>$active->getCell('D' . $i)->getValue()));
        }
        http_response_code(200);
        echo json_encode($data);
    }

    public function get_xlsx_file()
    {
        $path = _storage . 'users.xlsx';

        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=users.xlsx");
        header("Content-Type: application/xlsx");
        header("Content-Transfer-Encoding: binary");

        readfile($path);
    }


    public function put_xlsx($params)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $this->check_auth();

        require_once _root.'/Classes/PHPExcel.php';

        $path = _storage . 'users.xlsx';

        $fileType = 'Excel2007';
        $fileName = $path;

        $objReader = PHPExcel_IOFactory::createReader($fileType);
        $objPHPExcel = $objReader->load($fileName);

        $objPHPExcel->setActiveSheetIndex(0);

        $rowCount = 1;
        if($objPHPExcel->getActiveSheet()->getCell('A1')->getValue()!=NULL) {
            while ($row = $objPHPExcel->getActiveSheet()->getCell('A' . $rowCount)->getValue() != NULL) {
                $rowCount++;
            }
        } else
            $rowCount = 1;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$rowCount, $rowCount)
            ->setCellValue('B'.$rowCount, $params['name'])
            ->setCellValue('C'.$rowCount, $params['email'])
            ->setCellValue('D'.$rowCount, $params['phone']);

        try {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
            $objWriter->save($fileName);
            http_response_code(200);
        } catch (Exception $ex)
        {
            echo $ex;
            http_response_code(400);
        }
    }

    private function check_auth() {
        if ((new api_auth())->check_login()) {
            http_response_code(401);
            exit;
        }
    }

    public function refresh_db() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $this->check_auth();

        try {
            (new model_user_pass())->refresh_table();
            (new model_users())->refresh_table();
            (new api_auth())->logout();
            http_response_code(200);
        } catch (PDOException $ex){
            http_response_code(401);
            echo json_encode($ex);
        }


    }
}