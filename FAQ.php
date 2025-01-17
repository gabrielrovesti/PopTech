﻿<?php

    require_once "includes/connection.php";
    require_once "includes/utilities.php";

    use DB\DBAccess;

    $template = file_get_contents('layouts/layout.html');

    $pageID = 'FAQ';
    $title = "FAQ - Pop Tech";
    $breadcrumbs = '<p>Ti trovi in: <a href="index.php" lang="en">Home</a> &gt; <abbr title="Frequently Asked Questions" lang="en">FAQ</abbr></p> ';

    $content = '<h1><abbr title="Frequently Asked Questions" lang="en">FAQ</abbr></h1>';

    $connection = new DBAccess;

    $content .= '<div class="faqRow">';

    if ($connection->open_connection()) {
        $faqs = $connection->exec_select_query('SELECT id, domanda, risposta FROM faq;');
        $connection->close_connection();
        foreach($faqs as $faq){

            $content .= '<details open="">
                    <summary class="comic_box">'.parse_lang($faq['domanda']).'</summary>
                    <p class="comic_box2">'.parse_lang($faq['risposta']).'</p>
            </details>';
        }

        $content .= "</div>";
    }else{
        $title = "FAQ - Pop Tech";
        $breadcrumbs = '<p>Ti trovi in: <a href="index.php" lang="en">Home</a> &gt; <abbr title="Frequently Asked Questions" lang="en">FAQ</abbr></p> ';
        $content = '<h1><abbr title="Frequently Asked Questions" lang="en">FAQ</abbr></h1>';
        $content .= getDBConnectionError();
    }

    $menu = get_menu();
    $template = str_replace('{{menu}}',$menu,$template);
    echo replace_in_page($template,$title,$pageID,$breadcrumbs,'keywords','description',$content,'addScrollEventListener()');
?>