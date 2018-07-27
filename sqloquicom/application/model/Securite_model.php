<?php

defined('FC_INI') or exit('Acces Denied');

class Securite_model extends FC_Model {

    private $secure = false;
    private $file_no_scan = [
        '..',
        '.',
        '.htaccess',
        'export',
        'secure.dat',
        'index.html',
    ];

    public function __construct($isSecure = false) {
        parent::__construct();
        //Regarde si sécurité en place
        if ($this->session->secure ||$isSecure) {
            $this->secure = true;
            //Charge bibliotheque chiffrement
            $this->load->library('LcFeistel');
            $this->load->library('LcCryptoFeistel');
            $this->LcCryptoFeistel->set_key($this->session->pass);
        }
    }

    public function get_files() {
        $files = array_diff(scandir('../data/'), $this->file_no_scan);
        $res = [];
        foreach ($files as $file) {
            $tab = $this->get_file($file);
            if ($tab !== false) {
                $res = array_merge($res, $tab);
            }
        }
        return $res;
    }

    public function get_file($fileName) {
        //Verification de l'existance du fichier
        if (!file_exists('../data/' . $fileName) && !file_exists('../data/' . $fileName . '.lccf')) {
            return false;
        }
        //Si fichier chiffré
        if ($this->secure) {
            try {
                //Recup le nom
                if(strpos($fileName, '.lccf') !== false){
                    $name = str_replace('.lccf', '', '../data/' . $fileName);
                } else {
                    $name = '../data/' . $fileName;
                }
                //Déchiffre le contenu
                $newName = $this->LcCryptoFeistel->decrypt_file($name);
                $data = file_get_contents($newName);
                unlink($newName);
                $name = basename($name);
            } catch (LcFeistel_Exception $ex) {
                return false;
            }
        }
        //Sinon
        else {
            $name = $fileName;
            $data = file_get_contents('../data/' . $fileName);
        }
        //Verification de la validité du fichier
        $data = explode("\r\n", $data);
        //Si le fichier ne contient pas assez d'info suppr
        if (count($data) < 2) {
            unlink('../data/' . $fileName);
            return false;
        }
        //Verification que le hash est bon
        if ($data[1] !== md5($data[0])) {
            unlink('../data/' . $fileName);
            return false;
        }
        //Extraction des données
        $content = base64_decode($data[0]);
        list($md5, $host, $dbName, $usr, $pass) = explode("\r\n", $content);
        //Verification de l'intégrité du fichier
        if ($md5 != md5(str_replace('.dat', '', $name))) {
            unlink('../data/' . $fileName);
            return false;
        }
        //Préparation et retour
        $res = [
            $name => [
                'host' => $host,
                'name' => $dbName,
                'usr' => $usr,
                'pass' => $pass,
            ]
        ];
        return $res;
    }

    public function add_file($info) {
        //Nom du fichier
        $fileName = '../data/' . $info['host'] . '(-)' . $info['name'] . '.dat';
        //Generation du contenue
        $content = md5($info['host'] . '(-)' . $info['name']) . "\r\n";
        $content .= $info['host'] . "\r\n";
        $content .= $info['name'] . "\r\n";
        $content .= $info['usr'] . "\r\n";
        $content .= $info['pass'];
        $content = base64_encode($content);
        $content .= "\r\n" . md5($content);
        //Ecriture dans le fichier
        file_put_contents($fileName, $content);
        //Si la securite est active
        if ($this->secure) {
            $this->crypt_files();
        }
    }

    public function remove_file($fileName) {
        //Regarde si le fichier est chiffré ou non
        if (file_exists('../data/' . $fileName)) {
            unlink('../data/' . $fileName);
            return true;
        } else if (file_exists('../data/' . $fileName . '.lccf')) {
            unlink('../data/' . $fileName . '.lccf');
            return true;
        } else {
            return false;
        }
    }

    public function crypt_files() {
        //Necesite la securite active
        if (!$this->secure) {
            return false;
        }
        //Chiffrement de tous les fichiers non chiffrées
        $files = array_diff(scandir('../data/'), $this->file_no_scan);
        foreach ($files as $file) {
            //Si le fichier est deja chiffré on ne fait rien
            if (strpos($file, '.lccf') !== false) {
                continue;
            }
            //Chiffre le fichier
            try {
                $res = $this->LcCryptoFeistel->crypt_file('../data/' . $file, '../data/' . $file);
                unlink('../data/' . $file);
            } catch (LcFeistel_Exception $ex) {
                
            }
        }
    }

    public function decrypt_files() {
        //Necesite la securite actve
        if (!$this->secure) {
            return false;
        }
        //Déchiffre tous les fichiers chiffrées
        $files = array_diff(scandir('../data/'), $this->file_no_scan);
        foreach ($files as $file) {
            //Si le fichier n'est pas chiffré on ne fait rien
            if (strpos($file, '.lccf') === false) {
                continue;
            }
            //Chiffre le fichier
            try {
                $res = $this->LcCryptoFeistel->decrypt_file('../data/' . str_replace('.lccf', '', $file));
                unlink('../data/' . $file);
            } catch (LcFeistel_Exception $ex) {
                
            }
        }
    }

}
