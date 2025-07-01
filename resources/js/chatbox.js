document.addEventListener('DOMContentLoaded', function () {
    var chatboxInput = document.querySelector('.chatbox-input');
    var chatboxMessages = document.querySelector('.chatbox-messages');
    var sendBtn = document.querySelector('.chatbox-send-btn');

    function appendMessage(message, isUser) {
        var p = document.createElement('p');
        p.textContent = message;
        p.style.backgroundColor = isUser ? '#007bff' : '#f1f1f1';
        p.style.color = isUser ? '#fff' : '#000';
        p.style.textAlign = isUser ? 'right' : 'left';
        p.style.margin = '0 0 10px 0';
        p.style.padding = '5px 10px';
        p.style.borderRadius = '15px';
        p.style.maxWidth = '80%';
        chatboxMessages.appendChild(p);
        chatboxMessages.scrollTop = chatboxMessages.scrollHeight;
    }

    sendBtn.addEventListener('click', function () {
        var message = chatboxInput.value.trim();
        if (message === '') return;
        appendMessage(message, true);
        chatboxInput.value = '';

        // Simulate bot response
        setTimeout(function () {
            appendMessage('Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất.', false);
        }, 1000);
    });

    chatboxInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            sendBtn.click();
            e.preventDefault();
        }
    });
});
