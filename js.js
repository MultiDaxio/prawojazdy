

document.addEventListener("DOMContentLoaded", function () {

    document.querySelector('.langpl').style.filter = "brightness(115%)";

    
    document.querySelectorAll('.T, .N, .A, .B, .C').forEach(el => {
        el.addEventListener('click', function(e) {

            if (e.target.tagName === 'INPUT' || e.target.tagName === 'LABEL') {
                return;
            }

            const input = this.querySelector('input');
            if (input) {
                input.checked = true;
            }
        });
    });
    wczytajKategorie();
});

let obecnePytanie = 1;
let wszystkieBlokiPytan = document.getElementsByClassName('pytanie');

if (wszystkieBlokiPytan.length > 0) {
    wszystkieBlokiPytan[0].classList.add('aktywne');
}

document.getElementById('numerPytania').innerText = obecnePytanie + "/32";
    

if (obecnePytanie == 1) {
    document.getElementsByClassName('poprzednie')[0].style.visibility = "hidden";
}

if (obecnePytanie == 32) {
    document.getElementsByClassName('nastepne')[31].style.visibility = "hidden";
}

if (obecnePytanie != 32) {
    document.getElementById('koniec').disabled = true;
}
else {
    document.getElementById('koniec').disabled = false;
}

wszystkieBlokiPytan[0].classList.add('aktywne');

function zmienPytanie(x) {
    if (obecnePytanie+x >= 1 && obecnePytanie+x <= 32) {

        wszystkieBlokiPytan[obecnePytanie-1].classList.remove('aktywne');
        wszystkieBlokiPytan[obecnePytanie-1+x].classList.add('aktywne');

        obecnePytanie += x;

        document.getElementById("progressBar").style.width = (3.125 * obecnePytanie) + "%";
    }

    document.getElementById('numerPytania').innerText = obecnePytanie + "/32";
    if (obecnePytanie != 32) {
        document.getElementById('koniec').disabled = true;
    }
    else {
        document.getElementById('koniec').disabled = false;
    }
    if (obecnePytanie == 32) {
        document.getElementsByClassName('nastepne')[31].style.visibility = "hidden";
    }
}

function pickLang(event) {
    let labels = document.querySelectorAll('.lang');
    for (let i = 0; i < labels.length; i++) {
        labels[i].style.backgroundColor = "inherit";
        labels[i].style.filter = "brightness(90%)";
    }
    event.currentTarget.style.backgroundColor = "inherit";
    event.currentTarget.style.filter = "brightness(115%)";
}
lang = localStorage.getItem('lang');
function changeDisp(lang) {
    let langdisp = document.getElementById('langdisp');
    let katdisp = document.getElementById('katdisp');
    let begin = document.getElementById('begin');
    localStorage.setItem('lang', lang);
    switch (lang) {
        case 'pl':
            langdisp.innerText = "Wybierz język testu";
            katdisp.innerText = "Wybierz kategorię prawa jazdy:";
            begin.value = "Rozpocznij test";
            break;
        case 'en':
            langdisp.innerText = "Choose test language";
            katdisp.innerText = "Choose driver's licence category:";
            begin.value = "Begin test";
            break;
        case 'de':
            langdisp.innerText = "Testsprache auswählen";
            katdisp.innerText = "Führerscheinkategorie auswählen:";
            begin.value = "Test Starten";
            break;
        case 'ua':
            langdisp.innerText = "Виберіть мову тесту";
            katdisp.innerText = "Виберіть категорію водійського посвідчення:";
            begin.value = "Почніть тест";
            break;
    }
}

function highlightOn(event) {
    event.target.style.filter = "brightness(130%)";
    event.target.style.transform = "translateY(5px)";
}

function highlightOff(event) {
    event.target.style.filter = "brightness(90%)";
    event.target.style.transform = "translateY(0px)";
}

function wczytajKategorie() {
    console.log("Wczytano:", localStorage.getItem("kategoria"));
    document.getElementById("kategoria").value = localStorage.getItem("kategoria");
}

function zapamietajKategorie() {
    localStorage.setItem("kategoria", document.getElementById("kategoria").value);
    console.log("Zapisano:", document.getElementById("kategoria").value);
}



