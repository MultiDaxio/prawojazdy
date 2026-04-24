<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test kategorii <?php echo $_GET['kategoria']; ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <h1>Egzamin teoretyczny kategorii <?php echo $_GET['kategoria']; ?></h1>
    <form action="wyniki.php" method="POST">
        <section id='progressBarBackground'>
            <section id="progressBar">
                <span id='numerPytania'></span>
            </section>
        </section>
        <?php
            echo "<a href='index.php'><i class='fa fa-home'></i></a>";

            $listaIdPytan = [];
            $lsitaOdpowiedzi = [];

            $conn = mysqli_connect("localhost", "root", "", "prawo_jazdy");
            $wybranyJezyk = $_GET['jezyk'] ?? 'pl';

            $allowed = ['pl', 'en', 'de', 'ua'];

            if (!in_array($wybranyJezyk, $allowed)) {
                $wybranyJezyk = 'pl';
            }

            $pyt   = $wybranyJezyk === 'pl' ? 'pyt'   : "pyt_$wybranyJezyk";
            $odp_a = $wybranyJezyk === 'pl' ? 'odp_a' : "odp_a_$wybranyJezyk";
            $odp_b = $wybranyJezyk === 'pl' ? 'odp_b' : "odp_b_$wybranyJezyk";
            $odp_c = $wybranyJezyk === 'pl' ? 'odp_c' : "odp_c_$wybranyJezyk";
            $queryPodstawowe = "
            
                SELECT * FROM (
                    SELECT id, nr_pyt, $pyt as pyt, $odp_a as odp_a, $odp_b as odp_b, $odp_c as odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 3 AND zakres = 'PODSTAWOWY'
                    ORDER BY RAND()
                    LIMIT 10
                ) t1

                UNION ALL

                SELECT * FROM (
                    SELECT id, nr_pyt, $pyt as pyt, $odp_a as odp_a, $odp_b as odp_b, $odp_c as odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 2 AND zakres = 'PODSTAWOWY'
                    ORDER BY RAND()
                    LIMIT 6
                ) t2

                UNION ALL

                SELECT * FROM (
                    SELECT id, nr_pyt, $pyt as pyt, $odp_a as odp_a, $odp_b as odp_b, $odp_c as odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 1 AND zakres = 'PODSTAWOWY'
                    ORDER BY RAND()
                    LIMIT 4
                ) t3 ORDER BY RAND()";
            $querySpecjalistyczne = "
                SELECT * FROM (
                    SELECT id, nr_pyt, $pyt as pyt, $odp_a as odp_a, $odp_b as odp_b, $odp_c as odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 3 AND zakres = 'SPECJALISTYCZNY'
                    ORDER BY RAND()
                    LIMIT 6
                ) t1

                UNION ALL

                SELECT * FROM (
                    SELECT id, nr_pyt, $pyt as pyt, $odp_a as odp_a, $odp_b as odp_b, $odp_c as odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 2 AND zakres = 'SPECJALISTYCZNY'
                    ORDER BY RAND()
                    LIMIT 4
                ) t2

                UNION ALL

                SELECT * FROM (
                    SELECT id, nr_pyt, $pyt as pyt, $odp_a as odp_a, $odp_b as odp_b, $odp_c as odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 1 AND zakres = 'SPECJALISTYCZNY'
                    ORDER BY RAND()
                    LIMIT 2
                ) t3 ORDER BY RAND()";

            $resultPodstawowe = mysqli_query($conn, $queryPodstawowe);

            $resultSpecjalistyczne = mysqli_query($conn, $querySpecjalistyczne);

            while ($row = mysqli_fetch_assoc($resultPodstawowe)) {
                echo "<section class='pytaniePodstawowe pytanie'>";
                echo "<span class='poprzednie' onclick='zmienPytanie(-1)'><i class='fa fa-arrow-left'></i></span>";
                echo "<section class='wrapper'>";
                echo "<p>Pytanie " . $row['id'] . " (id: " . $row['nr_pyt'] . ") " . $row['zakres'] . ": " . $row['pkt'] . " pkt.</p>";
                echo "<p>" . $row['pyt'] . "</p>";
                if (str_ends_with(strval($row['media']), ".jpg")) {
                    echo "<img src='media/" . $row['media'] . "' alt='media/" . $row['media'] . "'><br>";
                }
                else if (str_ends_with(strval($row['media']), ".mp4")) {
                    echo "<video controls><source src='media/" . $row['media'] . "' alt='media/" . $row['media'] . "'></video><br>";
                }
                echo "<section class='T'><input type='radio' value='T' id='tak{$row['id']}' name='podstawowe" . $row['id'] . "'><label for='tak{$row['id']}'> Tak</label></section>";
                echo "<section class='N'><input type='radio' value='N' id='nie{$row['id']}' name='podstawowe" . $row['id'] . "'><label for='nie{$row['id']}'> Nie</label></section>";
                echo "</section>";
                echo "<span class='nastepne' onclick='zmienPytanie(1)'><i class='fa fa-arrow-right'></i></span>";
                echo "</section>";
                array_push($listaIdPytan, $row['id']);
            }

            while ($row = mysqli_fetch_assoc($resultSpecjalistyczne)) {
                echo "<section class='pytanieSpecjalistyczne pytanie'>";
                echo "<span class='poprzednie' onclick='zmienPytanie(-1)'><i class='fa fa-arrow-left'></i></span>";
                echo "<section class='wrapper'>";
                echo "<p>Pytanie " . $row['id'] . " (id: " . $row['nr_pyt'] . ") " . $row['zakres'] . ": " . $row['pkt'] . " pkt.</p>";
                echo "<p>" . $row['pyt'] . "</p>";
                if (str_ends_with(strval($row['media']), ".jpg")) {
                    echo "<img src='media/" . $row['media'] . "' alt='media/" . $row['media'] . "'><br>";
                }
                else if (str_ends_with(strval($row['media']), ".mp4")) {
                    echo "<video controls><source src='media/" . $row['media'] . "' alt='media/" . $row['media'] . "'></video><br>";
                }
                if ($row['odp_a'] != "" && $row['odp_b'] != "" && $row['odp_c'] != "") {
                    echo "<section class='A'><input type='radio' value='A' id='a{$row['id']}' name='specjalistyczne" . $row['id'] . "'><label for='a{$row['id']}'> " . $row['odp_a'] . "</label></section>";
                    echo "<section class='B'><input type='radio' value='B' id='b{$row['id']}' name='specjalistyczne" . $row['id'] . "'><label for='b{$row['id']}'> " . $row['odp_b'] . "</label></section>";
                    echo "<section class='C'><input type='radio' value='C' id='c{$row['id']}' name='specjalistyczne" . $row['id'] . "'><label for='c{$row['id']}'> " . $row['odp_c'] . "</label></section>";
                }
                else {
                    echo "<section class='T'><input type='radio' value='T' id='tak{$row['id']}' name='specjalistyczne" . $row['id'] . "'><label for='tak{$row['id']}'> Tak</label></section>";
                    echo "<section class='N'><input type='radio' value='N' id='nie{$row['id']}' name='specjalistyczne" . $row['id'] . "'><label for='nie{$row['id']}'> Nie</label></section>";
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
            
        ?>
        <input type="submit" id='koniec' value="Zakończ test" disabled>
    </form>
</body>
<script src="js.js">

</script>
</html>



