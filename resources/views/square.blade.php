<head>
  <link rel="stylesheet" href="/reference/sdks/web/static/styles/code-preview.css" preload>
  <script src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
</head>
<body>
<div id="payment-form">
  <div id="payment-status-container"></div>
  <div id="card-container"></div>
  <button id="card-button" type="button">Pay</button>
</div>
  <script type="module">
    const payments = Square.payments('sandbox-sq0idb-Q5OqMxTI_yL1_EIXKJP9bA', 'LDBZJ5DTXSW02');
    const card = await payments.card();
    await card.attach('#card-container');

    const cardButton = document.getElementById('card-button');
    cardButton.addEventListener('click', async () => {
      const statusContainer = document.getElementById('payment-status-container');

      try {
        const result = await card.tokenize();
        if (result.status === 'OK') {
          console.log(`Payment token is ${result.token}`);
          statusContainer.innerHTML = "Payment Successful";
        } else {
          let errorMessage = `Tokenization failed with status: ${result.status}`;
          if (result.errors) {
            errorMessage += ` and errors: ${JSON.stringify(
              result.errors
            )}`;
          }

          throw new Error(errorMessage);
        }
      } catch (e) {
        console.error(e);
        statusContainer.innerHTML = "Payment Failed";
      }
    });
  </script>
</body>