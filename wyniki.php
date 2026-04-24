<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wyniki egzaminu</title>
    <link rel="stylesheet" href="style_wyniki.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <h1>Wyniki egzaminu kategorii <?php echo $_POST['kategoria']; ?></h1>
    <?php
        $listaIdPytan = $_POST['listaIdPytan'];
        $numerPytania = 0;
        $conn = mysqli_connect("localhost", "root", "", "prawo_jazdy");
        $query = "SELECT id, pyt, poprawna, pkt, media, nr_pyt, zakres, odp_a, odp_b, odp_c
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
                echo "<p>Pytanie " . $row['id'] . " (id: " . $row['nr_pyt'] . ") " . $row['zakres'] . ": " . $row['pkt'] . " pkt.</p>";
                echo "<p>" . $row['pyt'] . "</p>";
                if (str_ends_with(strval($row['media']), ".jpg")) {
                    echo "<img src='media/" . $row['media'] . "' alt='media/" . $row['media'] . "'><br>";
                }
                else if (str_ends_with(strval($row['media']), ".mp4")) {
                    echo "<video controls><source src='media/" . $row['media'] . "' alt='media/" . $row['media'] . "'></video><br>";
                }
                echo "<h3>Poprawna odpowiedź</h3>";
                if ($row['poprawna'] == 'T') {
                    echo "<section class='T'><label for='tak{$row['id']}'> Tak</label></section>";
                }
                else if ($row['poprawna'] == 'N'){
                    echo "<section class='N'><label for='nie{$row['id']}'> Nie</label></section>";
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

                echo "<h3>Twoja odpowiedź</h3>";
                echo "<section class='$odpowiedz'><label>$odpowiedz</label></section>";
              
                if ($odpowiedz == $row['poprawna']) {
                    echo ". {$row['pkt']} pkt.<br>";
                    $pkt += intval($row['pkt']);
                } else {
                    echo ". 0 pkt.<br>";
                }

            } else {
                echo "<p>Pytanie " . $row['id'] . " (id: " . $row['nr_pyt'] . ") " . $row['zakres'] . ": " . $row['pkt'] . " pkt.</p>";
                echo "<p>" . $row['pyt'] . "</p>";
                if (str_ends_with(strval($row['media']), ".jpg")) {
                    echo "<img src='media/" . $row['media'] . "' alt='media/" . $row['media'] . "'><br>";
                }
                else if (str_ends_with(strval($row['media']), ".mp4")) {
                    echo "<video controls><source src='media/" . $row['media'] . "' alt='media/" . $row['media'] . "'></video><br>";
                }

                echo "<h3>Poprawna odpowiedź</h3>";

                if ($row['poprawna'] == 'T') {
                    echo "<section class='T'><label for='tak{$row['id']}'> Tak</label></section>";
                }
                else if ($row['poprawna'] == 'N'){
                    echo "<section class='N'><label for='nie{$row['id']}'> Nie</label></section>";
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
                                
                echo "<h3>Twoja odpowiedź</h3>";
                echo "<section class='brak'><label>Brak</label></section>";

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
</body>
</html>
