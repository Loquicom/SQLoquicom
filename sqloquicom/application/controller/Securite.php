<?php

defined('FC_INI') or exit('Acces Denied');

class Securite extends FC_Controller {

    private $secure = false;
    private $hash = '';
    private $connect = false;

    public function __construct() {
        parent::__construct();
        //Regarde si la sécurité est set
        if (file_exists('../data/secure.dat')) {
            //Parametre
            $this->secure = true;
            $this->hash = file_get_contents('../data/secure.dat');
            //Regarde si la sécurite est passée
            if ($this->session->secure !== false) {
                $this->connect = true;
            }
        }
    }

    public function is_secure() {
        return $this->secure;
    }

    public function is_connect() {
        return $this->connect;
    }

    public function index() {
        //Si non activé ou deja connecté
        if (!( $this->secure && !$this->connect )) {
            redirect('Connexion');
        }
        //Si un mot de passe est envoyé
        $err = null;
        if ($this->post('pass') !== false) {
            //Verification
            if (password_verify($this->post('pass'), $this->hash)) {
                //Connexion validée
                $this->session->secure = true;
                $this->session->pass = $this->post('pass');
                redirect('Connexion');
            }
            //Sinon message d'erreur
            else {
                $err = 'Mot de passe invalide';
            }
        }
        //Chargement vue
        $page = $this->load->view('secure_connect', array('err' => $err), true);
        $this->load->view('webpage', array('body' => $page));
    }

    public function active() {
        //Si la securite est active et pas encore passée
        if ($this->secure && !$this->connect) {
            redirect('Securite');
        }
        //Si la securite est deja active redirige vers les parametres
        if ($this->secure) {
            redirect('Parametre');
        }
        //Si il y eu le retour d'un formulaire
        $err = null;
        if ($this->post('pass') !== false) {
            //Verif que le nouveau mot de passe est identique
            if ($this->post('pass') == $this->post('conf_pass')) {
                //Creation fichier secure
                file_put_contents('../data/secure.dat', password_hash($this->post('pass'), PASSWORD_BCRYPT));
                //Charge le model
                $this->load->model('Securite_model');
                //Chiffrement des fichiers
                $this->session->pass = $this->post('pass');
                $securite = new Securite_model(true);
                $securite->crypt_files();
                //Redirection
                redirect('Securite');
            }
            $err = "Les mots de passes ne sont pas identiques";
        }
        //Chargement de la vue pour la modification
        $page = $this->load->view('secure_active', array('err' => $err), true);
        $this->load->view('webpage', array('body' => $page));
    }

    public function desactive() {
        //Si la securite est active et pas encore passée
        if ($this->secure && !$this->connect) {
            redirect('Securite');
        }
        //Si la securite n'est pas active redirige vers les parametres
        if (!$this->secure) {
            redirect('Parametre');
        }
        //Si il y eu le retour d'un formulaire
        $err = null;
        if ($this->post('pass') !== false) {
            //Verif le mot de passe
            if (password_verify($this->post('pass'), $this->hash)) {
                //Charge le model
                $this->load->model('Securite_model');
                //Dechiffrement des fichiers
                $this->Securite_model->decrypt_files();
                //Supprime le fichier secure.dat
                unlink('../data/secure.dat');
                $this->session->secure = false;
                redirect('Parametre');
            }
            $err = "Le mot de passe est invalide";
        }
        //Chargement de la vue pour la modification
        $page = $this->load->view('secure_desactive', array('err' => $err), true);
        $this->load->view('webpage', array('body' => $page));
    }

    public function change() {
        //Si la securite est active et pas encore passée
        if ($this->secure && !$this->connect) {
            redirect('Securite');
        }
        //Si la securite n'est pas active redirige vers les parametres
        if (!$this->secure) {
            redirect('Parametre');
        }
        //Si il y eu le retour d'un formulaire
        $err = null;
        if ($this->post('pass') !== false) {
            //Verif que le nouveau mot de passe est identique
            if ($this->post('new_pass') != $this->post('conf_new_pass')) {
                $err = "Les mots de passes ne sont pas identiques";
            } else {
                //Verif que l'ancien mot de passe est bon
                if (!password_verify($this->post('pass'), $this->hash)) {
                    $err = "L'ancien mot de passe est invalide";
                } else {
                    //Charge le model
                    $this->load->model('Securite_model');
                    //Dechiffrement des fichiers
                    $this->Securite_model->decrypt_files();
                    //Chiffrement des fichiers
                    $this->session->pass = $this->post('new_pass');
                    $securite = new Securite_model(true);
                    $securite->crypt_files();
                    //Change le mot de passe et deconnecte
                    file_put_contents('../data/secure.dat', password_hash($this->post('new_pass'), PASSWORD_BCRYPT));
                    $this->session->secure = false;
                    redirect('Securite');
                }
            }
        }
        //Chargement de la vue pour la modification
        $page = $this->load->view('secure_change', array('err' => $err), true);
        $this->load->view('webpage', array('body' => $page));
    }

}
