document.addEventListener('DOMContentLoaded', () => {

    const paymentMethodSelect = document.getElementById('payment-method');
    const cardPaymentDiv = document.getElementById('card-payment');
    const cashPaymentDiv = document.getElementById('cash-payment');
    const submitButton = document.getElementById('submit-btn');
    const responseField = document.getElementById('response');
    const totalField = document.getElementById('totalField');
    const selectedCoins = {};

    // cambia la interfaz según el método de pago seleccionado
    paymentMethodSelect.addEventListener('change', (event) => {
        if (event.target.value === 'card') {
            cardPaymentDiv.style.display = 'block';
            cashPaymentDiv.style.display = 'none';
            responseField.innerText = '';
        } else if (event.target.value === 'cash') {
            cardPaymentDiv.style.display = 'none';
            cashPaymentDiv.style.display = 'block';
            responseField.innerText = '';
        }
    });

    // maneja la selección de billetes y monedas
    document.querySelectorAll('#bills-container img, #coins-container img').forEach(img => {
        img.addEventListener('click', () => {
            let value = img.getAttribute('data-value');

            // si la moneda/billete ya está en el objeto, suma, sino, inicializa
            if (selectedCoins[value]) {
                selectedCoins[value]++;
            } else {
                selectedCoins[value] = 1;
            }

            updateTotal(); // actualiza el total al seleccionar
            console.log(selectedCoins);
        });
    });

    function updateTotal() {
        let total = 0;
        for (let value in selectedCoins) {
            total += value * selectedCoins[value];
        }
        totalField.innerText = "Total: " + (total / 100).toFixed(2) + "€"; // convierte céntimos a euros
    }

    // envía datos al darle en el botón de enviar
    submitButton.addEventListener('click', () => {
        const amount = parseFloat(document.getElementById('amount').value) * 100; // convierte a céntimos
        const paymentType = paymentMethodSelect.value; // obtener tipo de pago

        // crea el JSON para el envío
        let paymentData;
        if (paymentType === 'card') {
            const cardNum = document.getElementById('card_num').value;
            paymentData = { amount, currency: 'eur', card_num: cardNum };
        } else if (paymentType === 'cash') {
            paymentData = { amount, currency: 'eur', coin_types: selectedCoins };
        } else {
            alert("Por favor, selecciona un método de pago.");
            return;
        }

        // envía los datos a la API
        fetch('../api/payment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(paymentData)
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    if (paymentType === 'cash') {
                        // si es un pago en efectivo, mostramos el cambio
                        const coinTypesString = JSON.stringify(data.change.coin_types);
                        responseField.innerText = "Pago exitoso. Cambio: " + (data.change.amount/100) + "€. " + coinTypesString + ". ID Transacción: " + data.transaction_id.id;
                    } else {
                        // si es un pago con tarjeta
                        responseField.innerText = "Pago con tarjeta exitoso. ID Transacción: " + data.transaction_id.id;
                    }
                } else {
                    responseField.innerText = "Error: " + data.message;
                }
            })
            .catch(error => {
                console.error("Error:", error);
                responseField.innerText = "Hubo un error al procesar el pago.";
            });
    });

});
