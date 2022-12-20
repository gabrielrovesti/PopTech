<?php


/*
    Rimpiazza i placeholder del template html
*/
function replace_in_page(String $html, String $title, String $id, String $breadcrumbs, String $content){

    //Header presente in ogni pagina
    $header = file_get_contents('./layouts/header.html');
    $html   = str_replace('{{header}}',$header,$html);
    
    $html = str_replace('{{title}}',$title,$html);
    $html = str_replace('{{pageID}}',$id,$html);
    $html = str_replace('{{breadcrumbs}}',$breadcrumbs,$html);
    $html = str_replace('{{content}}',$content,$html);

    //Footer presente in ogni pagina
    $footer = file_get_contents('./layouts/footer.html');
    $html   = str_replace('{{footer}}',$footer,$html);
    

    return $html;

}


/*
    Rimpiazza i codici per la lingua con tag span
*/
function parse_lang(String $string){


    //Rimpiazza i tag di fine con </span>
    $string = preg_replace('/\[\/.{2}\]/', '</span>', $string); 

    //Rimpiazza i tag di inizio con <span lang="xx">
    $string = preg_replace('/(\[)(.*?)(\])/', '<span lang="${2}">', $string);

    return $string;

}

/* 
    Rimpiazza {{menu}} con il menú in base alla pagina in cui si trova l'utente
*/
function get_menu(){

    $menu = '';

    // Link da inserire
    $links = ["index.php","prodotti.php","contatti.php","faq.php","chiSiamo.php"];
    // Nomi delle voci di menu
    $names = ["Home","Prodotti","Contatti","FAQ","Chi Siamo"];
    // Lingue dei link (se diverse da Italiano)
    $langs = ["en","","","en",""];
    // Numero dei link da mostrare (grandezza array)
    $nLinks = count($links);

    //Togliere dall'url restituito da PHP -- cambierà in base all'hosting (probilmente non sará necessario in fase di consegna)
    $strToRemove = "/poptech/sito/";
    $currentPage = str_replace($strToRemove,"",$_SERVER['REQUEST_URI']);

    for($i=0;$i<$nLinks;$i++){
        if($currentPage==$links[$i]){
            $menu .= '<li id="currentLink" '.(($langs[$i])?'lang="'.$langs[$i].'"':'').'>'.$names[$i].'</li>';
        }else{
            $menu .= '<li><a href="'.$links[$i].'" '.(($langs[$i])?'lang="'.$langs[$i].'"':'').'>'.$names[$i].'</a></li>';
        }
    }

    return $menu;

}

/*
    Pulisce la stringa per l'iunserimento in database rimuovendo tag indesiderati, 
    spazi superflui e limitando il rischio di attacchi
*/
function sanitize($input, $allowed_tags) {
    $input = strip_tags($input, $allowed_tags);
    $input = htmlentities($input, ENT_QUOTES, 'UTF-8');
    $input = stripslashes($input);
    $input = trim($input);
    return $input;
}


?>