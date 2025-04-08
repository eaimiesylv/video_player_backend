<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pay with PayPal</title>
    <script src="https://www.paypal.com/sdk/js?client-id=ATraiYYOq_uwHvTr5FRuthm_Yqyli0RiE177BIHHtt04hQagzOxX5W-XV9N8i3YoXucAk1fcoLp2N5k5"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="paypal-button-container"></div>

    <script>
        $(document).ready(function() {
            // get the packageid and appointmend id from the backend. This  optional
            $.get('/api/v1/test-data').done(function(response) {
               // const appointmentId = response.appointment_id;
            const appointmentId = "9c9d5bee-9d7d-4da2-b187-1824447120c7";
                //const packageId = response.package_id;
               //hardcode value
                const paymentMetaData = [
                    { agent_id: '862f24ad-9e93-4567-bc9a-ffb68588c206', amount: 550 },
                    { agent_id: '8bdd386e-13ba-4e00-9d98-fc1fda8bcf67', amount: 550 }
                ];
                // Render the PayPal button with the fetched data
                paypal.Buttons({
                    createOrder: function(data, actions) {
                        // Call your backend to initiate the payment
                        return fetch('/api/v1/initiate-web-payment', {
                            method: 'post',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                appointment_id: appointmentId,
                                payment_meta_data:""
                            })
                        }).then(function(res) {
                            return res.json();
                        }).then(function(data) {
                            // Return the order ID from your backend
                            return data.orderID;
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                            // Inform your backend about the completed payment
                            $.post('/api/v1/web-payment', {
                                orderID: data.orderID
                            }).done(function(response) {
                                alert('Transaction successful')
                                console.log(response.data);
                            }).fail(function(err) {
                                alert('Transaction could not be completed. Please try again.');
                            });
                        });
                    }
                }).render('#paypal-button-container');
            }).fail(function(err) {
                alert('Failed to fetch data. Please try again.');
            });
        });
    </script>
</body>
</html>
