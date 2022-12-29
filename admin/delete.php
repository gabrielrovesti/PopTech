<?php

    require_once "../includes/connection.php";
    require_once "../includes/utilities.php";

    use DB\DBAccess;

    session_start();

    $template = file_get_contents('layouts/layout.html');
    $form     = file_get_contents('layouts/elimina.html');

    $connection = new DBAccess();

    $pageID = 'homePage';
    $title = "Pop Tech";
    $breadcrumbs = '<p>Ti trovi in: Elimina</p>';

    $typeText = "";
    $nome     = "";
    $backLink = "#";

    if(!isLoggedIn(true)){

        $content = '<p class="message errorMsg">Attenzione non disponi dei privilegi necessari per accede a questa pagina.</p>';

    }else{

        $content = "";

        if($connection->open_connection()){

            
            if( isset($_GET['id']) && isset($_GET['type']) && !isset($_POST['submit'])){
            //Produzione form di eliminazione       

                $id = intval(sanitize($_GET['id'],""));
                $type = sanitize($_GET['type'],"");


                $content = "<h1>Elimina</h1>";            

                switch($type){
                    
                    case 'prodotto':
                        
                        $typeText = "del prodotto";
                        $backLink = "prodotti.php";
                        $products = $connection->exec_select_query('SELECT id, nome FROM prodotto WHERE id='.$id.';');
                        if(isset($products[0])){
                            $product = $products[0];
                            $nome    = $product['nome'];
                        }

                    break;
                    
                    case 'marca':

                        $typeText = "della marca";
                        $backLink = "marche.php";
                        $brands = $connection->exec_select_query('SELECT id, nome FROM marca WHERE id='.$id.';');
                        if(isset($products[0])){
                            $brand = $brands[0];
                            $nome  = $brand['nome'];
                        }

                    break;

                    case 'categoria':


                        $typeText = "della categoria";
                        $backLink = "categorie.php";
                        $categories = $connection->exec_select_query('SELECT id, nome FROM categoria WHERE id='.$id.';');
                        if(isset($categories[0])){
                            $category = $categories[0];
                            $nome     = $category['nome'];
                        }

                    break;

                    case 'utente':


                        $typeText = "dell'utente'";
                        $backLink = "utenti.php";
                        $users = $connection->exec_select_query('SELECT id, nome FROM utente WHERE id='.$id.';');
                        if(isset($users[0])){
                            $user = $users[0];
                            $nome = $user['nome'];
                        }

                    break;

                    case 'recensione':


                        $typeText = "della recensione";
                        $backLink = "recensioni.php";
                        $reviews = $connection->exec_select_query('SELECT id FROM recensione WHERE id='.$id.';');
                        if(isset($reviews[0])){
                            $review = $reviews[0];
                            $nome   = '';
                        }

                    break;

                    default:
                        $content = '<p class="message errorMsg">L\'indirizzo digitato non è corretto. Selezionare un elemento da eliminare dalla lista.</p>';
                    break;
                }

                $form = str_replace('{{id}}',$id,$form);
                $form = str_replace('{{type}}',$type,$form);
                $form = str_replace('{{nome}}',$nome,$form);
                $form = str_replace('{{typeText}}',$typeText,$form);
                $form = str_replace('{{backLink}}',$backLink,$form);

                $content .= $form;
                

            }elseif(isset($_POST['submit'])){
                //Operazione di eliminazione 

                $errors = [];

                if(!isset($_POST['id']) || intval($_POST['id'])<=0){
                    array_push($errors,"Errore: ID non selezionato.");
                }

                if(!isset($_POST['type']) || strlen($_POST['type'])<=4){
                    array_push($errors,"Errore: tipo non selezionato.");
                }

                $id   = intval($_POST['id']);
                $type = sanitize($_POST['type'],'');

                if(count($errors)==0){
                            
                        $queryOK = $connection->exec_alter_query("DELETE FROM $type WHERE id=$id;");
                
                        if($queryOK){
                            $content .= '<p class="message successMsg">Elemento eliminato con successo</p>';
                        }else{
                            $content .= '<p class="message errorMsg">Eliminazione non riuscita.</p>';
                        } 
                
                    }else{
                        $content .= '<p>I sistemi sono momentaneamente fuori servizio. Ci scusiamo per il disagio.</p>';
                    }

            
                    

            }else{
                $content = '<p class="message errorMsg">L\'indirizzo digitato non è completo. Selezionare un elemento da eliminare dalla lista.</p>';
            }

        }else{
            $content .= '<p class="message errorMsg">Errore nell\'eliminazione. Contatta il supporto tecnico.</p>';
        }      

    }

    $menu = get_admin_menu();
    $template = str_replace('{{menu}}',$menu,$template);

    $template = str_replace('{{title}}',$title,$template);
    $template = str_replace('{{breadcrumbs}}',$breadcrumbs,$template);
    $template = str_replace('{{content}}',$content,$template);

    echo $template;

?>