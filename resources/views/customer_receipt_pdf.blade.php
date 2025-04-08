<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Ensure table fills the width */
            word-wrap: break-word; /* Wrap long content within cells */
        }
        .receipt-table th, .receipt-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .receipt-table th { background-color: #f2f2f2; text-align: center; }
    </style>
</head>
<body>
  
    <h2 style="text-align: center;">
        {{ isset($title['franchisee_id']) 
            ? "{$data[0]['company_name']} Appointment Report" 
            : "Appointment Report" 
        }}
        @if(isset($title['start_date']) && isset($title['end_date']))
            between 
            {{ \Carbon\Carbon::parse($title['start_date'])->format('d/m/Y') }} - 
            {{ \Carbon\Carbon::parse($title['end_date'])->format('d/m/Y') }}
        @endif
    </h2>
    
    {{-- {{ print_r($data, true) }}  --}}
    {{-- {{ var_dump($data) }} --}}
    <table class="receipt-table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Franchisee Name</th>
                <th>Package Name</th>
                <th>Amt($)</th>
                <th>Discount</th>
                <th>Addon Service</th>
                <th>Date</th>
                <th>1st  50%</th>
                <th>2nd 50%</th>
                <th>Total Price</th>
               
            </tr>
        </thead>
        <tbody>
            @php $totalFirst = $totalSecond = $totalOverall = 0; @endphp <!-- Initialize totals -->
            @foreach ($data as $index => $receipt)
                @php
                    // Serial number
                    $serialNumber = $index + 1;
        
                    // Calculate 1st 50%
                    $packagePrice = $receipt['package_price'] ?? 0;
                    $discountPercentage = $receipt['discount_percentage'] ?? 0;
                    $discountAmount = $packagePrice * ($discountPercentage / 100);
                    $discountedPrice = $packagePrice - $discountAmount;
                    $first50 = isset($receipt['appointmentlog'][0]['status']) ? $discountedPrice / 2 : 0;
        
                    // Calculate 2nd 50%
                    $addonCostTotal = $receipt['addon_quote']->sum('cost');
                    $second50 = isset($receipt['appointmentlog'][1]['status']) ? $first50 + $addonCostTotal : 0;
        
                    // Calculate total price for the row
                    $rowTotal = $first50 + $second50;
        
                    // Accumulate totals
                    $totalFirst += $first50;
                    $totalSecond += $second50;
                    $totalOverall += $rowTotal;
                @endphp
        
                <tr>
                    <td>{{ $serialNumber }}</td>
                    <td>{{ $receipt['customers']['name'] ?? 'N/A' }}</td>
                    <td>{{ $receipt['customers']['email'] ?? 'N/A' }}</td>
                    <td>{{ $receipt['customers']['phone_number'] ?? 'N/A' }}</td>
                    <td>{{ $receipt['address'] ?? 'N/A' }}</td>
                    <td>{{ $receipt['company_name'] ?? 'N/A' }}</td>
                    <td>{{ $receipt['package_name'] ?? 'N/A' }}</td>
                    <td>{{ $packagePrice }}</td>
                    <td>{{ $discountPercentage }}%</td>
                    <td>
                        {!! nl2br(e(implode("\n", $receipt['addon_quote']->map(function ($addon) {
                            return "{$addon['addon_name']} - $" . $addon['cost'];
                        })->all()))) !!}
                    </td>
                    <td>{{ $receipt['Date/Time'] ?? now()->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $first50 > 0 ? '$' . number_format($first50, 2) : '' }}</td>
                    <td>{{ $second50 > 0 ? '$' . number_format($second50, 2) : '' }}</td>
                    <td>{{ '$' . number_format($rowTotal, 2) }}</td>
                </tr>
            @endforeach
        
            <!-- Total row -->
            <tr>
                <td colspan="11"><strong>Total</strong></td>
                <td>{{ '$' . number_format($totalFirst, 2) }}</td>
                <td>{{ '$' . number_format($totalSecond, 2) }}</td>
                <td>{{ '$' . number_format($totalOverall, 2) }}</td>
            </tr>
        </tbody>
        
    </table>

</body>
</html>
