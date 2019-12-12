<html>
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }}</title>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{{ $key }}');
        stripe.redirectToCheckout({
            sessionId: '{{ $session_id }}'
        }).then(function (result) {
            // If `redirectToCheckout` fails due to a browser or network
            // error, display the localized error message to your customer
            Alert(result.error.message);
        });
    </script>
</head>
<body>
</body>
</html>