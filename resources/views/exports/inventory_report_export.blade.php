<table>
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Weighted Average Cost</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reportData as $data)
            <tr>
                <td>{{ $data['product_name'] }}</td>
                <td>{{ $data['quantity'] }}</td>
                <td>{{ $data['unit'] }}</td>
                <td>{{ number_format($data['weighted_average_cost'], 3) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">Total</td>
            <td>{{ number_format($totalPurchasingValue, 3) }}</td>
        </tr>
    </tfoot>
</table>
