<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Pago</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <h1>Formulario de Pago</h1>

    <form id="payment-form">
        <label for="card_num">Número de tarjeta:</label>
        <input type="text" id="card_num" name="card_num" required><br><br>

        <label for="amount">Cantidad a pagar:</label>
        <input type="number" id="amount" name="amount" required><br><br>

        <h3>Selecciona los billetes:</h3>
        <div id="bills-container">
            <img class="coin" src="images/500euro.png" alt="Billete de 500 euros" id="bill500" data-value="50000">
            <img class="coin" src="images/200euro.png" alt="Billete de 200 euros" id="bill200" data-value="20000">
            <img class="coin" src="images/100euro.png" alt="Billete de 100 euros" id="bill100" data-value="10000">
            <img class="coin" src="images/50euro.png" alt="Billete de 50 euros" id="bill50" data-value="5000">
            <img class="coin" src="images/20euro.png" alt="Billete de 20 euros" id="bill20" data-value="2000">
            <img class="coin" src="images/10euro.png" alt="Billete de 10 euros" id="bill10" data-value="1000">
            <img class="coin" src="images/5euro.png" alt="Billete de 5 euros" id="bill5" data-value="500">
        </div>

        <h3>Selecciona las monedas:</h3>
        <div id="coins-container">
            <img class="coin" src="images/2euro.png" alt="Moneda de 2 euros" id="coin200" data-value="200">
            <img class="coin" src="images/1euro.png" alt="Moneda de 1 euro" id="coin100" data-value="100">
            <img class="coin" src="images/50cent.png" alt="Moneda de 50 céntimos" id="coin50" data-value="50">
            <img class="coin" src="images/20cent.png" alt="Moneda de 20 céntimos" id="coin20" data-value="20">
            <img class="coin" src="images/10cent.png" alt="Moneda de 10 céntimos" id="coin10" data-value="10">
            <img class="coin" src="images/5cent.png" alt="Moneda de 5 céntimos" id="coin5" data-value="5">
            <img class="coin" src="images/2cent.png" alt="Moneda de 2 céntimos" id="coin2" data-value="2">
            <img class="coin" src="images/1cent.png" alt="Moneda de 1 céntimos" id="coin1" data-value="1">
        </div>

        <p id="totalField">Total: 0.00€</p>
        <div class="response">
            <pre id="response">

            </pre>
        </div>

        <button type="button" id="submit-btn">Enviar Pago</button>
    </form>

    <script src="js/app.js"></script>
</body>

</html>