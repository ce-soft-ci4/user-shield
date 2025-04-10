<?php

namespace UserShield\UserModule;

use CodeIgniter\Config\BaseService;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class UserModule extends BaseService
{
    /**
     * Initialise le module.
     */
    public static function initialize()
    {
        // Enregistrement des espaces de noms pour les vues
        /*$viewsPath = __DIR__ . '/Views';
        Services::renderer()->setPath($viewsPath);

        // Enregistrement des routes
        $routes = Services::routes();
        require __DIR__ . '/Config/Routes.php';

        // Enregistrement des filtres
        Services::filters()->add('usermodule', \UserShield\UserModule\Filters\UserModuleFilter::class);

        // Enregistrement des fichiers de langue
        $locale = service('request')->getLocale();
        $langPath = __DIR__ . '/Language/';
        service('language')->addNamespace('usermodule', $langPath);
        */
    }

    /**
     * Charge les migrations nécessaires.
     */
    /*public static function migrate()
    {
        $migrate = Services::migrations();
        $migrate->setNamespace('UserShield\UserModule');
        $migrate->latest();
    }*/
}