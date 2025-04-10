<?php
namespace Config;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Events\Events;

Events::on('pre_system', function () {
    \UserShield\UserModule\UserModule::initialize();
});