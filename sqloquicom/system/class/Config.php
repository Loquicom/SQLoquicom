<?php

/* ==============================================================================
  Fraquicom [PHP Framework] by Loquicom <contact@loquicom.fr>

  GPL-3.0
  Config.php
  ============================================================================ */
defined('FC_INI') or exit('Acces Denied');

class Config {

    private static $config = null;

    private function __construct() {
        
    }

    /**
     * Retourne l'instance de config
     * @return Config
     */
    public static function get_config() {
        if (self::$config === null) {
            return (self::$config = new Config());
        } else {
            return self::$config;
        }
    }

    /**
     * Accède à une valeur du tableau config
     * Autant de parametre que de clef pour accéder à la valeur ou un tableau avec toutes les clefs
     * @global mixed $config
     * @global mixed $_config
     * @return false|mixed
     */
    public function get() {
        global $config;
        global $_config;
        $conf = array_merge($config, $_config);
        //Si aucub parametre
        if (func_num_args() == 0) {
            return false;
        } 
        //Si 1 parametre
        else if (func_num_args() == 1) {
            //Si c'est un tableau de parametre on appel la fonction avec la bonne forme
            if(is_array(func_get_arg(0))){
                return call_user_func_array(array($this, 'get'), func_get_arg(0));
            }
            //Si la clef existe
            else if (isset($conf[func_get_arg(0)])) {
                return $conf[func_get_arg(0)];
            } else {
                return false;
            }
        } 
        //Si +1 parametres
        else {
            $args = func_get_args();
            foreach ($args as $arg) {
                if (isset($conf[$arg])) {
                    $conf = $conf[$arg];
                } else {
                    return false;
                }
            }
            return $conf;
        }
    }
    
    /**
     * Méthode magique pour accéder à une seul clef dans le tableau de config
     * $this->config->clef <=> $this->config->get('clef')
     * @param string $clef
     * @return false|mixed
     */
    public function __get($clef) {
        return $this->get($clef);
    }

}
