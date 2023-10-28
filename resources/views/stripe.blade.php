<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

       
        <style>
           
        </style>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
        <form action="#" method="post" id="payment-form">
            <div class="form-row">
                <label for="card-element">
                Credit or debit card
                </label>
                <div id="card-element">
                <!-- A Stripe Element will be inserted here. -->
                </div>

                <!-- Used to display Element errors. -->
                <div id="card-errors" role="alert"></div>
            </div>

            <button>Submit Payment</button>
            </form>
           
        </div>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js" integrity="sha512-LUKzDoJKOLqnxGWWIBM4lzRBlxcva2ZTztO8bTcWPmDSpkErWx0bSP4pdsjNH8kiHAUPaT06UXcb+vOEZH+HpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
    
    var stripe = Stripe('pk_test_51MpCKJACe4fzyuYTJ7dhX6C5O2cMlxTseYRQlWL74jeAvYTg1TL9nU9shH0tNydkvLh4YRomOb4eG11M08SI9yCI00qCMUvVDY');
   
   const elements = stripe.elements();
   const style = {
    base: {
    // Add your base input styles here. For example:
            fontSize: '16px',
            color: '#32325d',
        },
        };

        // Create an instance of the card Element.
        const card = elements.create('card', {style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Create a token or display an error when the form is submitted.
        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const {token, error} = await stripe.createToken(card);

        if (error) {
            // Inform the customer that there was an error.
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
        } else {
            // Send the token to your server.
            console.log('token',token)
            stripeTokenHandler(token);
        }
        
        });

        const stripeTokenHandler = (token) => {
        // Insert the token ID into the form so it gets submitted to the server
       // const form = document.getElementById('payment-form');
       // const hiddenInput = document.createElement('input');
       // hiddenInput.setAttribute('type', 'hidden');
       // hiddenInput.setAttribute('name', 'stripeToken');
       // hiddenInput.setAttribute('value', token.id);
       // form.appendChild(hiddenInput);

        // Submit the form
       // form.submit();
       console.log(token)
      //  axios.post('/api/reservations/4/payments', {
      //      token_stripe: token.id,
      //      payment_type: 'Credit Card',
      //  })
      //  .then(function (response) {
      //      console.log(response);
      //  })
      //  .catch(function (error) {
      //      console.log(error);
      //  });
        
        }
    </script>
    </body>

</html>
