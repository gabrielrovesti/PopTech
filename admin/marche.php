<?php

    require_once "../includes/connection.php";
    require_once "../includes/utilities.php";

    use DB\DBAccess;

    session_start();

    $template = file_get_contents('layouts/layout.html');

    $pageID = 'homePage';
    $title = "Pop Tech";
    $breadcrumbs = '<p>Ti trovi in: Marche</p>';

    if(!isLoggedIn(true)){

        $content = '<p class="message errorMsg">Attenzione non disponi dei privilegi necessari per accede a questa pagina.</p>';

    }else{

        $content = "<h1>marche</h1>";

        $content .= '<a href="marca.php" class="btn btn-green">Aggiungi Marca</a>';

        $connection = new DBAccess();

        if($connection->open_connection()){

            $brands = $connection->exec_select_query('SELECT id, nome FROM marca ORDER BY nome;');
            $connection->close_connection();

            foreach($brands as $brand){
                
                $content .= '<article class="listItem">';
                    $content .= '<span>'.parse_lang($brand['nome']).'</span>';
                    $content .= '<a href="marca.php?id='.$brand['id'].'" class="btn btn-info">Modifica</a>';
                    $content .= '<a href="delete.php?id='.$brand['id'].'&type=marca" class="btn btn-danger">Elimina</a>';
                $content .= '</article>';

            }

        }else{
            $content .= getDBConnectionError(true);
        }

    }

    $menu = get_admin_menu();
    $template = str_replace('{{menu}}',$menu,$template);

    $template = str_replace('{{title}}',$title,$template);
    $template = str_replace('{{breadcrumbs}}',$breadcrumbs,$template);
    $template = str_replace('{{content}}',$content,$template);

    echo $template;

?>