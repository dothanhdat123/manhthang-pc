<table>
    <tr>
        <th style="height: 40px; text-align: center; font-weight: bold;">#</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Khách hàng</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Số điện thoại</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Địa chỉ</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Tổng tiền</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Phí vận chuyển</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Hình thức thanh toán</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Trạng thái thanh toán</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Trạng thái</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Ngày đặt</th>
    </tr>
    @foreach($results as $item)
        <tr>
            <td style="height: 40px; text-align: center;">{{ $item['id'] ?? 0 }}</td>
            <td style="height: 40px;">{{ $item['customer_address']['name'] ?? "" }}</td>
            <td style="height: 40px;">{{ $item['customer_address']['phone'] ?? "" }}</td>
            <td style="height: 40px;">{{ $item['customer_address']['address'] ?? "" }}</td>
            <td style="height: 40px;">{{ number_format($item['total']) ?? 0 }} vnđ</td>
            <td style="height: 40px;">{{ number_format($item['ship']) ?? 0 }} vnđ</td>
            <td style="height: 40px;">{!! hwa_order_payment_method($item['payment_method']) !!}</td>
            <td style="height: 40px;">{!! hwa_order_payment_status($item['payment_status']) !!}</td>
            <td style="height: 40px;">{!! hwa_order_active($item['active']) !!}</td>
            <td style="height: 40px; text-align: center;">{{ Carbon\Carbon::parse($item['created_at'])->format('H:i:s d/m/Y') }}</td>
        </tr>
    @endforeach
</table>
