<table>
    <tr>
        <th style="height: 40px; text-align: center;">#</th>
        <th style="height: 40px; text-align: center;">Email</th>
        <th style="height: 40px; text-align: center;">Ngày đăng ký</th>
    </tr>
    @php $i = 1 @endphp
    @foreach($newsletters as $newsletter)
        <tr>
            <td style="height: 40px; text-align: center;">{{ $i++ }}</td>
            <td style="height: 40px;">{{ $newsletter['email'] }}</td>
            <td style="height: 40px; text-align: center;">{{ Carbon\Carbon::parse($newsletter['created_at'])->format('H:i:s d/m/Y') }}</td>
        </tr>
    @endforeach
</table>
