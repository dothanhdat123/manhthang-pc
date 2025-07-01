<table>
    <tr>
        <th style="height: 40px; text-align: center;">#</th>
        <th style="height: 40px; text-align: center;">Họ tên</th>
        <th style="height: 40px; text-align: center;">Tên người dùng</th>
        <th style="height: 40px; text-align: center;">Email</th>
        <th style="height: 40px; text-align: center;">Giới tính</th>
        <th style="height: 40px; text-align: center;">Số điện thoại</th>
        <th style="height: 40px; text-align: center;">Trạng thái</th>
        <th style="height: 40px; text-align: center;">Ngày gửi</th>
    </tr>
    @php $i = 1 @endphp
    @foreach($results as $item)
        <tr>
            <td style="height: 40px; text-align: center;">{{ $i++ }}</td>
            <td style="height: 40px;">{{ $item['name'] ?? "" }}</td>
            <td style="height: 40px;">{{ $item['username'] ?? "" }}</td>
            <td style="height: 40px;">{{ $item['email'] ?? "" }}</td>
            <td style="height: 40px; text-align: center;">{{ empty($item['gender']) ? "-" : ($item['gender'] == 'male' ? "Nam" : "Nữ") }}</td>
            <td style="height: 40px; text-align: center;">{{ $item['phone'] ?? "-" }}</td>
            <td style="height: 40px; text-align: center;">{{ $item['active'] == '1' ? 'Bình thường' : 'Bị khóa' }}</td>
            <td style="height: 40px; text-align: center;">{{ Carbon\Carbon::parse($item['created_at'])->format('H:i:s d/m/Y') }}</td>
        </tr>
    @endforeach
</table>
