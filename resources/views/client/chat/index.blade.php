<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chatbox - SportZone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #chat-box {
            height: 400px;
            overflow-y: scroll;
            background: #f8f9fa;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .message {
            margin-bottom: 10px;
        }
        .message strong {
            color: #007bff;
        }
        .store-label {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>
<body class="p-4">
    <div class="container">
        <h2 class="mb-3">💬 Hỗ trợ trực tuyến</h2>
        <div id="chat-box">
            @foreach ($messages as $msg)
                <div class="message">
                    <strong>{{ $msg->name }}:</strong> {{ $msg->message }}
                    @if (auth()->check() && auth()->user()->role == 1 && $msg->store)
                        <div class="store-label">[{{ $msg->store->name ?? 'Cửa hàng không xác định' }}]</div>
                    @endif
                </div>
            @endforeach
        </div>

        <form id="chat-form" class="mt-3">
            @csrf
            <div class="input-group mb-2">
                <input type="text" name="name" class="form-control" placeholder="Tên của bạn" required>
            </div>
            <div class="input-group mb-2">
                <input type="text" name="message" class="form-control" placeholder="Nội dung..." required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Gửi</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        const isAdmin = {{ auth()->check() && auth()->user()->role == 1 ? 'true' : 'false' }};

        function loadMessages() {
            $.get('/chat/fetch', function(data) {
                let html = '';
                data.forEach(msg => {
                    html += `<div class="message"><strong>${msg.name}:</strong> ${msg.message}`;
                    if (isAdmin && msg.store && msg.store.name) {
                        html += `<div class="store-label">[${msg.store.name}]</div>`;
                    }
                    html += `</div>`;
                });
                $('#chat-box').html(html);
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            });
        }

        $('#chat-form').submit(function(e) {
            e.preventDefault();
            $.post('/chat/send', $(this).serialize(), function() {
                loadMessages();
                $('#chat-form')[0].reset();
            });
        });

        setInterval(loadMessages, 3000);
    </script>
</body>
</html>
