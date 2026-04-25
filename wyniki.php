<?php
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wyniki egzaminu</title>
    <link rel="stylesheet" href="style_wyniki.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

</head>
<body>
    <?php
        $wybranyJezyk = $_POST['jezyk'];

        $allowed = ['pl', 'en', 'de', 'ua'];

        if (!in_array($wybranyJezyk, $allowed)) {
            $wybranyJezyk = 'pl';
        }

        $tak = "Tak";
        $nie = "Nie";
        $zakoncz = "Zakończ test";
        $punktow = "pkt";
        $str_podstawowy = "PODSTAWOWY";
        $str_specjalistyczny = "SPECJALISTYCZNY";
        $str_pytanie = "Pytanie";
        $str_poprawna = "Poprawna odpowiedź";
        $str_twoja = "Twoja odpowiedź";
        $str_brak = "brak";

        if ($wybranyJezyk == "en") {
            $tak = "Yes";
            $nie = "No";
            $zakoncz = "End test";
            $punktow = "pts";
            $str_podstawowy = "BASIC";
            $str_specjalistyczny = "SPECIALIZED";
            $str_pytanie = "Question";
            $str_poprawna = "Correct answer";
            $str_twoja = "Your answer";
            $str_brak = "none";
        }
        else if ($wybranyJezyk == "de") {
            $tak = "Ja";
            $nie = "Nein";
            $zakoncz = "Test Beenden";
            $punktow = "Punkte";
            $str_podstawowy = "BASIC";
            $str_specjalistyczny = "SPEZIALISIERT";
            $str_pytanie = "Frage";
            $str_poprawna = "Richtige Antwort";
            $str_twoja = "Ihre Antwort";
            $str_brak = "Keine";
        }
        else if ($wybranyJezyk == "ua") {
            $tak = "так";
            $nie = "ні";
            $zakoncz = "Кінець тесту";
            $punktow = "бали";
            $str_podstawowy = "БАЗОВИЙ";
            $str_specjalistyczny = "СПЕЦІАЛІЗОВАНИЙ";
            $str_pytanie = "Питання";
            $str_poprawna = "Правильна відповідь";
            $str_twoja = "Ваша відповідь";
            $str_brak = "немає";
        }

        $pyt   = $wybranyJezyk === 'pl' ? 'pyt'   : "pyt_$wybranyJezyk";
        $odp_a = $wybranyJezyk === 'pl' ? 'odp_a' : "odp_a_$wybranyJezyk";
        $odp_b = $wybranyJezyk === 'pl' ? 'odp_b' : "odp_b_$wybranyJezyk";
        $odp_c = $wybranyJezyk === 'pl' ? 'odp_c' : "odp_c_$wybranyJezyk";







        $listaIdPytan = $_POST['listaIdPytan'];
        $numerPytania = 0;
        require __DIR__ . '/conf/config.php';
        $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    	$conn->set_charset("utf8mb4");
        $query = "SELECT id, $pyt as pyt, poprawna, pkt, media, nr_pyt, zakres, $odp_a as odp_a, $odp_b as odp_b, $odp_c as odp_c
                FROM pula_pytan
                WHERE id IN ($listaIdPytan)
                ORDER BY FIELD(id, $listaIdPytan)";
        $result = mysqli_query($conn, $query);
        $ktorePytanie = "";
        $pkt = 0;
        while($row = mysqli_fetch_assoc($result)) {
            if ($numerPytania < 20) {
                $ktorePytanie = "podstawowe" . $row['id'];
                echo "<section class='pytanie pytaniePodstawowe'>";

            } else {
                $ktorePytanie = "specjalistyczne" . $row['id'];
                echo "<section class='pytane pytanieSpecjalistyczne'>";
            }

            $numerPytania++;

            if (isset($_POST[$ktorePytanie])) {
                $odpowiedz = $_POST[$ktorePytanie];
                echo "<p>$str_pytanie " . $row['id'] . " (id: " . $row['nr_pyt'] . ") " . ($numerPytania <= 20 ? $str_podstawowy : $str_specjalistyczny) . ": " . $row['pkt'] . " $punktow.</p>";
                echo "<p>" . $row['pyt'] . "</p>";
                if (str_ends_with(strval($row['media']), ".jpg")) {
                    echo "<img src='media/" . $row['media'] . "' alt='media/" . $row['media'] . "'><br>";
                }
                else if (str_ends_with(strval($row['media']), ".mp4")) {
                    echo "<video controls><source src='media/" . $row['media'] . "' alt='media/" . $row['media'] . "'></video><br>";
                }
                echo "<h3>$str_poprawna</h3>";
                if ($row['poprawna'] == 'T') {
                    echo "<section class='T'><label for='tak{$row['id']}'> $tak</label></section>";
                }
                else if ($row['poprawna'] == 'N'){
                    echo "<section class='N'><label for='nie{$row['id']}'> $nie</label></section>";
                }
                else if ($row['poprawna'] == 'A'){
                    echo "<section class='A'><label for='nie{$row['id']}'> A</label></section>";
                }
                else if ($row['poprawna'] == 'B'){
                    echo "<section class='B'><label for='nie{$row['id']}'> B</label></section>";
                }
                else if ($row['poprawna'] == 'C'){
                    echo "<section class='C'><label for='nie{$row['id']}'> C</label></section>";
                }

                echo "<h3>$str_twoja</h3>";
                echo "<section class='$odpowiedz'><label>$odpowiedz</label></section>";
              
                if ($odpowiedz == $row['poprawna']) {
                    echo "{$row['pkt']} $punktow.<br>";
                    $pkt += intval($row['pkt']);
                } else {
                    echo "0 $punktow<br>";
                }

            } else {
                echo "<p>$str_pytanie " . $row['id'] . " (id: " . $row['nr_pyt'] . ") " . ($numerPytania <= 20 ? $str_podstawowy : $str_specjalistyczny) . ": " . $row['pkt'] . " $punktow.</p>";
                echo "<p>" . $row['pyt'] . "</p>";
                if (str_ends_with(strval($row['media']), ".jpg")) {
                    echo "<img src='media/" . $row['media'] . "' alt='media/" . $row['media'] . "'><br>";
                }
                else if (str_ends_with(strval($row['media']), ".mp4")) {
                    echo "<video controls><source src='media/" . $row['media'] . "' alt='media/" . $row['media'] . "'></video><br>";
                }

                echo "<h3>$str_poprawna</h3>";

                if ($row['poprawna'] == 'T') {
                    echo "<section class='T'><label for='tak{$row['id']}'> $tak</label></section>";
                }
                else if ($row['poprawna'] == 'N'){
                    echo "<section class='N'><label for='nie{$row['id']}'> $nie</label></section>";
                }
                else if ($row['poprawna'] == 'A'){
                    echo "<section class='A'><label for='nie{$row['id']}'> A</label></section>";
                }
                else if ($row['poprawna'] == 'B'){
                    echo "<section class='B'><label for='nie{$row['id']}'> B</label></section>";
                }
                else if ($row['poprawna'] == 'C'){
                    echo "<section class='C'><label for='nie{$row['id']}'> C</label></section>";
                }
                                
                echo "<h3>$str_twoja</h3>";
                echo "<section class='brak'><label>$str_brak</label></section>";

            }
            echo "</section>";
        }
        
        echo "<section id='wynikPunktowy'";
        if ($pkt >= 68) {
            echo " class='zdane'>";
        }
        else {
            echo " class='niezdane'>";
        }
        echo $pkt ."<br>";
        echo "<hr>";
        echo "74";
        if ($pkt >= 68) {
        }
        echo "</section>";
        mysqli_close($conn);
        echo "<a href='index.php'><i class='fa fa-home'></i></a>";
        
    ?>
    <a href='mailto:adikk99@gmail.com'><i class='fa fa-exclamation-triangle'></i><span id='zglos'>Zgłoś błąd</span></a>
</body>
</html>
