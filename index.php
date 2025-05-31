<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'inc/db.php';

// For demo, set a static receiver ID (later we’ll make it dynamic)
$receiver_id = 1; // e.g., admin
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #chat-box {
            width: 500px;
            height: 300px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
        }
        #message {
            width: 400px;
        }
    </style>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>! <a href="logout.php">Logout</a></h2>

<div id="chat-box"></div>
<input type="text" id="message" placeholder="Type a message">
<button id="send">Send</button>

<script>
let receiverId = <?= $receiver_id ?>;

function fetchMessages() {
    $.get("api/fetch_messages.php", { receiver_id: receiverId }, function(data) {
        let messages = JSON.parse(data);
        let html = '';
        messages.forEach(m => {
            html += `<p><strong>${m.sender_id}:</strong> ${m.message}</p>`;
        });
        $('#chat-box').html(html).scrollTop($('#chat-box')[0].scrollHeight);
    });
}

$('#send').on('click', function() {
    const msg = $('#message').val();
    if (msg.trim() === '') return;
    $.post("api/send_message.php", { receiver_id: receiverId, message: msg }, function() {
        $('#message').val('');
        fetchMessages();
    });
});

setInterval(fetchMessages, 2000);
fetchMessages();
</script>

</body>
</html>
