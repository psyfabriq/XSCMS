<?php
Registry::set('Resource'      , '../resource/');                   //Folder resource
Registry::set('ResourceCore'  , '../resource/core/');              //Folder resource core
Registry::set('ResourceError' , '../resource/core/err_template/'); //Folder resource core
Registry::set('ResourceAdmin' , '../resource/admin/');             //Folder admin template
Registry::set('ResourceSite'  , '../resource/site/');              //Folder template
Registry::set('SiteIMG'        , '../images_public/');       //Folder images
Registry::set('TemplateEngine' , '../templates/');                   //Folder resource
Registry::set('UserPhotos'     , '../images_public/users/');                   //Folder resource
Registry::set('ImageTMP'       , '../images_public/tmp/');                   //Folder resource
Registry::set('Logs'           , '../log/');                   //Folder resource
Registry::set('Cache'      , '../cache/');                   //Folder cache

Registry::lock('Resource');
Registry::lock('ResourceCore');
Registry::lock('ResourceError');
Registry::lock('ResourceAdmin');
Registry::lock('ResourceSite');
Registry::lock('SiteIMG');
Registry::lock('TemplateEngine');
Registry::lock('UserPhotos');
Registry::lock('ImageTMP');
Registry::lock('Logs');
Registry::lock('Cache');
?>