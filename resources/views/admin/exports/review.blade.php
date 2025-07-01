<table>
    <tr>
        <th style="height: 40px; text-align: center; font-weight: bold;">#</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Sản phẩm</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Khách hàng</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Số sao</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Nội dung</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Trạng thái</th>
        <th style="height: 40px; text-align: center; font-weight: bold;">Ngày đăng</th>
    </tr>
    @php $i = 1 @endphp
    @foreach($results as $result)
        <tr>
            <td style="height: 40px; text-align: center;">{{ $i++ }}</td>
            <td style="height: 40px;">{{ $result['product']['name'] ?? "" }}</td>
            <td style="height: 40px;">{{ ($result['customer']['name'] . "<" . $result['customer']['email'] . ">") ?? "" }}</td>
            <td style="height: 40px;">{{ $result['point'] ?? "" }}</td>
            <td style="height: 40px;">{{ $result['comment'] ?? "" }}</td>
            @switch($result['active'])
                @case('published')
                <td style="height: 40px; color: #34c38f;">{{ "Đã đăng" }}</td>
                @break
                @case('unpublished')
                <td style="height: 40px; color: #f46a6a;">{{ "Hủy đăng" }}</td>
                @break
                @default
                <td style="height: 40px; color: #556ee6;">{{ 'Chờ duyệt' }}</td>
            @endswitch
            <td style="height: 40px; text-align: center;">{{ Carbon\Carbon::parse($result['created_at'])->format('H:i:s d/m/Y') }}</td>
        </tr>
    @endforeach
</table>
