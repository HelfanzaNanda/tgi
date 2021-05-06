<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report Scheduled Arrival</title>

    <style>
        table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        table td, table th {
            border: 1px solid #ddd;
            padding: 6px;
        }

        table tr:nth-child(even){
            background-color: #f2f2f2;
        }

        table th {
            padding-top: 6px;
            padding-bottom: 6px;
            text-align: center;
            background-color: #4CAF50;
            color: white;
        }

        table tbody tr{
            text-align: center;
        }
        .text-customer {
            background: darkgreen;
            text-align: left;
        }
    </style>

</head>

<body>
    @foreach ($scheduled_arrivals as $key => $scheduled_arrival)
    <table style="width: 100%; margin-bottom: 10px">        
        <thead>
            <tr> 
                <th colspan="8" class="text-customer">Customer : {{ $key }}</th> 
            </tr>
            <tr> <th class="w-1">No.</th> <th>Invoice Number</th> <th>Product Code</th> <th>Product Description</th> <th>Qty</th> <th>Customer Order Number</th> <th>Dispatch Date</th> <th>ETA</th> </tr>
        </thead>
        <tbody>
            @foreach ($scheduled_arrival as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->invoice_number }}</td>
                    <td>{{ $item->inventory->code }}</td>
                    <td>{{ $item->inventory->product_description }}</td>
                    <td>{{ intval($item->quantity) }}</td>
                    <td>{{ $item->customer_order_number }}</td>
                    <td>{{ $item->dispatch_date->format('d-M-Y') }}</td>
                    <td>{{ $item->estimated_time_of_arrival->format('d-M-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach
    
</body>

</html>
