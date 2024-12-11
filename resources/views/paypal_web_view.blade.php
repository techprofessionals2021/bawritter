<!DOCTYPE html>
<html>
<head>
  <script>
    // Fetch PayPal configuration dynamically
    async function loadPayPalSdk() {
      try {
        const response = await fetch('api/paypal/config');
        const config = await response.json();

        // Dynamically load PayPal SDK with client ID
        const script = document.createElement('script');
        script.src = `https://www.paypal.com/sdk/js?client-id=${config.client_id}`;
        script.onload = () => initializePayPalButtons(config);
        document.head.appendChild(script);
      } catch (error) {
        console.error('Error loading PayPal SDK:', error);
      }
    }

    function initializePayPalButtons(config) {
      const urlParams = new URLSearchParams(window.location.search);
      const amount = urlParams.get('amount') || '10.00'; // Default amount or fetch from URL

      paypal.Buttons({
        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: amount
              }
            }]
          });
        },
        onApprove: function(data, actions) {
          return actions.order.capture().then(function(details) {
            window.location.href = config.success_url; // Redirect to success URL
          });
        },
        onCancel: function(data) {
          window.location.href = config.cancel_url; // Redirect to cancel URL
        }
      }).render('#paypal-button-container');
    }

    // Load PayPal SDK and initialize buttons
    loadPayPalSdk();
  </script>
</head>
<style>
  .container {
    margin: 10vh 10vw 1vh 10vw;  
  }
</style>
<body>
  <div class="container">
    <div id="paypal-button-container"></div>
  </div>
</body>
</html>
