<table>
    <tr>
        <td>
            <img src="assets/images/logo-light.png" alt="" width="200">
        </td>
        <td colspan="3" style="vertical-align: center; text-align: right;">
            <p>Hóa đơn: #{{ $result['id'] }}</p>
            <p>Ngày đặt: {{ Carbon\Carbon::parse($result['created_at'])->format('d-m-Y') }}</p>
            <p>Ngày nhận: {{ Carbon\Carbon::parse($result['created_at'])->format('d-m-Y') }}</p>
        </td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="4" style="vertical-align: center; text-align: left; font-weight: bold;">
            {{ strtoupper($result['customer_address']['name']) ?? "" }}
        </td>
    </tr>
    <tr>
        <td colspan="4" style="vertical-align: center; text-align: left;">
            {{ $result['customer_address']['phone'] ?? "" }}
        </td>
    </tr>
    <tr>
        <td colspan="4" style="vertical-align: center; text-align: left;">
            {{ $result['customer_address']['address'] ?? "" }}
        </td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid #0b0b0b;">Tên sản phẩm</th>
        <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid #0b0b0b;">Đơn giá</th>
        <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid #0b0b0b;">Số lượng</th>
        <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid #0b0b0b;">Tổng tiền</th>
    </tr>
    @foreach($result['order_details'] as $detail)
    <tr>
        <td style="text-align: center; vertical-align: center; border: 1px solid #0b0b0b;">{{ $detail['name'] ?? "" }}</td>
        <td style="text-align: center; vertical-align: center; border: 1px solid #0b0b0b;">{{ number_format($detail['price']) ?? 0 }} đ</td>
        <td style="text-align: center; vertical-align: center; border: 1px solid #0b0b0b;">{{ $detail['qty'] ?? 0 }}</td>
        <td style="text-align: center; vertical-align: center; border: 1px solid #0b0b0b;">{{ number_format($detail['total']) ?? 0 }} đ</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td style="text-align: right; vertical-align: center;">Tạm tính</td>
        <td style="text-align: right; vertical-align: center;">{{ number_format($result['subtotal']) ?? 0 }} đ</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td style="text-align: right; vertical-align: center;">Phí vận chuyển</td>
        <td style="text-align: right; vertical-align: center;">{{ number_format($result['ship']) ?? 0 }} đ</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td style="text-align: right; vertical-align: center; font-weight: bold; border-top: 1px solid #0b0b0b;">Tổng tiền</td>
        <td style="text-align: right; vertical-align: center; font-weight: bold; border-top: 1px solid #0b0b0b;">{{ number_format($result['total']) ?? 0 }} đ</td>
    </tr>
</table>
