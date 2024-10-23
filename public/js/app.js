document.addEventListener('DOMContentLoaded', () => {

    const paymentMethodSelect = document.getElementById('payment-method');
    const cardPaymentDiv = document.getElementById('card-payment');
    const cashPaymentDiv = document.getElementById('cash-payment');
    const submitButton = document.getElementById('submit-btn');
    const responseField = document.getElementById('response');
    const totalField = document.getElementById('totalField');
    const selectedCoins = {};

    // Cambiar la interfaz según el método de pago seleccionado
    paymentMethodSelect.addEventListener('change', (event) => {
        if (event.target.value === 'card') {
            cardPaymentDiv.style.display = 'block';
            cashPaymentDiv.style.display = 'none';
            response.innerText = '';
        } else if (event.target.value === 'cash') {
            cardPaymentDiv.style.display = 'none';
            cashPaymentDiv.style.display = 'block';
            response.innerText = '';
        }
    });

    // Manejar la selección de billetes y monedas
    document.querySelectorAll('#bills-container img, #coins-container img').forEach(img => {
        img.addEventListener('click', () => {
            let value = img.getAttribute('data-value');

            // Si la moneda/billete ya está en el objeto, suma, sino, inicializa
            if (selectedCoins[value]) {
                selectedCoins[value]++;
            } else {
                selectedCoins[value] = 1;
            }

            updateTotal(); // Actualiza el total al seleccionar
            console.log(selectedCoins); // Para ver la construcción del JSON
        });
    });

    // Actualizar el total
    function updateTotal() {
        let total = 0;
        for (let value in selectedCoins) {
            total += value * selectedCoins[value];
        }
        totalField.innerText = `Total: ${(total / 100).toFixed(2)}€`; // Convertir céntimos a euros
    }

    // Enviar datos al hacer clic en el botón de enviar
    submitButton.addEventListener('click', () => {
        const amount = parseFloat(document.getElementById('amount').value) * 100; // Convertir a céntimos
        const paymentType = paymentMethodSelect.value; // Obtener tipo de pago

        // Crear el JSON para el envío
        let paymentData;
        if (paymentType === 'card') {
            const cardNum = document.getElementById('card_num').value;
            paymentData = { amount, currency: 'eur', card_num: cardNum };
        } else if (paymentType === 'cash') {
            paymentData = { amount, currency: 'eur', coin_types: selectedCoins };
        } else {
            alert('Por favor, selecciona un método de pago.');
            return;
        }

        // Enviar los datos a la API
        fetch('../api/payment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(paymentData)
        })
            .then(response => response.json())
            .then(data => {
                responseField.innerText = JSON.stringify(data, null, 2); // Mostrar respuesta
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

});

