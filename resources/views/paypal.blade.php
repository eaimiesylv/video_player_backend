<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pay with PayPal</title>
    <script src="https://www.paypal.com/sdk/js?client-id=ATraiYYOq_uwHvTr5FRuthm_Yqyli0RiE177BIHHtt04hQagzOxX5W-XV9N8i3YoXucAk1fcoLp2N5k5"></script>
</head>
<body>
    <div id="paypal-button-container"></div>

    <script>
        function setupPayPalButton(appointmentId, paymentMetaData) {
            console.log("Setting up PayPal button with:", appointmentId, paymentMetaData);
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return fetch('/api/v1/initiate-web-payment', {
                        method: 'post',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            appointment_id: appointmentId,
                            payment_meta_data: paymentMetaData
                        })
                    }).then(function(res) {
                        return res.json();
                    }).then(function(data) {
                        return data.orderID;
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        fetch('/api/v1/web-payment', {
                            method: 'post',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                orderID: data.orderID
                            })
                        }).then(function(response) {
                            return response.json();
                        }).then(function(response) {
                            // Notify React Native about the success
                            if (window.ReactNativeWebView) {
                                window.ReactNativeWebView.postMessage(JSON.stringify({
                                    status: 'success',
                                    data: details, // You can send more data as needed
                                }));
                            }
                        }).catch(function(err) {
                            // Notify React Native about the failure
                            if (window.ReactNativeWebView) {
                                window.ReactNativeWebView.postMessage(JSON.stringify({
                                    status: 'failed',
                                    message: 'Transaction could not be completed. Please try again.'
                                }));
                            }
                        });
                    });
                }
            }).render('#paypal-button-container');
        }

        // Listener for messages from React Native
        window.addEventListener('message', function(event) {
            console.log("Received message from React Native:", event.data);
            try {
                var data = JSON.parse(event.data);
                if (data.appointmentId && data.paymentMetaData) {
                    setupPayPalButton(data.appointmentId, data.paymentMetaData);
                }
            } catch (error) {
                console.error('Error parsing message from React Native:', error);
            }
        });

        // For testing purposes
        // window.postMessage(JSON.stringify({
        //     appointmentId: "9c72388e-aa6f-4419-9979-d8b544711a65",
        //     paymentMetaData: [
        //         { agent_id: '862f24ad-9e93-4567-bc9a-ffb68588c206', amount: 550 },
        //         { agent_id: '8bdd386e-13ba-4e00-9d98-fc1fda8bcf67', amount: 550 }
        //     ]
        // }), "*");
    </script>
</body>
</html>
