document.addEventListener('DOMContentLoaded', () => {
    let selectedCoins = {};

    /** Monedas */

    // Añadir event listeners a todas las imágenes
    document.querySelectorAll('#bills-container img, #coins-container img').forEach(img => {
        img.addEventListener('click', () => {
            let value = img.getAttribute('data-value');

            // Si la moneda/billete ya está en el objeto, suma, sino, inicializa
            if (selectedCoins[value]) {
                selectedCoins[value]++;
            } else {
                selectedCoins[value] = 1;
            }

            console.log(selectedCoins); // Para ver la construcción del JSON
        });
    });

    let coinQuantities = {}; // Para almacenar las cantidades de monedas/billetes seleccionadas
    let totalAmount = 0; // Para llevar el total acumulado

    const updateTotal = () => {
        const totalField = document.getElementById('totalField');
        totalField.textContent = "Total: " + (totalAmount / 100).toFixed(2) + "€"; // Mostrar en formato 0.00€
    };

    const handleCoinClick = (coinValue) => {
        if (coinQuantities[coinValue]) {
            coinQuantities[coinValue]++;
        } else {
            coinQuantities[coinValue] = 1;
        }

        totalAmount += coinValue; // Sumar el valor de la moneda/billete al total
        updateTotal(); // Actualizar el campo del total
    };

    // Escucha los clics en las imágenes de billetes y monedas
    document.querySelectorAll('.coin').forEach((coinElement) => {
        const coinValue = parseInt(coinElement.getAttribute('data-value'), 10);
        coinElement.addEventListener('click', () => handleCoinClick(coinValue));
    });

    // Enviar los datos al hacer clic en "Enviar Pago"
    document.getElementById('submit-btn').addEventListener('click', () => {
        let cardNum = document.getElementById('card_num').value;
        let amount = document.getElementById('amount').value;

        if (cardNum === "" || amount === "") {
            alert("Por favor, rellena todos los campos.");
            return;
        }

        /* LLAMADA API */

        // Crear el JSON
        let paymentData = {
            card_num: cardNum,
            amount: amount,
            coin_types: selectedCoins
        };

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
                console.log(data);
                //alert(`Respuesta: `+JSON.stringify(data));
                document.getElementById('response').innerText = JSON.stringify(data, null, 2);

            })
            .catch(error => {
                console.error('Error en la petición:', error);
                alert('Error en la petición');
            });
    });
});
