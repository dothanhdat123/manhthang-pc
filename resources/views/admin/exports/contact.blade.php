<table>
    <tr>
        <th style="height: 40px; text-align: center;">#</th>
        <th style="height: 40px; text-align: center;">Họ tên</th>
        <th style="height: 40px; text-align: center;">Số điện thoại</th>
        <th style="height: 40px; text-align: center;">Email</th>
        <th style="height: 40px; text-align: center;">Chủ đề</th>
        <th style="height: 40px; text-align: center;">Nội dung</th>
        <th style="height: 40px; text-align: center;">Trạng thái</th>
        <th style="height: 40px; text-align: center;">Ngày gửi</th>
    </tr>
    @php $i = 1 @endphp
    @foreach($contacts as $contact)
        <tr>
            <td style="height: 40px; text-align: center;">{{ $i++ }}</td>
            <td style="height: 40px;">{{ $contact['name'] ?? "" }}</td>
            <td style="height: 40px;">{{ $contact['phone'] ?? "" }}</td>
            <td style="height: 40px;">{{ $contact['email'] ?? "" }}</td>
            <td style="height: 40px;">{{ $contact['subject'] ?? "" }}</td>
            <td style="height: 40px;">{{ $contact['message'] ?? "" }}</td>
            <td style="height: 40px;">{{ $contact['active'] == 'read' ? 'Chưa đọc' : 'Đã đọc' }}</td>
            <td style="height: 40px; text-align: center;">{{ Carbon\Carbon::parse($contact['created_at'])->format('H:i:s d/m/Y') }}</td>
        </tr>
    @endforeach
</table>
