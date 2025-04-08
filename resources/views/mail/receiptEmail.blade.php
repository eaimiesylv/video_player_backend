<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Appointment Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #EDF2F7;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .receipt-header, .receipt-footer {
            text-align: center;
        }
        .receipt-header img {
            max-width: 150px;
        }
        .receipt-table, .addon-table, .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .receipt-table td, .receipt-table th, .addon-table td, .addon-table th, .payment-table td, .payment-table th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .receipt-table th, .addon-table th, .payment-table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .receipt-footer {
            margin-top: 40px;
        }
        h3 {
            color: #333;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with Company Logo and Invoice/Receipt ID -->
        <div class="receipt-header">
            <img src="{{ config('app.url') }}/logo.png" alt="Company Logo" />
            <h2>Appointment Receipt</h2>
            <p><strong>{{ isset($emailData['is_complete']) && $emailData['is_complete'] < 0 ? 'Invoice' : 'Receipt' }} ID:</strong> {{ $emailData['id'] ?? 'N/A' }}</p>
        </div>

        <!-- Salutation and Message Based on Appointment Status -->
        <div class="salutation">
            <p>
                {{ isset($emailData['customers']['name']) ? 'Hello ' . $emailData['customers']['name'] . ',' : 'Hello,' }}
                <br>
                {{
                    isset($emailData['is_complete'])
                    ? ($emailData['is_complete'] == -1
                        ? 'Thank you for booking an appointment with us. Below are your appointment details.'
                        : ($emailData['is_complete'] == 0
                            ? 'This is to inform you that we have received the first 50% of your part payment. Below are your payment details.'
                            : 'This is to inform you that you have completed the final payment of your appointment booking. Below are your payment details.'))
                    : 'Below are your appointment details.'
                }}
            </p>
        </div>

        <!-- Customer and Company Details -->
        <table class="receipt-table">
            <tr>
                <td>
                    <h3>Customer Details</h3>
                    <p><strong>Name</strong>: {{ $emailData['customers']['name'] ?? 'N/A' }}</p>
                    <p><strong>Email</strong>: {{ $emailData['customers']['email'] ?? 'N/A' }}</p>
                    <p><strong>Phone Number</strong>: {{ $emailData['customers']['phone_number'] ?? 'N/A' }}</p>
                    <p><strong>Address</strong>: {{ $emailData['address'] ?? 'N/A' }}</p>
                </td>
                <td>
                    <h3>Franchisee Details</h3>
                    <p><strong>Name</strong>: {{ $emailData['franchisee']['company_name'] ?? 'N/A' }}</p>
                    <p><strong>Email</strong>: {{ $emailData['franchisee']['company_email_address'] ?? 'N/A' }}</p>
                    <p><strong>Phone Number</strong>: {{ $emailData['franchisee']['company_phone_number'] ?? 'N/A' }}</p>
                    <p><strong>Address</strong>: {{ $emailData['franchisee']['company_address'] ?? 'N/A' }}</p>
                </td>
            </tr>
        </table>

        <!-- Appointment Details -->
        <table class="receipt-table">
            <thead>
                <tr>
                    <th colspan="2">Appointment Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Appointment Date:</strong> {{ $emailData['appointment_date'] ?? 'N/A' }}</td>
                    <td><strong>Service Name:</strong> {{ $emailData['service_name'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Package Name:</strong> {{ $emailData['packages']['package_name'] ?? 'N/A' }}</td>
                    <td><strong>Package Duration:</strong> {{ $emailData['packages']['package_duration'] ?? 'N/A' }} hours</td>
                </tr>
            </tbody>
        </table>

        <!-- Payment Breakdown -->
        <h3>Payment Details</h3>
        <table class="payment-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <!-- Service Payment Calculation -->
                @php
                    // Calculate service payment based on appointment status and discount
                    $packagePrice = $emailData['packages']['package_price'] ?? 0;
                    $discountPercentage = $emailData['packages']['discount_percentage'] ?? null;
                    $discountAmount = $discountPercentage ? ($packagePrice * $discountPercentage / 100) : 0;
                    $servicePayment = $packagePrice - $discountAmount;

                    // Adjust payment if appointment is incomplete
                    if (isset($emailData['is_complete']) && $emailData['is_complete'] == 0) {
                        $servicePayment /= 2;
                    }

                    // Initialize total addon cost
                    $totalAddonCost = 0;
                    // Loop through addon_quote to calculate total cost only if the addon is accepted
                    if (isset($emailData['addon_quote'])) {
                        foreach ($emailData['addon_quote'] as $addon) {
                            if (isset($addon['is_addon_accepted']) && $addon['is_addon_accepted'] == 1) {
                                $totalAddonCost += $addon['car_cost'] ?? 0;
                            }
                        }
                    }

                    // Calculate total price including addons
                    $totalPrice = $servicePayment + $totalAddonCost;
                @endphp

                <tr>
                    <td>Service Payment</td>
                    <td>${{ number_format($servicePayment, 2) }}</td>
                </tr>

                @if($discountPercentage)
                    <tr>
                        <td>Discount Applied ({{ $discountPercentage }}%)</td>
                        <td>-${{ number_format($discountAmount, 2) }}</td>
                    </tr>
                @endif

                <!-- Addon Services -->
                @if(!empty($emailData['addon_quote']))
                    <tr>
                        <td colspan="2"><strong>Addon Services:</strong></td>
                    </tr>
                    @foreach($emailData['addon_quote'] as $addon)
                        @if(isset($addon['is_addon_accepted']))
                            <tr>
                                <td>{{ $addon['addon_service'] ?? 'N/A' }}</td>
                                <td>{{ isset($addon['car_cost']) ? '$' . number_format($addon['car_cost'], 2) : '' }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endif

                <!-- Total Payment -->
                <tr>
                    <td><strong>Total Amount</strong></td>
                    <td><strong>${{ number_format($totalPrice, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Footer with Generated Time and Status -->
		<div class="receipt-footer">
			<p><strong>Generated at:</strong> {{ \Carbon\Carbon::now()->toDateTimeString() }}</p>
			<p><strong>Appointment Status:</strong> 
				{{
					isset($emailData['is_complete']) 
					? ($emailData['is_complete'] == -1 ? 'Created' : ($emailData['is_complete'] == 0 ? 'Booked' : 'Completed')) 
					: ''
				}}
			</p>
			<p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
		</div>
		
    </div>
</body>
</html>
