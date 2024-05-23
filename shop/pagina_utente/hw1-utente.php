<?php
    session_start();
    
    if (!isset($_SESSION['email'])) {        
        header('Location: ../hw1-login-signup.php');
        exit();
    }

    if(isset($_GET['acquista-bottone'])){
        $conn = mysqli_connect("localhost", "root", "", "pistacchissimo");
        $email = mysqli_real_escape_string($conn, $_SESSION['email']);
        
        $query = "call acquista('".$email."')";
        mysqli_query($conn, $query);
        
        mysqli_close($conn);
    }

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

        <link rel = "stylesheet" type="text/css" href = "../style/hw1-shop.css">
        <link rel = "stylesheet" type="text/css" href = "hw1-utente.css">
        
        <script src="hw1-utente.js" defer></script>
    </head>
    <body>
        
        <section id="prima-riga-esterno">
            <div id ="prima-riga-interno" class = "header-margini">
                
                <div id = "menu">
                    <a class = "fa fa-bars"></a>
                    <a href="../../index.php"><img src="https://shop.pistacchissimo.it/img/my-shop-logo-1604849342.jpg"></a>
                    <nav class = "hidden"> 
                        <span><a href="#"><span>shop</span></a></span>
                        <span><a href="../../index.php"><span>blog</span></a></span> 
                        <span><a href="../../index.php">Contattaci</a></span>                               
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
                                    echo "href = \"../hw1-logout.php\"";
                                }
                            ?>
                        >
                            Esci
                        </a>
                        <a href = "#"> 
                            
                            <span class="material-symbols-outlined">
                                person
                            </span>
                            <span>
                                <?php echo "Ciao ". $_SESSION['nome'];?>
                            </span>
                        </a>
                    </div>

                    <div id = "carrello">
                        <a href= "#">
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
            <a href="../../index.php"><img src="https://shop.pistacchissimo.it/img/my-shop-logo-1604849342.jpg"></a>
            <nav>
                <a href="../hw1-shop.php">shop</a>
                <a href="#">colombe</a>
                <a href="#">uova di pasqua</a>
                <a href="#">vasetti e secchi</a>
                <a href="#">senza glutine</a>
                <a href="../../index.php">blog</a>         
            </nav>
            
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
                        
                    </div>
                </div>
            </div>

        </section>

        <section id="main-section">
            <div class = "header-margini">
                <a href = "../hw1-shop.php">
                    <span class ="arrow left"></span> Torna allo shop
                </a>
            </div>
            
    
            

            <div id="prodotti-nel-carrello">

            </div>

            
            <form name ="acquista">
                <button type = "submit" name="acquista-bottone">
                <a id ="tasto-acquista">
                    <span>Acquista</span>
                    <span id = importo></span> 
                </a>
                </button>                
            </form>            

            
            <div class = "container-foto">
                <h2>
                    prodotti preferiti
                </h2>

                <div id="prodotti-preferiti" class="foto-main" >

                </div>
            </div>
            
            
            <div class = "container-foto">
                <h2>
                    transazioni passate
                </h2>

                <div class="foto-main" id="transazioni-passate">

                </div>
            </div>
            
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