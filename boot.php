<?php
// подключаем файлы ядра
//require_once 'system/registry.php';
require_once 'system/core/model.php';
require_once 'system/core/view.php';
require_once 'system/core/controller.php';
require_once 'system/libs/site.lib.php';
require_once 'system/models/model_general.php';
require_once 'system/libs/acl.lib.php';
require_once 'system/libs/json.php';
require_once 'system/libs/file_manager.php';
require_once "system/backup/backup.inc.php";
require_once 'system/core/route.php';
include_once 'system/libs/imageupload.lib.php';


Route::start($context); // запускаем маршрутизатор
?>