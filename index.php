<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prawo jazdy - baza pytań teoretycznych</title>
</head>
<body>
    <form action="test.php" method="get">
        <label for="jezyk">Wybierz język testu:</label>
        <select name="jezyk" id="jezyk">
            <option value="pl">polski</option>
            <option value="en">english</option>
            <option value="de">deutsch</option>
            <option value="ua">українська</option>
        </select>
        <br>
        <label for="kategoria">Wybierz kategorię prawa jazdy:</label>
        <select name="kategoria" id="kategoria">
            <option value="B">B</option>
        </select>
        <br>
        <input type="submit" value="Rozpocznij test">
    </form>

</body>
</html>