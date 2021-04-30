<?php


class BaseApi
{
    protected function get_params($gets, $params) {
        $keys =array();
        $val = array();
        foreach ($gets as $get) {
            $name = explode('=', $get);
            foreach($params as $param)
                if($name[0]==$param)
                {
                    array_push($keys, $param);
                    array_push($val, $name[1]);
                }
        }

        return array_combine($keys,$val);
    }
}