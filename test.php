<?php
    // ini_set('display_errors', 0);
    // error_reporting(E_ALL);
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test kategorii <?php echo $_GET['kategoria']; ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<body>


    <form action="wyniki.php" method="POST">
        <section id='progressBarBackground'>
            <section id="progressBar">
                <span id='numerPytania'></span>
            </section>
        </section>
        <?php
            require __DIR__ . '/conf/config.php';
            $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
        	$conn->set_charset("utf8mb4");

            $seed = 0;
            if (isset($_GET['seed']) && !empty($_GET['seed'])) {
                $seed = intval(mysqli_real_escape_string($conn, $_GET['seed']));
                echo "Seeded run";
            }
            else {
                $seed = rand();
            }

            echo "<a href='index.php'><i class='fa fa-home'></i></a>";
            

            $listaIdPytan = [];
            $lsitaOdpowiedzi = [];
            
            $wybranyJezyk = $_GET['jezyk'] ?? 'pl';

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
            $reset = "Zresetuj test";
            $zglos = "Zgłoś błąd";
            $poczekaj = "Jeżeli zdjęcie lub film nie wczytuje się od razu, poczekaj chwilę.";

            if ($wybranyJezyk == "en") {
                $tak = "Yes";
                $nie = "No";
                $zakoncz = "End test";
                $punktow = "pts";
                $str_podstawowy = "BASIC";
                $str_specjalistyczny = "SPECIALIZED";
                $str_pytanie = "Question";
                $reset = "Reset test";
                $zglos = "Report error";
                $poczekaj = "If the photo or video doesn't load at first, wait a moment.";
            }
            else if ($wybranyJezyk == "de") {
                $tak = "Ja";
                $nie = "Nein";
                $zakoncz = "Test Beenden";
                $punktow = "Punkte";
                $str_podstawowy = "BASIC";
                $str_specjalistyczny = "SPEZIALISIERT";
                $str_pytanie = "Frage";
                $reset = "Test zurücksetzen";
                $zglos = "Fehler melden";
                $poczekaj = "Sollte das Foto oder Video nicht sofort geladen werden, einen Moment wort.";
            }
            else if ($wybranyJezyk == "ua") {
                $tak = "так";
                $nie = "ні";
                $zakoncz = "Кінець тесту";
                $punktow = "бали";
                $str_podstawowy = "БАЗОВИЙ";
                $str_specjalistyczny = "СПЕЦІАЛІЗОВАНИЙ";
                $str_pytanie = "Питання";
                $reset = "Скинути тест";
                $zglos = "Повідомити про помилку";
                $poczekaj = "Якщо фото або відео не завантажується одразу, зачекайте трохи.";
            }

            echo "<a id='reset' href='test.php?jezyk=" . $_GET['jezyk'] . "&kategoria=" . $_GET['kategoria'] . "'><i class='fa fa-repeat'></i><span >$reset</span></a>";

            $pyt   = $wybranyJezyk === 'pl' ? 'pyt'   : "pyt_$wybranyJezyk";
            $odp_a = $wybranyJezyk === 'pl' ? 'odp_a' : "odp_a_$wybranyJezyk";
            $odp_b = $wybranyJezyk === 'pl' ? 'odp_b' : "odp_b_$wybranyJezyk";
            $odp_c = $wybranyJezyk === 'pl' ? 'odp_c' : "odp_c_$wybranyJezyk";
            $queryPodstawowe = "
            
                SELECT * FROM (
                    SELECT id, nr_pyt, $pyt as pyt, $odp_a as odp_a, $odp_b as odp_b, $odp_c as odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 3 AND zakres = 'PODSTAWOWY'
                    ORDER BY RAND($seed)
                    LIMIT 10
                ) t1

                UNION ALL

                SELECT * FROM (
                    SELECT id, nr_pyt, $pyt as pyt, $odp_a as odp_a, $odp_b as odp_b, $odp_c as odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 2 AND zakres = 'PODSTAWOWY'
                    ORDER BY RAND($seed)
                    LIMIT 6
                ) t2

                UNION ALL

                SELECT * FROM (
                    SELECT id, nr_pyt, $pyt as pyt, $odp_a as odp_a, $odp_b as odp_b, $odp_c as odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 1 AND zakres = 'PODSTAWOWY'
                    ORDER BY RAND($seed)
                    LIMIT 4
                ) t3 ORDER BY RAND($seed)";
            $querySpecjalistyczne = "
                SELECT * FROM (
                    SELECT id, nr_pyt, $pyt as pyt, $odp_a as odp_a, $odp_b as odp_b, $odp_c as odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 3 AND zakres = 'SPECJALISTYCZNY'
                    ORDER BY RAND($seed)
                    LIMIT 6
                ) t1

                UNION ALL

                SELECT * FROM (
                    SELECT id, nr_pyt, $pyt as pyt, $odp_a as odp_a, $odp_b as odp_b, $odp_c as odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 2 AND zakres = 'SPECJALISTYCZNY'
                    ORDER BY RAND($seed)
                    LIMIT 4
                ) t2

                UNION ALL

                SELECT * FROM (
                    SELECT id, nr_pyt, $pyt as pyt, $odp_a as odp_a, $odp_b as odp_b, $odp_c as odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 1 AND zakres = 'SPECJALISTYCZNY'
                    ORDER BY RAND($seed)
                    LIMIT 2
                ) t3 ORDER BY RAND($seed)";

            echo $queryPodstawowe . "<br>" . $querySpecjalistyczne . "<br>";
            $resultPodstawowe = mysqli_query($conn, $queryPodstawowe);

            $resultSpecjalistyczne = mysqli_query($conn, $querySpecjalistyczne);

            while ($row = mysqli_fetch_assoc($resultPodstawowe)) {
                echo "<section class='pytaniePodstawowe pytanie'>";
                echo "<span class='poprzednie' onclick='zmienPytanie(-1)'><i class='fa fa-arrow-left'></i></span>";
                echo "<section class='wrapper'>";
                echo "<p>{$str_pytanie} " . $row['id'] . " (" . $row['nr_pyt'] . ") " . $str_podstawowy . ": " . $row['pkt'] . " {$punktow}.</p>";
                echo "<p>" . $row['pyt'] . "</p>";
                if (str_ends_with(strval(strtolower($row['media'])), ".jpg")) {
                    echo "<img src='media/" . trim($row['media']) . "' alt='media/" . $row['media'] . "'><br>";
                }
                else if (str_ends_with(strval($row['media']), ".mp4")) {
                    echo "<video controls><source src='media/" . trim($row['media']) . "' alt='media/" . $row['media'] . "'></video><br>";
                }
                echo "<label for='tak{$row['id']}'><section class='T'><input type='radio' value='T' id='tak{$row['id']}' name='podstawowe" . $row['id'] . "'> {$tak}</section></label>";
                echo "<label for='nie{$row['id']}'><section class='N'><input type='radio' value='N' id='nie{$row['id']}' name='podstawowe" . $row['id'] . "'> {$nie}</section></label>";
                echo "</section>";
                echo "<span class='nastepne' onclick='zmienPytanie(1)'><i class='fa fa-arrow-right'></i></span>";
                echo "</section>";
                array_push($listaIdPytan, $row['id']);
            }

            while ($row = mysqli_fetch_assoc($resultSpecjalistyczne)) {
                echo "<section class='pytanieSpecjalistyczne pytanie'>";
                echo "<span class='poprzednie' onclick='zmienPytanie(-1)'><i class='fa fa-arrow-left'></i></span>";
                echo "<section class='wrapper'>";
                echo "<p>{$str_pytanie} " . $row['id'] . " (" . $row['nr_pyt'] . ") " . $str_specjalistyczny . ": " . $row['pkt'] . " {$punktow}</p>";
                echo "<p>" . $row['pyt'] . "</p>";
                if (str_ends_with(strval(strtolower($row['media'])), ".jpg")) {
                    echo "<img src='media/" . trim($row['media']) . "' alt='media/" . $row['media'] . "'><br>";
                }
                else if (str_ends_with(strval($row['media']), ".mp4")) {
                    echo "<video controls><source src='media/" . trim($row['media']) . "' alt='media/" . $row['media'] . "'></video><br>";
                }
                if ($row['odp_a'] != "" && $row['odp_b'] != "" && $row['odp_c'] != "") {
                    echo "<label for='a{$row['id']}'> <section class='A'><input type='radio' value='A' id='a{$row['id']}' name='specjalistyczne" . $row['id'] . "'>" . $row['odp_a'] . "</section></label>";
                    echo "<label for='b{$row['id']}'> <section class='B'><input type='radio' value='B' id='b{$row['id']}' name='specjalistyczne" . $row['id'] . "'>" . $row['odp_b'] . "</section></label>";
                    echo "<label for='c{$row['id']}'> <section class='C'><input type='radio' value='C' id='c{$row['id']}' name='specjalistyczne" . $row['id'] . "'>" . $row['odp_c'] . "</section></label>";
                }
                else {
                    echo "<label for='tak{$row['id']}'> <section class='T'><input type='radio' value='T' id='tak{$row['id']}' name='specjalistyczne" . $row['id'] . "'>{$tak}</section></label>";
                    echo "<label for='nie{$row['id']}'><section class='N'><input type='radio' value='N' id='nie{$row['id']}' name='specjalistyczne" . $row['id'] . "'> {$nie}</section></label>";
                }
                echo "</section>";
                echo "<span class='nastepne' onclick='zmienPytanie(1)'><i class='fa fa-arrow-right'></i></span>";
                echo "</section>";
                array_push($listaIdPytan, $row['id']);
            }

            mysqli_close($conn);
            $listaIdPytan = implode(",", $listaIdPytan);
            echo "<input type='hidden' name='listaIdPytan' value=$listaIdPytan>";
            echo "<input type='hidden' name='kategoria' value='" . $_GET['kategoria'] . "'>";
            echo "<input type='hidden' name='jezyk' value='{$wybranyJezyk}'>";
            echo "<a href='mailto:adikk99@gmail.com'><i class='fa fa-exclamation-triangle'></i><span id='zglos'>$zglos</span></a>";
        
        ?>
        <input type="submit" id='koniec' value="<?php echo $zakoncz; ?>" disabled>
        <h4><?php echo $poczekaj; ?></h4>
        
    </form>

</body>
<script src="js.js">

</script>
</html>



