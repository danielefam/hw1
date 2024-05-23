function onJson(json){
    console.log('json ricevuto inizio verifica mail');
    console.log(json);
    if(json.disposable){
        const doc = document.querySelector('#newsletter input');
        doc.classList.add('rosso');
        doc.value = "Le mail temporanee non sono consentite!";
    }
}
  
function onResponse(response) {
    return response.json();
}

function controllaMail(event) {

    event.preventDefault();

    const input_email = document.querySelector('#newsletter input');
    // ho preso il primo #newsletter input indicante l'input di tipo testo
    const emailDaVerificare = encodeURIComponent(input_email.value);
    // console.log('controllo: ' + emailDaVerificare);

    rest_url = 'https://api.mailcheck.ai/email/' + emailDaVerificare;
    // console.log('URL: ' + rest_url);
    
    fetch(rest_url).then(onResponse).then(onJson);
}

function ripristina(event) {
    const input = event.currentTarget;
    if(input.value === "Le mail temporanee non sono consentite!" || input.classList.contains('rosso')){
        input.classList.remove('rosso');
        input.value = "";
    }
}

const form = document.querySelector('#newsletter form');
form.addEventListener('submit', controllaMail);
const input = document.querySelector('#newsletter input');
input.addEventListener('click', ripristina);

