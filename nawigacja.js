function wypelnijNawigacje() {
        console.log("wypełniam");

    let nav = document.getElementById("box");
    let poprawneBledne = document.getElementById('poprawneBledne').value;
    poprawneBledne = poprawneBledne.split(",");
    console.log(poprawneBledne);
    for (let i = 0; i < 32; i++) {
        let kostka = document.createElement("section");
        kostka.classList.add("kostkaNawigacji");
        kostka.innerHTML = "<a href='#sekcja" + (i) + "'>" + (i+1) + "</a>";
        if (poprawneBledne[i] == 1) {
            kostka.style.backgroundColor = "green";
        }
        else if (poprawneBledne[i] == 0) {
            kostka.style.backgroundColor = "red";
        }
        nav.appendChild(kostka);
    }
}

wypelnijNawigacje();