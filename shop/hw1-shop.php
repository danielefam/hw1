<?php
    session_start();
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Pistacchissimo Shop</title>
        <link rel="icon"href="https://shop.pistacchissimo.it/img/favicon.ico?1604966480">

        <!-- font manrope per l'header -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel = "stylesheet" type="text/css" href = "style/hw1-shop.css">
        <script src="scripts/hw1-shop.js" defer></script>
        <script src="scripts/hw1-apiCheckEmail.js" defer></script>
        <script src="scripts/hw1-ricerca.js" defer></script>
    </head>
    <body>
        
        <section id="prima-riga-esterno">
            <div id ="prima-riga-interno" class = "header-margini">
                
                <div id = "menu">
                    <a class = "fa fa-bars"></a>
                    <a href="../index.php"><img src="https://shop.pistacchissimo.it/img/my-shop-logo-1604849342.jpg"></a>
                    <nav class = "hidden"> 
                        <span><a href="#"><span>pasqua</span></a></span>
                        <span><a href="#"><span>colombe</span></a></span>
                        <span><a href="#"><span>uova di pasqua</span></a></span>
                        <span><a href="#"><span>vasetti e secchi</span></a></span>
                        <span><a href="#"><span>senza glutine</span></a></span>
                        <span><a href="../index.php"><span>blog</span></a></span> 
                        <span><a href="../index.php">Contattaci</a></span>                               
                    </nav>
                </div>
                <a id="contattaci">Contattaci</a>
                
                
                
                <nav>
                    <div>
                        <a 
                        <?php if(!isset($_SESSION['email'])){
                                        echo "class = \"hidden\"";
                                    }
                                    else{
                                        echo "href = \"hw1-logout.php\"";
                                    }
                            ?>
                        >
                            Esci
                        </a>
                        <a href = 
                            <?php if(isset($_SESSION['email'])){
                                        echo "pagina_utente/hw1-utente.php";
                                    }
                                    else{
                                        echo "hw1-login-signup.php";
                                    }
                            ?>> 
                            
                            <span class="material-symbols-outlined">
                                person
                            </span>
                            <span>
                                <?php
                                    if(isset($_SESSION['email'])){
                                        echo "Ciao ". $_SESSION['nome'];
                                    }
                                    else{
                                        echo "Accedi";
                                    }
                                ?>
                            </span>
                        </a>
                    </div>

                    <div id = "carrello">
                        <a href=
                            <?php
                                // Verifica se l'utente è loggato
                                if(!isset($_SESSION['email'])){
                                    echo "hw1-login-signup.php";
                                }
                                else
                                    echo "pagina_utente/hw1-utente.php";                            
                            ?>
                        >
                            <span class="material-symbols-outlined">
                                shopping_cart
                            </span>
                            <span>Carrello</span>
                        </a>
                    </div>
                </nav>
            </div>
            
        </section>

        <header class = "header-margini">
            <a href="../index.php"><img src="https://shop.pistacchissimo.it/img/my-shop-logo-1604849342.jpg"></a>
            <nav>
                <a href="#">pasqua</a>
                <a href="#">colombe</a>
                <a href="#">uova di pasqua</a>
                <a href="#">vasetti e secchi</a>
                <a href="#">senza glutine</a>
                <a href="../index.php">blog</a>         
            </nav>
            <form name = 'catalogo'>
                <input type="text" name="catalogoProdotto" placeholder = "Cerca nel catalogo">
                <input type="submit" class ="hidden">
            </form>
            
        </header>

        

        <!-- ------------------ -->

        
        
        <section id="modal-view-prodotti" class = "hidden" tabindex="0">
            <!-- tabindex = 0 mi rende focusable la section così da poterla chiudere con esc-->
            <div id="modal-container-esterno">
                <span class="fa fa-window-close"></span>
                <div id="modal-container-interno">
                    <img src="">
                    <div>
                        <span class = "nome-prodotto"><strong></strong></span>
                        <strong class = "in_saldo"></strong> 
                        <strong class= "prezzo"></strong>

                        <span id = "descrizione-prodotti"></span>

                        <br>

                        <span id = "quantita-disponibile">
                            
                        </span>

                        <br>

                        <form name ="aggiungi-al-carrello">
                            <input type="text" name = "quantita">

                            <?php
                                if(isset($_SESSION['email'])){
                                    echo "<button type = \"submit\" id = \"submitcarrello\">";
                                    echo "<a>";
                                }
                                else{
                                    echo "<a href = \"hw1-login-signup.php\">";                                    
                                }
                                echo "<span class=\"material-symbols-outlined\">";
                                echo "shopping_cart </span>";
                                echo "<span>Aggiungi al carrello</span>";
                                echo "</a>";
                            ?>
                        </form>

                        <div id="errore-quantita">
                            Inserire una quantità valida
                        </div>
                        
                    </div>
                </div>
            </div>

        </section>


        <section id="main-section">
            <div id = "risposte-catalogo">
                <form name="catalogo">
                    <input type="text" name="catalogoProdotto" placeholder = "Cerca nel catalogo...">
                    <input type="submit" class ="hidden">
                </form>
                <h2 class = "hidden">Prodotti nel catalogo <span class = "fa fa-window-close"></span></h2>
                <div class = "foto-main">
                    
                </div>

            </div>
    
            <h2>
                prodotti popolari
            </h2>
            

            <div class="foto-main" id="popolari">
            </div>


            <div id="descrizione">
                <h2>il vero gusto del pistacchio</h2>
                <p><em>
                    <strong>
                        Ciao sono Daniele Famà benvenuto in questo sito rubato
                    </strong>
                </em></p>

                <p><em>
                    Ho copiato <strong>Pistacchissimo</strong> perchè è un sito <strong>completo</strong>
                </em></p>

                <p><em>
                    Se ancora non mi conosci ti invito a seguirmi su <a>Instagram</a> e su <a>Facebook</a>, per scoprire ogni giorno nuovi prodotti e tantissimi segreti sul pistacchio.
                </em></p>

                <p><em>
                    Su questo sito puoi <strong>trovare prodotti al pistacchio straordinari</strong>, dedicati a coloro che amano davvero il pistacchio e ne ricercano il <strong>gusto intenso e autentico</strong>.
                </em></p>

                <p><em>
                    Dietro ad ogni prodotto c'è uno studio lungo e attento, fatto con <strong>autentica passione</strong>, per farti vivere un'esperienza diversa da qualsiasi altro prodotto sul mercato.
                </em></p>

                <a href="../index.php">SCOPRI DI PIÙ SU PISTACCHISSIMO.IT</a>

            </div>


            
            <h2>
                in saldo
            </h2>

            <div id="in-saldo" class="foto-main" >

            </div>

            <a href = "#">
                Tutti i prodotti in vendita <span class ="arrow right"></span>
            </a>
        </section>


        <!-- ------------- -->
        <footer>
            <div>
                <div id="newsletter">
                    <div>
                        <h4>Ricevi le nostre novità e le offerte speciali</h4>
                    </div>
                    <form>                    
                        <div>
                            <input type="text" placeholder="Il tuo indirizzo email">
                            <input type="submit" value="ISCRIVITI" id="iscriviti">
                        </div>
                        <p>Puoi annullare l'iscrizione in ogni momento</p>
                        <div id="checkboxNews">
                            <input type="checkbox">
                            <span>Accetto le condizioni generali e la politica di riservatezza.
                                Sono consapevole che posso cancellare la mia iscrizione in qualsiasi momento contattando il servizio clienti.</span>
                        </div>
                    </form>
                </div>
                <div id = loghi-social>
                    <a href="#" class="fa fa-facebook"></a>
                    <a href="#" class="fa fa-youtube"></a>
                    <a href="#" class="fa fa-instagram"></a>
                </div>
            </div>

            <div id="recensioni">
                <a>
                    Scrivi una recensione su di noi
                </a>
            </div>

            <div id="link-footer">
                <div>
                    <h4>informazioni</h4>
                    <span><a>Spedizioni</a></span>
                    <span><a>Note legali</a></span>
                    <span><a>Termini e condizioni d'uso</a></span>
                    <span><a>Chi siamo</a></span>
                    <span><a>Pagamento sicuro</a></span>
                    <span><a>Contattaci</a></span>
                    <span><a>Mappa del sito</a></span>
                    <span><a>Il mio account</a></span>
                    <span><a>Privacy Policy</a></span>
                    <span><a>Cookie Policy</a></span>
                </div>

                
                <div>
                    <h4>il tuo account</h4>
                    <span><a>Informazioni personali</a></span>
                    <span><a>Ordini</a></span>
                    <span><a>Note di credito</a></span>
                    <span><a>Indirizzi</a></span>
                    <span><a>Buoni</a></span>
                </div>

                <div>
                    <h4>informazioni negozio</h4>
                    <span><a>Pistacchissimo</a></span>
                    <span><a>Marco Rinaldi</a></span>
                    <span><a>P.IVA: .</a></span>
                    <span><a>Whatsapp: -</a></span>
                    <span><a>Inviaci un'e-mail</a></span>
                    <a>info@pistacchissimo.it</a>
                </div>
                
            </div>
        </footer>
        

        <section id="fisso"> 
            <span>
                Consegna gratuita a <strong>Catania</strong> - 
                Spedizioni in <strong>italia e Europa</strong>
            </span>
        </section>
    </body>
</html>