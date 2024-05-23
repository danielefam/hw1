<!-- accesso -->
<?php
    // Avvia la sessione
    session_start();
    // Verifica l'accesso
    if(isset($_SESSION["email"]))
    {
        // Vai alla home
        header("Location: hw1-shop.php");
        exit;
    }
    // Verifica l'esistenza di dati POST
    if(isset($_POST["email"]) && isset($_POST["password"]))
    {
        // Connetti al database
        $conn = mysqli_connect("localhost", "root", "", "pistacchissimo");
        // Escape dell'input
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);
        // Cerca utenti con quelle credenziali
        $query = "SELECT * FROM user WHERE
                 email = '".$email."'";
        $res = mysqli_query($conn, $query);
        

        if(mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);

            if(password_verify($_POST['password'], $row['password_utente'])){
                // Imposta la variabile di sessione
                $_SESSION["email"] = $_POST["email"];
                $_SESSION["nome"] = $row["nome"];

                header("Location: hw1-shop.php");
                exit;
            }
            else{
                $errore = true;
            }
        }
        else
        {
            // Flag di errore
            $errore = true;
        }
    }
?>


<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pistacchissimo-login</title>

        <!-- font manrope per l'header -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        
        <script src="scripts/hw1-apiCheckEmail.js" defer></script>
        <script src = "scripts/hw1-login-signup.js" defer></script>

        <link rel="icon"href="https://shop.pistacchissimo.it/img/favicon.ico?1604966480">
        <link rel = "stylesheet" type="text/css" href = "style/hw1-shop.css">
        <link rel = "stylesheet" type="text/css" href = "style/hw1-login-signup.css">
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
                        <a> 
                            <span class="material-symbols-outlined">
                                person
                            </span>
                            <span>Accedi</span>
                        </a>
                    </div>

                    <div id = "carrello">
                        <a>
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

        <!-- --------------------- -->



        <main id = "login">
            <div class="header-margini">
                <span><a href="hw1-shop.php">Home</a> / Accedi al tuo account</span>

                <h1>Accedi al tuo account</h1>

                <div class = "esterno-main">
                    <form name="login" method="post">
                        <label>E-mail <input type="text" name = "email"></label>
                        <label>Password <input type="password" name = "password"></label>
                        <a>Hai dimenticato la password?</a>
                        <input type="submit" value="ACCEDI" class="bottone">
                    </form>
                    <?php                    
                        if(isset($errore))
                        {
                            echo "<p class='errore'>";
                            echo "Credenziali non valide.";
                            echo "</p>";
                        }
                    ?>
                    <a href="#" class = "login-signup">Non hai ancora un account? Creane ora qui uno</a>
                </div>
            </div>
        </main>

        <main id = "signup" class = "hidden">
            <div class = "header-margini">
                <span><a href="hw1-shop.php">Home</a> / Crea un account</span>

                <h1>Crea un account</h1>

                <div class = "esterno-main">
                    <a href="#" id = "registrati" class = "login-signup">Hai già un account? Accedi</a>
                    <form name="signup" method="post">
                        <label>Nome <input type="text" name = "nome_reg"></label>
                        <label>Cognome <input type="text" name = "cognome_reg"></label>
                        <label>E-mail <input type="text" name = "email_reg"></label>
                        <label>Password <input type="password" name = "password_reg"></label>

                        <a>Hai dimenticato la password?</a>
                        <input type="submit" value="SALVA" class="bottone">
                    </form>

                    <div id="blocco-errori">
                        
                    </div>
                    
                </div>
            </div>
        </main>





        <!-- -------------------- -->
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