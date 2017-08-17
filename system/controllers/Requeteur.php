<?php

(defined('APPLICATION')) ? '' : exit('Acces denied');

class Requeteur extends ControllerIni {

    public function __construct() {
        parent::__construct();
        $this->load->load_model('Requeteur_model');
    }

    public function index() {
        $page = $this->load->load_view('requeteur', null, true);
        $this->load->load_view('webpage', array('body' => $page));
    }

    public function ajx_requete() {
        if (!isset($_POST['requete'])) {
            echo json_encode(array('etat' => 'err', 'message' => 'Parametre absent'));
            exit;
        }
        //Decoupage des requetes au niveau des ;
        $requetes = explode(';', $_POST['requete']);
        //On execute chaque requete
        $return = array('etat' => 'ok', 'info' => '', 'view' => array());
        foreach ($requetes as $i => $requete) {
            //Si la requete n'est pas vide
            if (trim($requete) != '') {
                $result = $this->model->requeteur_model->execute($requete);
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
                    $return['view'][] = $this->toHtml($result, 'Requete ' . ( $i + 1 ) . ' [' . mb_strimwidth(trim($requete), 0, 56, '...') . ']');
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
        $html = '<h3>' . $title . '</h3><table class="table"><thead><tr>';
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
