function aggiungiEvento(event) {
    const form_data = {method: 'post', body: new FormData(event.currentTarget)};
    fetch("admin.php", form_data);
    
    event.preventDefault();
}

function visualizzaCasella(){
    label.classList.remove('hidden');
}

function nascondiCasella(){
    label.classList.add('hidden');
}

const form = document.forms["aggiungi-prodotto"];
form.addEventListener("submit", aggiungiEvento);

document.getElementById('scon').addEventListener('change', visualizzaCasella);
document.getElementById('non-scon').addEventListener('change', nascondiCasella);

const label = document.getElementById('scontoprezzo');
