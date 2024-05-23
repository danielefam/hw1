function toggleAccediRegistra(event){
    event.preventDefault();

    const login = document.getElementById('login');
    const signup = document.getElementById('signup');

    if(login.classList.contains('hidden')){
        login.classList.remove('hidden');
        signup.classList.add('hidden');
    }
    else{
        signup.classList.remove('hidden');
        login.classList.add('hidden');
    }
}

function previeniRicarica(event){
    event.preventDefault();
}

function validaLogin(event){

    const form = event.currentTarget;
    
    if(form.email.value.length == 0 || form.password.value.length == 0){
        alert("Compilare tutti i campi.");
        event.preventDefault();
    }
      
}

function onJsonSignup(json){
    console.log(json);

    const div = document.getElementById('blocco-errori');
    div.innerHTML = "";

    if(json.errori.length === 1){
        window.location.href = "hw1-login-signup.php";
        return;
    }

    for(errore of json.errori){
        if(errore === "salta")
            continue;
        const p = document.createElement('span');
        p.classList.add('errore');
        p.textContent = errore;
        div.appendChild(p);
    }
    
}

function onResponseSignup(response){
    return response.json();
}

function validaSignup(event){
    event.preventDefault();

    const form = event.currentTarget;

    if(form.nome_reg.value.length == 0 ||
        form.cognome_reg.value.length == 0 ||
        form.email_reg.value.length == 0 ||
        form.password_reg.value.length == 0)
    {        
        alert("Compilare tutti i campi.");        
    }
    else{
        const arrivaDaJs = true;
        const formDaInviare = new FormData(form);
        formDaInviare.append("chiave", arrivaDaJs);
        const dati_form = {method: 'post', body: formDaInviare};
        fetch("informazioniphp/hw1-signup.php", dati_form).then(onResponseSignup).then(onJsonSignup);
        // console.log(form);
    }
      
}



const registrati = document.querySelectorAll('.login-signup');
for(a of registrati){
    a.addEventListener('click', toggleAccediRegistra);
}

const link = document.querySelectorAll('a');
for(let a of link){
  if(a.getAttribute('href') === "#")
    a.addEventListener('click', previeniRicarica);
}

const formLogin = document.forms['login'];
formLogin.addEventListener('submit', validaLogin);

const formSignup = document.forms['signup'];
formSignup.addEventListener('submit', validaSignup);