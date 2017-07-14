<?php 
global $_load;
if($_load->load_controller('Affichage') === false){exit('ErreurC');} 
if(!method_exists($_load->affichage, "ajx_tableContent")){exit("ErreurM");}
@$_load->affichage->ajx_tableContent();