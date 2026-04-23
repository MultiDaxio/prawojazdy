<?php
    $listaIdPytan = $_POST['listaIdPytan'];
    $numerPytania = 0;
    $conn = mysqli_connect("localhost", "root", "", "prawo_jazdy");
    $query = "SELECT id, pyt, poprawna, pkt 
              FROM pula_pytan
              WHERE id IN ($listaIdPytan)
              ORDER BY FIELD(id, $listaIdPytan)";
    $result = mysqli_query($conn, $query);
    $ktorePytanie = "";
    $pkt = 0;
    while($row = mysqli_fetch_assoc($result)) {

        if ($numerPytania < 20) {
            $ktorePytanie = "podstawowe" . $row['id'];
        } else {
            $ktorePytanie = "specjalistyczne" . $row['id'];
        }

        echo $ktorePytanie. " ";

        $numerPytania++;

        if (isset($_POST[$ktorePytanie])) {
            $odpowiedz = $_POST[$ktorePytanie];
            echo "<strong>$odpowiedz</strong>";

            echo " Id pytania: {$row['id']}, treść: {$row['pyt']}. Poprawna odpowiedź: {$row['poprawna']}, twoja odpowiedź: $odpowiedz";

            if ($odpowiedz == $row['poprawna']) {
                echo ". {$row['pkt']} pkt.<br>";
                $pkt += intval($row['pkt']);
            } else {
                echo ". 0 pkt.<br>";
            }

        } else {
            echo "Id pytania: {$row['id']}, treść: {$row['pyt']}. Poprawna odpowiedź: {$row['poprawna']}, twoja odpowiedź: brak. 0 pkt.<br>";
        }
    }
    echo "<br> Uzyskano $pkt/74 pkt.<br>";
    if ($pkt >= 68) {
        echo "<br> Egzamin zdany! Gratulacje!<br>";
    }
    mysqli_close($conn);
    echo "<a href='index.php'>Powrót na stronę główną</a>";
?>