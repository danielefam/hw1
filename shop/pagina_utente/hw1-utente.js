function aggiungiFotoPerUtente(album, image, nome) {
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
    a_cuore.setAttribute('href', '#');
    
    div1.appendChild(a_cuore);

    if(album == album3){
        const elimina = document.createElement('span');
        elimina.classList.add('fa', 'fa-window-close');
        
        // console.log(elimina.dataset.id);
        // if(album == album1){
        //     elimina.dataset.id = image.dataset.id;
        //     elimina.addEventListener('click', eliminaDalCarrello);
        // }
        // else{
        elimina.dataset.id = image.dataset.id_tran;
        elimina.addEventListener('click', nascondiTransazione);
        // }
        div1.appendChild(elimina);
    }      
  
    div1.classList.add('relative');
  
    div1.appendChild(image);
    
    const nomeProdotto = document.createElement('span');
    nomeProdotto.textContent = nome;
  
    nomeProdotto.classList.add('nome-ridotto', 'nome-prodotto');    
  
    div2.appendChild(nomeProdotto);

    if(album == album3){
        const spanQuant = document.createElement('span');
        
        // spanQuant.textContent = (album == album1 ? "Quantita = ": "Acquistati: ") + image.dataset.quantita;
        spanQuant.textContent = "Acquistati = " + image.dataset.quantita;
        div2.appendChild(spanQuant);
    }
    
}

function separaJson(album, prodotti, tipo){
    // console.log(prodotti);
    if(prodotti.length === 0){
        const span = document.createElement('span');
        if(tipo == "pref") {
            span.textContent += "NESSUN PRODOTTO NEI PREFERITI";
        } else {
            span.textContent += "NESSUNA TRANSAZIONE PASSATA VISIBILE";
        }
        span.classList.add('errore');
        album.appendChild(span);
        return;
    }

    for(prodotto of prodotti){

        const spanIMG = document.createElement('span');
        const image = document.createElement('img');
        

        image.src = prodotto.img_src;
        image.dataset.id = prodotto.id;
        image.dataset.nome = prodotto.nome;
        image.dataset.descrizione = prodotto.descrizione;
        image.dataset.prezzo = prodotto.prezzo;    
        spanIMG.appendChild(image);
        spanIMG.dataset.id = prodotto.id;
        
        if(tipo == "tran"){
            spanIMG.dataset.quantita = prodotto.quantita_acquistata;
            spanIMG.dataset.id_tran = prodotto.id_transazione;                    
        }

        image.addEventListener('click', apriModale);

        // console.log(prodotto.quantita);
        if(prodotto.quantita === "0" && tipo == "pref"){
            // console.log("entrato");
            const soldout = document.createElement('span');
            soldout.textContent = "non disponibile";
            soldout.classList.add('soldout');
            spanIMG.appendChild(soldout);
        }

        aggiungiFotoPerUtente(album, spanIMG, prodotto.nome);        

    }
}

function creaCarrello(divcarrello, carrello){
    const h2 = document.createElement('h2');
    h2.textContent = "carrello";
    divcarrello.appendChild(h2);

    if(carrello.length === 0){
        const span = document.createElement('span');
        span.textContent += "NESSUN PRODOTTO NEL CARRELLO";
        span.classList.add('errore');
        divcarrello.appendChild(span);
        return;
    }

    for(prodotto of carrello){

        const container = document.createElement('div');
        container.classList.add('container-carrello');
        divcarrello.appendChild(container);

        const img = document.createElement('img');
        const spanImg = document.createElement('span')
        img.src = prodotto.img_src;
        spanImg.appendChild(img)
        container.appendChild(spanImg);

        const nomePrezzo = document.createElement('div');

        container.appendChild(nomePrezzo);
        
        const nome = document.createElement('strong');
        const spanNome = document.createElement('span');
        spanNome.appendChild(nome);
        nome.textContent = prodotto.nome;

        nomePrezzo.appendChild(spanNome);

        const divPrezzo = document.createElement('div');
        nomePrezzo.appendChild(divPrezzo);

        const prezzo = document.createElement('strong');
        prezzo.classList.add('prezzo');
        prezzo.textContent = prodotto.prezzo

        divPrezzo.appendChild(prezzo);

        if(prodotto.prezzo_scontato != '-1'){
            prezzo.classList.add('in_saldo');
            const scontato = document.createElement('strong');
            scontato.classList.add('prezzo');
            scontato.textContent = prodotto.prezzo_scontato;
            divPrezzo.appendChild(scontato);
        }

        const quantita = document.createElement('strong');
        quantita.textContent = "Quantità = " + prodotto.quantita;
        container.appendChild(quantita);

        const a = document.createElement('a');

        const spazzatura = document.createElement('span');
        spazzatura.classList.add('fa','fa-trash');
        a.appendChild(spazzatura);
        const text = document.createTextNode(' Elimina');
        a.appendChild(text);
        a.dataset.id = prodotto.id;
        a.addEventListener('click', eliminaDalCarrello);
        container.appendChild(a);

        const a_cuore = document.createElement('a');
        a_cuore.classList.add('fa','fa-heart');
        a_cuore.dataset.id = prodotto.id;
        a_cuore.addEventListener('click', modificaPreferenza); 
        a_cuore.setAttribute('href', '#');
        container.appendChild(a_cuore);
    }

}

