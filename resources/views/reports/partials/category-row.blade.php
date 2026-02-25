<tr class="category-row category-level-{{ $category['level'] }}">
    <td class="text-center font-weight-bold">{{ $index }}</td>
    <td class="font-weight-bold">
        <span style="margin-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: {{ $category['level'] * 20 }}px;">
            @if($category['has_children'])
                <i class="fas fa-folder-open text-warning mr-1"></i>
            @else
                <i class="fas fa-folder text-muted mr-1"></i>
            @endif
            {{ $category['name'] }}
        </span>
    </td>
    <td class="text-right">{{ number_format($category['total_quantity_sold']) }}</td>
    <td class="text-right">${{ number_format($category['total_sales_before_tax'], 2) }}</td>
    <td class="text-right">
        @if($category['total_discount'] > 0)
            <span class="text-danger">-${{ number_format($category['total_discount'], 2) }}</span>
        @else
            $0.00
        @endif
    </td>
    <td class="text-right text-success">${{ number_format($category['total_tax'], 2) }}</td>
    <td class="text-right font-weight-bold text-primary">${{ number_format($category['total_sales_after_tax'], 2) }}</td>
    <td class="text-center">{{ number_format($category['total_orders']) }}</td>
    <td class="text-center"><span class="badge badge-info">{{ number_format($category['total_products']) }}</span></td>
    <td class="text-center"><span class="badge badge-success">{{ number_format($category['in_stock_count']) }}</span></td>
    <td class="text-center"><span class="badge badge-danger">{{ number_format($category['out_of_stock_count']) }}</span></td>
</tr>

{{-- Render child categories recursively --}}
@forelse($category['children'] as $childIndex => $child)
    @include('reports.partials.category-row', ['category' => $child, 'index' => ''])
@empty
@endforelse
