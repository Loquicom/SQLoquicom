<?php

defined('FC_INI') or exit('Acces Denied');

class Requeteur extends FC_Controller {

    public function __construct() {
        parent::__construct();
        $this->session->connect !== false or redirect('Connexion');
        $this->load->model('Requeteur_model');
    }

    public function index() {
        $page = $this->load->view('requeteur', null, true);
        $this->load->view('webpage', array('body' => $page));
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
        $requetes = explode(';', $this->post('requete'));
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
                $html .= '<td>' . $val . '</td>';
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
