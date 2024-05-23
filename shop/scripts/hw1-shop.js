function toggleMenuNav(){
    const elem = document.querySelector('#menu nav');
    if(elem.classList.contains('hidden'))
        elem.classList.remove('hidden');
    else
        elem.classList.add('hidden');
}

function aggiungiFoto(album, image, priceSpan, nome) {
    let div;

    div = album.appendChild(document.createElement('div'));
    
    const div1 = div.appendChild(document.createElement('div'));
    const div2 = div.appendChild(document.createElement('div'));

    const a_cuore = document.createElement('a');
    a_cuore.classList.add('fa','fa-heart');
    a_cuore.dataset.id = image.dataset.id;
    a_cuore.addEventListener('click', modificaPreferenza); 

    // console.log(a_cuore.dataset.id);

    // console.log("aggiungi foto " + loggato);
    if(loggato == "true"){
        // console.log('entrato vero');
        a_cuore.setAttribute('href', '#');
    }else{
        a_cuore.setAttribute('href', 'hw1-login-signup.php');
    }
    
    div1.appendChild(a_cuore);
        

    div1.classList.add('relative');

    div1.appendChild(image);
    
    const nomeProdotto = document.createElement('span');
    nomeProdotto.textContent = nome;

    nomeProdotto.classList.add('nome-ridotto', 'nome-prodotto');    

    div2.appendChild(nomeProdotto);

    // const prezzo = document.createElement('strong');
    // prezzo.textContent = price + " $"; 
    const span = priceSpan;  
    
    div2.appendChild(span);
}

function modificaPreferenza(event){

    const cuore = event.currentTarget;
    let daEliminare = false;

    if(cuore.getAttribute('href') == "#"){

        event.preventDefault();
        // console.log("entrato");  

        if(cuore.classList.contains('rosso')){
            cuore.classList.remove('rosso');
            daEliminare = "true";
        }
        else{
            cuore.classList.add('rosso');
            daEliminare = "false";
        }

        // console.log("id= " + cuore.dataset.id + " azione = " + daEliminare);
        
        fetch("informazioniphp/hw1-modificaPreferiti.php?id_prodotto=" + encodeURIComponent(cuore.dataset.id) +"&azione="+ encodeURIComponent(daEliminare)).then(onResponseMod);
    }
}

function onResponseMod(){
    aggiornaCuori();
}

function aggiornaCuori(){
    if(loggato){
        fetch("informazioniphp/hw1-modificaPreferiti.php?ottieni_preferiti=true").then(onResponseAggiornaCuori).then(onJsonAggiornaCuori);
    }
}

function onResponseAggiornaCuori(response){
     return response.json();
}

function onJsonAggiornaCuori(json){
    // console.log(json.id_array);

    const cuori = document.querySelectorAll('a.fa-heart');
    
    // console.log(cuori);

    for(cuore of cuori){
        // console.log(cuore);
        if(json.id_array.includes(cuore.dataset.id)){
            cuore.classList.add('rosso');
        } else {
            cuore.classList.remove('rosso');
        }

    }

}

function onJsonLog(json){
    // console.log(json);
    loggato = json.loggato;
    // console.log(loggato);
}

function onJsonProdotti(json){
  // console.log(json);
  
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

        spanIMG.dataset.id = prodotto.id; 
        spanIMG.appendChild(image);

        scontato = false;
        spanPrezzoIntero.textContent = prodotto.prezzo + " $"; 

        const isInSaldo = json.prodotti_in_saldo.find(prod => prod.id === prodotto.id);
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

            const cloneSpanIMG = spanIMG.cloneNode(true);
            cloneSpanIMG.childNodes[0].addEventListener('click', apriModale);

            aggiungiFoto(album1,spanIMG, spanPrezzo, prodotto.nome);
            aggiungiFoto(album2,cloneSpanIMG, spanPrezzo.cloneNode(true), prodotto.nome);
            // console.log(spanPrezzoIntero)
            continue;
        } 

        if(!scontato){
            spanPrezzo.textContent = spanPrezzoIntero.textContent;
            aggiungiFoto(album1,spanIMG, spanPrezzo, prodotto.nome);
        }

        // console.log("aggiunto" + prodotto + prodotto.prezzo);
    }

    aggiornaCuori();

}

function onResponse(response) {
    return response.json();
}

function previeniRicarica(event){
    event.preventDefault();
}

function apriModale(event){

    const evento = event.currentTarget;
    const data = evento.dataset;
    const img = document.querySelector('#modal-container-interno img');

    img.src = evento.src;
    img.dataset.id = data.id;
    // console.log(img.dataset.id);
    document.querySelector('#modal-container-interno .prezzo').textContent = data.prezzoScontato;
    document.querySelector('#modal-container-interno .in_saldo').textContent = data.prezzo;
    document.querySelector('#modal-container-interno .nome-prodotto').textContent = data.nome;
    document.getElementById('descrizione-prodotti').textContent = data.descrizione;
    document.getElementById('quantita-disponibile').textContent = "Quantità disponibile: " + data.quantita; 
    document.getElementById('errore-quantita').textContent = "";
    modalViewProdotti.style.top = window.pageYOffset + 'px';
    document.body.classList.add('no-scroll');
    modalViewProdotti.classList.remove('hidden');
    modalViewProdotti.focus();
} 


function chiudiModaleProdotti(event) {
    if(event.type === 'keydown' && event.key !== "Escape"){
        return;
    }
    
    document.body.classList.remove('no-scroll');
    modalViewProdotti.classList.add('hidden');
    
}


function isIntero(variabile) {
    return !isNaN(parseInt(variabile)) && parseInt(variabile) == variabile;
}


function onJsonCarrello(json){  
    // console.log(json[0]);
    if(json[0] == "true"){
        location.reload();
    }else{
        const span = document.getElementById('errore-quantita');
        span.textContent = "Inserire una quantità valida";
    }
}


function aggiungiAlCarrello(event){
    const form = event.currentTarget;
    event.preventDefault();
    
    const id_prodotto = document.querySelector('#modal-container-interno img').dataset.id;

    const quantita_selezionata = form.quantita.value;

    if(loggato == "true"){
        if(!isIntero(quantita_selezionata)){
            alert("Inserire un numero intero");
            return;
        } else if(quantita_selezionata <= 0){
            alert("Inserire una quantità positiva");
            return;
        }
        else{
            fetch('informazioniphp/hw1-checkCarrello.php?id='+id_prodotto+"&quantita="+quantita_selezionata).then(onResponse).then(onJsonCarrello);
        }
    }
}

let loggato = false;
fetch("informazioniphp/hw1-checkLogin.php").then(onResponse).then(onJsonLog);

const album1 = document.querySelector('#popolari');
const album2 = document.querySelector('#in-saldo');

fetch("informazioniphp/hw1_db_elenco_prodotti.php").then(onResponse).then(onJsonProdotti);

const modalViewProdotti = document.querySelector('#modal-view-prodotti');
modalViewProdotti.addEventListener('keydown', chiudiModaleProdotti)

const tastoChiudiModale = document.querySelector('#modal-container-esterno span');
tastoChiudiModale.addEventListener('click', chiudiModaleProdotti);

const bloccoMenuNavigazione = document.querySelector('div#menu a');
bloccoMenuNavigazione.addEventListener('click', toggleMenuNav);

const link = document.querySelectorAll('a');
for(let a of link){
    if(a.getAttribute('href') === "#")
        a.addEventListener('click', previeniRicarica);
}

let disponibile;
const formcarrello = document.forms['aggiungi-al-carrello'];
formcarrello.addEventListener('submit', aggiungiAlCarrello);

