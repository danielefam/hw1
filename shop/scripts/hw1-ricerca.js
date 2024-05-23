function onJsonCatalogo(json){
    
    document.querySelector('#risposte-catalogo h2').classList.remove('hidden');

    const album = document.querySelector('#risposte-catalogo .foto-main');
    album.innerHTML = "";

    if(json.prodotti.length === 0){
        const span = document.createElement('span');
        span.textContent = "NESSUN ELEMENTO CORRISPONDENTE";
        span.classList.add('errore');
        album.appendChild(span);
        return;
    }


    let scontato = false; 

    for(prodotto of json.prodotti){
        
        const spanPrezzo = document.createElement('strong');
        spanPrezzo.classList.add('prezzo'); 
        const spanPrezzoIntero = document.createElement('strong'); 
        spanPrezzoIntero.classList.add('in_saldo'); 
        const spanPrezzoInSaldo = document.createElement('strong');

        const spanIMG = document.createElement('span');
        const image = document.createElement('img');
        image.src = prodotto.img_src;
        image.dataset.id = prodotto.id;
        image.dataset.nome = prodotto.nome;
        image.dataset.descrizione = prodotto.descrizione;
        image.dataset.prezzo = prodotto.prezzo;    
        image.dataset.quantita = prodotto.quantita;
        spanIMG.appendChild(image);
        spanIMG.dataset.id = prodotto.id;

        scontato = false;
        spanPrezzoIntero.textContent = prodotto.prezzo + " $"; 

        const isInSaldo = json.prodotti_in_saldo.find(obj => obj.id === prodotto.id);
        image.dataset.prezzoScontato = isInSaldo ? isInSaldo.prezzo_scontato : "";
        image.addEventListener('click', apriModale);

        // console.log(prodotto.quantita);
        if(prodotto.quantita === "0"){
            // console.log("entrato");
            const soldout = document.createElement('span');
            soldout.textContent = "non disponibile";
            soldout.classList.add('soldout');
            spanIMG.appendChild(soldout);
        }


        if(isInSaldo){
            // console.log(inSaldo.id + " " + prodotto.id)
            scontato = true;
            
            spanPrezzoInSaldo.textContent = "        " + isInSaldo.prezzo_scontato + " $";

            spanPrezzo.appendChild(spanPrezzoIntero);
            spanPrezzo.appendChild(spanPrezzoInSaldo);

            aggiungiFoto(album, spanIMG, spanPrezzo, prodotto.nome);
            // console.log(spanPrezzoIntero)
            continue;
        } 

        if(!scontato){
            spanPrezzo.textContent = spanPrezzoIntero.textContent;
            aggiungiFoto(album,spanIMG, spanPrezzo, prodotto.nome);
        }

    }
    aggiornaCuori();
}

function onResponseCatalogo(response){
    return response.json();
}

function leggiElemento(event){  
    // console.log(event.currentTarget);
    const formDaInviare = new FormData(event.currentTarget);
    // console.log(formDaInviare);
    const dati_form = {method: 'post', body: formDaInviare};
    
    fetch("informazioniphp/hw1-ricerca.php", dati_form).then(onResponseCatalogo).then(onJsonCatalogo);

    event.preventDefault();
}

function nascondiProdotti(){
    document.querySelector('#risposte-catalogo h2').classList.add('hidden');
    const album = document.querySelector('#risposte-catalogo .foto-main');
    album.innerHTML = "";
}


const formCatalogo = document.querySelectorAll('form');
for(f of formCatalogo){
    if(f.name == "catalogo"){
        f.addEventListener('submit', leggiElemento);
    }
}

const tastoProdottiNelCatalogo = document.querySelector('h2 .fa-window-close');
tastoProdottiNelCatalogo.addEventListener('click', nascondiProdotti);