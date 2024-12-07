


<!DOCTYPE html>
<html>
<head>
  <script src="https://www.paypal.com/sdk/js?client-id=ATFcfGtAaKU5MsB6ri6ih-THk-IZ1rRnVlSNbWXgznvOLH5JtO0g0qrMH7cG2tYIzyKVvAfVwzFLRDbY"></script>
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
  <script>
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
          window.location.href = 'https://dashboard.bestassignmentwriters.co.uk/success'; 
        });
      },
      onCancel: function(data) {
        window.location.href = 'https://dashboard.bestassignmentwriters.co.uk/cancel'; 
      }
    }).render('#paypal-button-container');
  </script>
</body>
</html>
