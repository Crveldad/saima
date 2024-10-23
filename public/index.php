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
        <label for="payment-method">Método de Pago:</label>
        <select id="payment-method" name="payment-method" required>
            <option value="" disabled selected>Selecciona un método</option>
            <option value="card">Tarjeta</option>
            <option value="cash">Efectivo</option>
        </select><br><br>

        <div id="card-payment" style="display:none;">
            <label for="card_num" id="card-label">Número de tarjeta:</label>
            <input type="text" id="card_num" name="card_num" required><br><br>
        </div>

        <label for="amount">Cantidad a pagar:</label>
        <input type="number" id="amount" name="amount" placeholder="Ej: 54.75" required><br><br>

        <div id="cash-payment" style="display:none;">
            <h3>Selecciona los billetes:</h3>
            <div id="bills-container">
                <img class="coin" src="images/500euro.png" alt="Billete de 500 euros" data-value="50000">
                <img class="coin" src="images/200euro.png" alt="Billete de 200 euros" data-value="20000">
                <img class="coin" src="images/100euro.png" alt="Billete de 100 euros" data-value="10000">
                <img class="coin" src="images/50euro.png" alt="Billete de 50 euros" data-value="5000">
                <img class="coin" src="images/20euro.png" alt="Billete de 20 euros" data-value="2000">
                <img class="coin" src="images/10euro.png" alt="Billete de 10 euros" data-value="1000">
                <img class="coin" src="images/5euro.png" alt="Billete de 5 euros" data-value="500">
            </div>

            <h3>Selecciona las monedas:</h3>
            <div id="coins-container">
                <img class="coin" src="images/2euro.png" alt="Moneda de 2 euros" data-value="200">
                <img class="coin" src="images/1euro.png" alt="Moneda de 1 euro" data-value="100">
                <img class="coin" src="images/50cent.png" alt="Moneda de 50 céntimos" data-value="50">
                <img class="coin" src="images/20cent.png" alt="Moneda de 20 céntimos" data-value="20">
                <img class="coin" src="images/10cent.png" alt="Moneda de 10 céntimos" data-value="10">
                <img class="coin" src="images/5cent.png" alt="Moneda de 5 céntimos" data-value="5">
                <img class="coin" src="images/2cent.png" alt="Moneda de 2 céntimos" data-value="2">
                <img class="coin" src="images/1cent.png" alt="Moneda de 1 céntimos" data-value="1">
            </div>

            <p id="totalField">Total: 0.00€</p>
        </div>

        <div class="response">
            <pre id="response"></pre>
        </div>

        <button type="button" id="submit-btn">Enviar Pago</button>
    </form>

    <script src="js/app.js"></script>
</body>

</html>
