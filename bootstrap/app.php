<?php
require_once 'variables.php';
require_once _config.'Env.php';
require_once _app.'core/model.php';
require_once _app.'core/route.php';
require_once _api.'core/BaseApi.php';
(new Env(_root.'/.env'))->Write();

Route::index();