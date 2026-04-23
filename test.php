<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test kategorii <?php echo $_GET['kategoria']; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="wyniki.php" method="POST">
        <section id='progressBarBackground'>
            <section id="progressBar">
                <span id='numerPytania'></span>
            </section>
        </section>
        <?php
            $listaIdPytan = [];
            $lsitaOdpowiedzi = [];

            $conn = mysqli_connect("localhost", "root", "", "prawo_jazdy");
            $queryPodstawowe = "
            
                SELECT * FROM (
                    SELECT id, nr_pyt, pyt, odp_a, odp_b, odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 3 AND zakres = 'PODSTAWOWY'
                    ORDER BY RAND()
                    LIMIT 10
                ) t1

                UNION ALL

                SELECT * FROM (
                    SELECT id, nr_pyt, pyt, odp_a, odp_b, odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 2 AND zakres = 'PODSTAWOWY'
                    ORDER BY RAND()
                    LIMIT 6
                ) t2

                UNION ALL

                SELECT * FROM (
                    SELECT id, nr_pyt, pyt, odp_a, odp_b, odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 1 AND zakres = 'PODSTAWOWY'
                    ORDER BY RAND()
                    LIMIT 4
                ) t3 ORDER BY RAND()";
            $querySpecjalistyczne = "
                SELECT * FROM (
                    SELECT id, nr_pyt, pyt, odp_a, odp_b, odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 3 AND zakres = 'SPECJALISTYCZNY'
                    ORDER BY RAND()
                    LIMIT 6
                ) t1

                UNION ALL

                SELECT * FROM (
                    SELECT id, nr_pyt, pyt, odp_a, odp_b, odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 2 AND zakres = 'SPECJALISTYCZNY'
                    ORDER BY RAND()
                    LIMIT 4
                ) t2

                UNION ALL

                SELECT * FROM (
                    SELECT id, nr_pyt, pyt, odp_a, odp_b, odp_c, poprawna, media, zakres, pkt, kategorie
                    FROM pula_pytan
                    WHERE kategorie LIKE '%" . $_GET['kategoria'] . "%' AND pkt = 1 AND zakres = 'SPECJALISTYCZNY'
                    ORDER BY RAND()
                    LIMIT 2
                ) t3 ORDER BY RAND()";

            $resultPodstawowe = mysqli_query($conn, $queryPodstawowe);

            $resultSpecjalistyczne = mysqli_query($conn, $querySpecjalistyczne);

            while ($row = mysqli_fetch_assoc($resultPodstawowe)) {
                echo "<section class='pytaniePodstawowe pytanie'>";
                echo "<p>Pytanie " . $row['id'] . " (id: " . $row['nr_pyt'] . ")</p>";
                echo "<p>" . $row['zakres'] . ": " . $row['pkt'] . " pkt.</p>";
                echo "<p>" . $row['pyt'] . "</p>";
                if (str_ends_with(strval($row['media']), ".jpg")) {
                    echo "<img src='media/" . $row['media'] . "'><br>";
                }
                else if (str_ends_with(strval($row['media']), ".mp4")) {
                    echo "<video controls><source src='media/" . $row['media'] . "'></video><br>";
                }
                echo "<section class='T'><input type='radio' value='T' id='tak{$row['id']}' name='podstawowe" . $row['id'] . "'><label for='tak{$row['id']}'> Tak</label></section>";
                echo "<section class='N'><input type='radio' value='N' id='nie{$row['id']}' name='podstawowe" . $row['id'] . "'><label for='nie{$row['id']}'> Nie</label></section>";
                echo "<span class='poprzednie' onclick='zmienPytanie(-1)'>Poprzednie</span>";
                echo "<span class='nastepne' onclick='zmienPytanie(1)'>Następne</span>";
                echo "</section>";
                array_push($listaIdPytan, $row['id']);
            }

            while ($row = mysqli_fetch_assoc($resultSpecjalistyczne)) {
                echo "<section class='pytanieSpecjalistyczne pytanie'>";
                echo "<p>Pytanie " . $row['id'] . " (id: " . $row['nr_pyt'] . ")</p>";
                echo "<p>" . $row['zakres'] . ": " . $row['pkt'] . " pkt.</p>";
                echo "<p>" . $row['pyt'] . "</p>";
                if (str_ends_with(strval($row['media']), ".jpg")) {
                    echo "<img src='media/" . $row['media'] . "'><br>";
                }
                else if (str_ends_with(strval($row['media']), ".mp4")) {
                    echo "<video controls><source src='media/" . $row['media'] . "'></video><br>";
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
                echo "<span class='poprzednie' onclick='zmienPytanie(-1)'>Poprzednie</span>";
                echo "<span class='nastepne' onclick='zmienPytanie(1)'>Następne</span>";
                echo "</section>";
                array_push($listaIdPytan, $row['id']);
            }

            mysqli_close($conn);
            $listaIdPytan = implode(",", $listaIdPytan);
            echo "<input type='hidden' name='listaIdPytan' value=$listaIdPytan>";
        ?>
        <input type="submit" id='koniec' value="Zakończ test" disabled>
    </form>
</body>
<script src="js.js">

</script>
</html>