function onJsonPrefCar(json){
    console.log(json);

    if(json.saldo != "0"){
        document.getElementById('importo').textContent = json.saldo + "\u20AC";
        document.forms['acquista'].classList.remove('hidden');
    }else{
        document.getElementById('importo').textContent = "";
        document.forms['acquista'].classList.add('hidden');
    }

    album1.innerHTML = "";
    album2.innerHTML = "";
    album3.innerHTML = "";

    creaCarrello(album1, json.carrello);
    separaJson(album2, json.preferiti, "pref");
    separaJson(album3, json.transazioni, "tran");

    aggiornaCuori();
}

function onResponsePrefCar(response){
    return response.json();
}

function modificaPreferenza(event){
  
    const cuore = event.currentTarget;
    let daEliminare = false;
  
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
    
    fetch("../informazioniphp/hw1-modificaPreferiti.php?id_prodotto=" + encodeURIComponent(cuore.dataset.id) +"&azione="+ encodeURIComponent(daEliminare)).then(onResponseMod);
    fetch('hw1-preferiti-carrello.php?preferiti-carrello=true').then(onResponsePrefCar).then(onJsonPrefCar);
}

function onResponseElimina(){
    fetch('hw1-preferiti-carrello.php?preferiti-carrello=true').then(onResponsePrefCar).then(onJsonPrefCar);
}

function eliminaDalCarrello(event){
    event.preventDefault();
    const id_prodotto = event.currentTarget.dataset.id;
    fetch('../informazioniphp/hw1-checkCarrello.php?id='+encodeURIComponent(id_prodotto)+"&elimina-carrello=true").then(onResponseElimina);
}

function nascondiTransazione(event){
    event.preventDefault();
    const id_transazione = event.currentTarget.dataset.id;
    fetch('../informazioniphp/hw1-checkCarrello.php?id-tran='+encodeURIComponent(id_transazione)+"&nascondi-transazione=true").then(onResponseElimina);
}


function onResponseMod(){
    aggiornaCuori();
}

function onJsonAggiornaCuori(json){
    // console.log(json.id_array);

    const cuori = document.querySelectorAll('a.fa-heart');
    
    // console.log(cuori);

    for(cuore of cuori){
        // console.log(cuore);
        if(json.id_array.includes(cuore.dataset.id)){
        cuore.classList.add('rosso');
        }else{
        cuore.classList.remove('rosso');
        }

    }

}
  
function aggiornaCuori(){    
    fetch("../informazioniphp/hw1-modificaPreferiti.php?ottieni_preferiti=true").then(onResponse).then(onJsonAggiornaCuori);    
}

function onResponse(response){
    return response.json();
}

function chiudiModaleProdotti(event) {
    if(event.type === 'keydown' && event.key !== "Escape"){
        return;
    }
    
    document.body.classList.remove('no-scroll');
    modalViewProdotti.classList.add('hidden');    
}

function apriModale(event){

    const evento = event.currentTarget;
    const data = evento.dataset;
    const img = document.querySelector('#modal-container-interno img');
  
    img.src = evento.src;
    img.dataset.id = data.id;
    // console.log(img.dataset.id);
    document.querySelector('#modal-container-interno .prezzo').textContent = data.prezzo;
    document.querySelector('#modal-container-interno .nome-prodotto').textContent = data.nome;
    document.getElementById('descrizione-prodotti').textContent = data.descrizione;
    
    modalViewProdotti.style.top = window.pageYOffset + 'px';
    document.body.classList.add('no-scroll');
    modalViewProdotti.classList.remove('hidden');
    modalViewProdotti.focus();
} 


const album1 = document.querySelector('div#prodotti-nel-carrello');
const album2 = document.querySelector('div#prodotti-preferiti');
const album3 = document.querySelector('div#transazioni-passate');
fetch('hw1-preferiti-carrello.php?preferiti-carrello=true').then(onResponsePrefCar).then(onJsonPrefCar);

const modalViewProdotti = document.querySelector('#modal-view-prodotti');
modalViewProdotti.addEventListener('keydown', chiudiModaleProdotti)

const tastoChiudiModale = document.querySelector('#modal-container-esterno span');
tastoChiudiModale.addEventListener('click', chiudiModaleProdotti);