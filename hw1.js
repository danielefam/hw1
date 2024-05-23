function previeniRicarica(event){
    event.preventDefault();
}

function apriMenuRec(event){
    const menu = document.querySelector('#blocco-recensioni nav');
    event.currentTarget.classList.add('hover');
    menu.classList.remove('hidden');
}

function chiudiMenuRec(){
    const menu = document.querySelector('#blocco-recensioni nav');
    const tasto = document.querySelector('#tasto-recensioni');
    tasto.classList.remove('hover');
    menu.classList.add('hidden');
}

function toggleMenuNav(){
    const elem = document.querySelector('#menu nav');
    if(elem.classList.contains('hidden'))
        elem.classList.remove('hidden');
    else
        elem.classList.add('hidden');
}

function mostranascondiEpisodi() {
    const ep = document.querySelector('div#episodi2')    
    const bottone = document.querySelector('#bottone-episodi span') 

    if(ep.classList.contains('hidden')){
        ep.classList.remove('hidden');
        bottone.textContent = 'Nascondi i video Pistacchissimo più recenti';
    }
    else{
        ep.classList.add('hidden');
        bottone.textContent = 'Mostra i video Pistacchissimo più recenti';
    }

}

function cambiaFooter() {
    const foot = document.querySelector('footer');
    const stileCorrente = document.querySelector('footer em')
    switch (foot.dataset.index) {
        case "0":
            foot.dataset.index = "1";
            stileCorrente.textContent = '(2 di 3)';
            break;
        case "1":
            foot.dataset.index = "2";
            foot.classList.add('originale')
            stileCorrente.textContent = '(3 di 3)';
            break;
        case "2":
            foot.dataset.index = "0";
            foot.classList.remove('originale')
            stileCorrente.textContent = '(1 di 3)';
            break;
    }
}

function onResponse(response) {
    return response.json();
}

// -------------- API TWITCH ----------------------

function onJsonTwitch(json){
    // console.log(json);
    const tasto = document.querySelector('#prima-riga-esterno input');
    tasto.blur(); 

    modalView.innerHTML= ''; 

    document.body.classList.add('no-scroll');
    modalView.style.top = window.pageYOffset + 'px';
    modalView.classList.remove('hidden');

    if(json.data.length === 0){  
        const scritta = document.createElement('span');
        scritta.textContent = "Il gioco non \u00E8 presente in archivio";
        modalView.append(scritta);
        return;
    }
    
    let res = json.data[0];

    const descrizione = document.createElement('p');
    descrizione.textContent = res.name;

    image_url='https://static-cdn.jtvnw.net/ttv-boxart/'+ res.id + '.jpg';
    // image_url= res.box_art_url;
    const image = document.createElement('img');
    image.src=image_url;

    const giochi = document.createElement('div');

    giochi.appendChild(descrizione);
    giochi.appendChild(image);

    modalView.appendChild(giochi);
} 

function chiudModale(event) {
    if(event.key === "Escape"){
        document.body.classList.remove('no-scroll');
        modalView.classList.add('hidden');
    }
    
}

function search(event){
    const formDaInviare = new FormData(event.currentTarget);
    // console.log(formDaInviare);
    const dati_form = {method: 'post', body: formDaInviare};

    fetch("apimie/twitchAPI.php", dati_form).then(onResponse).then(onJsonTwitch);

    event.preventDefault();    
}

// -------------- API YOUTUBE ----------------------

function aggiungiFoto(padre, link, urlIMG, title){
    const div = document.createElement('div');
    const h3 = document.createElement('h3');
    const img = document.createElement('img');
    const a = document.createElement('a');

    a.href = link;

    h3.textContent = title;
    img.src = urlIMG;
    padre.appendChild(div);
    div.appendChild(h3);
    div.appendChild(a);
    a.appendChild(img);

}

function onJsonYoutube(json){
    console.log(json)
    const padre = document.querySelector('#episodi2'); 
    let urlIMG;
    let titolo;
    let link;

    if(json.items.length === 0){
        const messErrore = document.createElement('span');
        messErrore.textContent = "Questo canale non ha video";
        padre.appendChild(messErrore);
        return;
    }

    for(let i = 0; i < json.items.length; i++){
        titolo = json.items[i].snippet.title;
        link = 'https://www.youtube.com/watch?v='+ json.items[i].snippet.resourceId.videoId
        urlIMG = json.items[i].snippet.thumbnails.medium.url;
        aggiungiFoto(padre, link,urlIMG , titolo)
    }
}

function cercaVideoRecenti() {    
    fetch("apimie/youtubeAPI.php").then(onResponse).then(onJsonYoutube);
}

//  variabili generiche
const bottoneCambioFooter = document.querySelector('footer button');
bottoneCambioFooter.addEventListener('click', cambiaFooter);

const bottoneAltriEpisodi = document.querySelector('#bottone-episodi button')
bottoneAltriEpisodi.addEventListener('click', mostranascondiEpisodi);

const menuRecensioni = document.querySelector('#tasto-recensioni');
menuRecensioni.addEventListener('mouseover', apriMenuRec);

const bloccoMenuRecensioni = document.querySelector('#blocco-recensioni span')
bloccoMenuRecensioni.addEventListener('mouseleave', chiudiMenuRec);


const bloccoMenuNavigazione = document.querySelector('div#menu a');
bloccoMenuNavigazione.addEventListener('click', toggleMenuNav);

const link = document.querySelectorAll('a');
for(let a of link){
  if(a.getAttribute('href') === "#")
    a.addEventListener('click', previeniRicarica);
}

// variabili per API twitch

const form = document.querySelector('form');
form.addEventListener('submit', search);

const modalView = document.querySelector('#modal-view');
document.addEventListener('keydown', chiudModale);

// API youtube

cercaVideoRecenti()