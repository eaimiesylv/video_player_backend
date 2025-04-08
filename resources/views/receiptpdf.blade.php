<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Appointment Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
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
    </style>
</head>
<body>

    <!-- Header with Company Logo and Invoice ID -->
    <div class="receipt-header">
        {{-- <img src="{{ asset('laraveldaily.png') }}" alt="Company Logo" /> --}}
        <h2>Appointment Receipt</h2>
        <p><strong>Receipt ID:</strong> {{ $data[0]['id'] ?? 'N/A' }}</p>
    </div>

    <!-- Customer and Company Details -->
    <table class="receipt-table">
        <tr>
            <td>
                <h3>Customer Details</h3>
                <p><strong>Name</strong>: {{ $data[0]['customers']['name'] ?? 'N/A' }}</p>
                <p><strong>Email</strong>: {{ $data[0]['customers']['email'] ?? 'N/A' }}</p>
                <p><strong>Phone Number</strong>: {{ $data[0]['customers']['phone_number'] ?? 'N/A' }}</p>
                <p><strong>Address</strong>: {{ $data[0]['address'] ?? 'N/A' }}</p>
            </td>
            <td>
                <h3>Franchisee Details</h3>
                <p><strong>Name</strong>: {{ $data[0]['franchisee']['company_name'] ?? 'N/A' }}</p>
                <p><strong>Email</strong>: {{ $data[0]['franchisee']['company_email_address'] ?? 'N/A' }}</p>
                <p><strong>Phone Number</strong>: {{ $data[0]['franchisee']['company_phone_number'] ?? 'N/A' }}</p>
                <p><strong>Address</strong>: {{ $data[0]['franchisee']['company_address'] ?? 'N/A' }}</p>
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
                <td><strong>Appointment Date:</strong> {{ $data[0]['appointment_date'] ?? 'N/A' }}</td>
                <td><strong>Service Name:</strong> {{ $data[0]['service_name'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Package Name:</strong> {{ $data[0]['packages']['package_name'] ?? 'N/A' }}</td>
                <td><strong>Package Duration:</strong> {{ $data[0]['packages']['package_duration'] ?? 'N/A' }} hours</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Package Cost:</strong> {{ $data[0]['packages']['package_price'] ?? 'N/A' }}</td>
               
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
                $packagePrice = $data[0]['packages']['package_price'] ?? 0;
                $discountPercentage = $data[0]['packages']['discount_percentage'] ?? null;
                $discountAmount = $discountPercentage ? ($packagePrice * $discountPercentage / 100) : 0;
               
                $firstPayment = $packagePrice /=2;
                $secondPayment = 0;

                if (isset($data[0]['is_complete']) && $data[0]['is_complete'] == 1) {
                    $secondPayment = $firstPayment - $discountAmount;
                }

              

                // Initialize total addon cost
                $totalAddonCost = 0;
                // Loop through addon_quote to calculate total cost only if the addon is accepted
                if (isset($data[0]['addon_quote'])) {
                    foreach ($data[0]['addon_quote'] as $addon) {
                        if (isset($addon['is_addon_accepted']) && $addon['is_addon_accepted'] == 1) {
                            $totalAddonCost += $addon['car_cost'] ?? 0;
                        }
                    }
                }

                // Calculate total price including addons
                $totalPrice = $firstPayment + $secondPayment + $totalAddonCost;
            @endphp

            <tr>
                <td>First Payment</td>
                <td>${{ number_format($firstPayment, 2) }}</td>
            </tr>
            @if($data[0]['is_complete'] == 1)
                <tr>
                    <td>Second Payment</td>
                    <td>${{ number_format($secondPayment, 2) }}</td>
                </tr>
            @endif

            @if($discountPercentage && $data[0]['is_complete'] == 1)
                <tr>
                    <td>Discount on Second Payment ({{ $discountPercentage }}%)</td>
                    <td>-${{ number_format($discountAmount, 2) }}</td>
                </tr>
            @endif

            <!-- Addon Services -->
            @if(!empty($data[0]['addon_quote']))
                <tr>
                    <td colspan="2"><strong>Addon Services:</strong></td>
                </tr>
                @foreach($data[0]['addon_quote'] as $addon)
                    @if(isset($addon['is_addon_accepted']) && $addon['is_addon_accepted'] == 1)
                        <tr>
                            <td>{{ $addon['addon_service'] ?? 'N/A' }}</td>
                            <td>${{ number_format($addon['car_cost'] ?? 0, 2) }}</td>
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
        <p><strong>Appointment Status:</strong> {{ isset($data[0]['is_complete']) && $data[0]['is_complete'] == 1 ? 'Completed' : 'Booked' }}</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}  </p>
    </div>

</body>
</html>
