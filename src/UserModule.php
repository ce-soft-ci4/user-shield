<?php

namespace UserShield\UserModule;

use CodeIgniter\Config\BaseService;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

/**
 * UserModule
 *
 * @package UserShield\UserModule
 */
class UserModule extends BaseService
{
    /**
     * Initialise le module.
     */
    public static function initialize()
    {
        $viewsPath = __DIR__ . '/Views';
        Services::renderer($viewsPath);
    }

}