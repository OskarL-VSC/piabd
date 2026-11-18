<?php
$host = 'localhost';
$user = 'root';
$pass = 'Admin';
$db   = 'szkola';

$wynikHtml = '';

if (isset($_POST['pokaz_krakow']) || isset($_POST['pokaz_wszystkich']) || isset($_POST['pokaz_pelnoletnich'])) {
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        $wynikHtml = '<p style="color:red;">Błąd połączenia z bazą: ' . $conn->connect_error . '</p>';
    } else {
        $conn->set_charset('utf8mb4');

        if (isset($_POST['pokaz_krakow'])) {
            $sql = "SELECT id, nazwisko, imie, data_urodzenia, miejscowosc, kod_pocztowy, email, telefon, hobby FROM uczniowie WHERE miejscowosc = 'Kraków'";
        } elseif (isset($_POST['pokaz_pelnoletnich'])) {
            $sql = "SELECT id, nazwisko, imie, data_urodzenia, miejscowosc, kod_pocztowy, email, telefon, hobby FROM uczniowie WHERE data_urodzenia <= DATE_SUB(CURDATE(), INTERVAL 18 YEAR)";
        } else {
            $sql = "SELECT id, nazwisko, imie, data_urodzenia, miejscowosc, kod_pocztowy, email, telefon, hobby FROM uczniowie";
        }

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $wynikHtml .= '<table>';
            $wynikHtml .= '<tr><th>ID</th><th>Imię</th><th>Nazwisko</th><th>Data urodzenia</th><th>Miejscowość</th><th>Kod pocztowy</th><th>Email</th><th>Telefon</th><th>Hobby</th></tr>';

            while ($row = $result->fetch_assoc()) {
                $wynikHtml .= '<tr>';
                $wynikHtml .= '<td>' . $row['id'] . '</td>';
                $wynikHtml .= '<td>' . $row['imie'] . '</td>';
                $wynikHtml .= '<td>' . $row['nazwisko'] . '</td>';
                $wynikHtml .= '<td>' . $row['data_urodzenia'] . '</td>';
                $wynikHtml .= '<td>' . $row['miejscowosc'] . '</td>';
                $wynikHtml .= '<td>' . $row['kod_pocztowy'] . '</td>';
                $wynikHtml .= '<td>' . $row['email'] . '</td>';
                $wynikHtml .= '<td>' . $row['telefon'] . '</td>';
                $wynikHtml .= '<td>' . $row['hobby'] . '</td>';
                $wynikHtml .= '</tr>';
            }
            $wynikHtml .= '</table>';
        } else {
            $wynikHtml = '<p>Brak wyników spełniających kryteria.</p>';
        }

        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Lista uczniów</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        button { padding: 10px 15px; font-size: 16px; cursor: pointer; margin-right: 10px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>

    <form method="POST">
        <button type="submit" name="pokaz_krakow">Pokaż osoby z Krakowa</button>
        <button type="submit" name="pokaz_wszystkich">Pokaż wszystkich</button>
        <button type="submit" name="pokaz_pelnoletnich">Pokaż osoby pełnoletnie</button>
    </form>

    <?= $wynikHtml ?>

</body>
</html>
