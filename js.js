let obecnePytanie = 1;
let wszystkieBlokiPytan = document.getElementsByClassName('pytanie');

document.getElementById('numerPytania').innerText = obecnePytanie + "/32";
console.log(obecnePytanie);


if (obecnePytanie == 1) {
    document.getElementsByClassName('poprzednie')[0].style.display = "none";
}

if (obecnePytanie == 32) {
    document.getElementsByClassName('nastepne')[31].style.display = "none";
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
    document.getElementsByClassName('nastepne')[31].style.display = "none";
    }
}


document.querySelectorAll('.T, .N, .A, .B, .C').forEach(el => {
    el.addEventListener('click', function(e) {
        const input = this.querySelector('input');
        input.checked = true;
    });
});