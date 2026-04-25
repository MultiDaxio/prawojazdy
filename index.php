<?php
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prawo jazdy - baza pytań teoretycznych</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

</head>
<body>
    <form action="test.php" method="get">
        <h2 id='langdisp'>Wybierz język testu</h2>
        <label for="pl" onclick='pickLang(event); changeDisp("pl")' class='lang langpl'><img onmouseover="highlightOn(event)" onmouseout="highlightOff(event)"  class="flaga" src='icons/poland.png' alt='polski'></label>
        <input type="radio" name="jezyk" value="pl" id="pl" checked>
        <label for="en" onclick='pickLang(event); changeDisp("en")' class='lang'><img onmouseover="highlightOn(event)" onmouseout="highlightOff(event)" class="flaga" src='icons/uk.png' alt='english'></label>
        <input type="radio" name="jezyk" value="en" id="en">
        <label for="de" onclick='pickLang(event); changeDisp("de")' class='lang'><img onmouseover="highlightOn(event)" onmouseout="highlightOff(event)" class="flaga" src='icons/germany.png' alt='deutsch'></label>
        <input type="radio" name="jezyk" value="de" id="de">
        <label for="ua" onclick='pickLang(event); changeDisp("ua")' class='lang'><img onmouseover="highlightOn(event)" onmouseout="highlightOff(event)" class="flaga" src='icons/ukraine.png' alt='українська'></label>
        <input type="radio" name="jezyk" value="ua" id="ua">
        <br>
        <h2 id='katdisp'><label for="kategoria">Wybierz kategorię prawa jazdy:</label></h2>
        <select name="kategoria" id="kategoria" oninput="zapamietajKategorie()" onload="wczytajKategorie()">
            <option value="AM">AM</option>
            <option value="A1">A1</option>
            <option value="A2">A2</option>
            <option value="A">A</option>
            <option value="B1">B1</option>
            <option value="B">B</option>
            <option value="B+E">B+E</option>
            <option value="C1">C1</option>
            <option value="C1+E">C1+E</option>
            <option value="C">C</option>
            <option value="C+E">C+E</option>
            <option value="D1">D1</option>
            <option value="D1+E">D1+E</option>
            <option value="D">D</option>
            <option value="D+E">D+E</option>
            <option value="T">T</option>
        </select>
        <br>
        <input type="submit" id='begin' value="Rozpocznij test">
    </form>
    <a href='mailto:adikk99@gmail.com'><i class='fa fa-exclamation-triangle'></i><span id='zglos'>Zgłoś błąd</span></a>
    <section id="faq">
        <h2>Dlaczego powstała ta strona?</h2>
        <p>Bo baza pytań, wszystkie zdjęcia i filmy do egzaminu są za darmo, a strony do rozwiązywania egzaminów, które widziałem, niekoniecznie.</p>
        <h2>Jak mam korzystać z tej strony?</h2>
        <ol>
            <li>wybierz język testu</li>
            <li>wybierz kategorię prawa jazdy</li>
            <li>przejdź do rozwiązywania testu</li>
            <li>opcja do zakończenia testu uaktywni się po dotarciu na ostatnie pytanie</li>
            <li>sprawdź swoje odpowiedzi; w lewym górnym rogu pojawi sie wynik punktowy</li>
        </ol>
    </section>
</body>
<script src='js.js' defer></script>
</html>