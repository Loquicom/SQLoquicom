<?php

defined('FC_INI') or exit('Acces Denied');

class Requeteur extends FC_Controller {

    /**
     * Liste des tables de la base
     * @var mixed 
     */
    private $table = array();

    /**
     * Liste des champs de la base
     * @var mixed
     */
    private $champ = array();

    public function __construct() {
        parent::__construct();
        $this->session->connect !== false or redirect('Connexion');
        $this->load->model('Requeteur_model');
        //Ajout de la liste des champs et des tables
        if ($this->session->get('requeteur') !== false) {
            $this->table = $this->session->requeteur['table'];
            $this->champ = $this->session->requeteur['champ'];
        } else {
            //Recup des infos
            $this->load->model('Affichage_model');
            //Les tables
            $tables = $this->affichage_model->getTables();
            foreach ($tables as $table => $ligne) {
                $this->table[] = $table;
            }
            //Les champs
            foreach ($this->table as $table) {
                $champs = $this->affichage_model->getColumn($table);
                $this->champ[$table] = $champs['list'];
            }
            //Stockage en Seesion pour 60s (pour rester à jour)
            $this->session->add_temp(array('requeteur' => array('table' => $this->table, 'champ' => $this->champ)), '', 60);
        }
    }

    public function index() {
        $page = $this->load->view('requeteur', null, true);
        $this->load->view('webpage', array('body' => $page));
    }

    public function ajx_autocomplete() {
        if ($this->post('search') === false) {
            exit;
        }
        $search = $this->post('search');
        $result = array();
        foreach ($this->table as $table) {
            //Si la table commence par $search on l'ajoute
            if (strtolower(substr($table, 0, strlen($search))) == strtolower($search)) {
                $result[] = $table;
            }
            foreach ($this->champ[$table] as $champ) {
                //Si le champ commence par $search on l'ajoute
                if (strtolower(substr($champ, 0, strlen($search))) == strtolower($search)) {
                    $result[] = $champ;
                }
            }
        }
        //Envoie du resultat
        echo json_encode($result);
    }

    public function ajx_read_file() {
        $encodedData = str_replace(' ', '+', $this->post('file'));
        $decodedData = base64_decode($encodedData);
        echo $decodedData;
    }

    public function ajx_requete() {
        if ($this->post('requete') === false) {
            echo json_encode(array('etat' => 'err', 'message' => 'Parametre absent'));
            exit;
        }
        //Decoupage des requetes au niveau des ;
        $requetes = explode(';', html_entity_decode($this->post('requete'), ENT_QUOTES));
        //On execute chaque requete
        $return = array('etat' => 'ok', 'info' => '', 'view' => array());
        foreach ($requetes as $i => $requete) {
            //Si la requete n'est pas vide
            if (trim($requete) != '') {
                $result = $this->requeteur_model->execute($requete);
                //Si le resultat est un string alors erreur
                if (is_string($result)) {
                    $return['info'] .= $this->toInfo('Requete ' . ( $i + 1 ) . ' [ ' . mb_strimwidth(trim($requete), 0, 86, '...') . ' ] :', $result, array('class' => 'danger', 'icone' => 'error'));
                }
                //si la requete est un boolean ( === true ) rien a afficher masi tous c'est bien passé
                else if ($result === true) {
                    $return['info'] .= $this->toInfo('Requete ' . ( $i + 1 ) . ' [ ' . mb_strimwidth(trim($requete), 0, 86, '...') . ' ] :', 'Requete ex&eacute;cut&eacute;e avec succ&egrave;s');
                }
                //Sinon si c'est un tableau on le prepare pour l'affichage
                else if (is_array($result)) {
                    //Si la tableau est vide
                    if (empty($result)) {
                        $return['view'][] = '<h3 style="padding-top: .6em;">' . 'Requete ' . ( $i + 1 ) . ' [' . mb_strimwidth(trim($requete), 0, 56, '...') . ']' . '</h3><div class="center">Aucun r&eacute;sultat</div>';
                    } else {
                        $return['view'][] = $this->toHtml($result, 'Requete ' . ( $i + 1 ) . ' [' . mb_strimwidth(trim($requete), 0, 56, '...') . ']');
                    }
                }
                //Sinon erreur
                else {
                    $return['info'] .= $this->toInfo('Requete ' . ( $i + 1 ) . ' [ ' . mb_strimwidth(trim($requete), 0, 86, '...') . ' ] :', 'Erreur lors de l\'ex&eacute;cution', array('class' => 'danger', 'icone' => 'error'));
                }
            }
        }
        //Renvoie en json des infos
        echo json_encode($return);
    }

    private function toHtml($result, $title = 'Requete') {
        //Recuperation des clef
        $keys = array_keys($result[0]);
        //Entete du tableau
        $html = '<h3 style="padding-top: .6em;">' . $title . '</h3><table class="table"><thead><tr>';
        foreach ($keys as $key) {
            $html .= '<th>' . $key . '</th>';
        }
        $html .= '</tr></thead><tbody>';
        //Creation du tableau html
        foreach ($result as $line) {
            $html .= '<tr>';
            foreach ($line as $val) {
                $html .= '<td>' . str_replace("\n", "<br>", $val) . '</td>';
            }
            $html .= '</tr>';
        }
        //Fin du tableau
        $html .= '</tbody></table>';
        //Retour
        return $html;
    }

    private function toInfo($title, $text, $type = array('class' => 'info', 'icone' => 'info')) {
        return <<<HTML
<div class="row-fluid alert a-is-{$type['class']}" id="return_zone" style="margin-top: 3em;">
    <i class="material-icons" id="return_icone">{$type['icone']}</i>
    <div class="col11" id="return_text">
        <b>{$title}</b>
        <div style="padding-left: 1em; padding-top: .5em;">{$text}</div>
    </div>
</div>

HTML;
    }

}
