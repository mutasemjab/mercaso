<table>
    <thead>
        <tr>
            <th>{{ __('messages.category_name') }}</th>
            <th>{{ __('messages.category_name') }} (EN)</th>
            <th>{{ __('messages.total_quantity_sold') }}</th>
            <th>{{ __('messages.total_sales_before_tax') }}</th>
            <th>{{ __('messages.total_sales_after_tax') }}</th>
            <th>{{ __('messages.total_discount') }}</th>
            <th>{{ __('messages.total_tax') }}</th>
            <th>{{ __('messages.total_orders') }}</th>
            <th>{{ __('messages.total_products') }}</th>
            <th>{{ __('messages.in_stock_count') }}</th>
            <th>{{ __('messages.out_of_stock_count') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($salesData as $data)
            <tr>
                <td>{{ $data->name_ar }}</td>
                <td>{{ $data->name_en }}</td>
                <td>{{ $data->total_quantity_sold }}</td>
                <td>{{ number_format($data->total_sales_before_tax, 2) }}</td>
                <td>{{ number_format($data->total_sales_after_tax, 2) }}</td>
                <td>{{ number_format($data->total_discount, 2) }}</td>
                <td>{{ number_format($data->total_tax, 2) }}</td>
                <td>{{ $data->total_orders }}</td>
                <td>
                    @if(isset($inventoryData[$data->id]))
                        {{ $inventoryData[$data->id]->total_products }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if(isset($inventoryData[$data->id]))
                        {{ $inventoryData[$data->id]->in_stock_count }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if(isset($inventoryData[$data->id]))
                        {{ $inventoryData[$data->id]->out_of_stock_count }}
                    @else
                        0
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>