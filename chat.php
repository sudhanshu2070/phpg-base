<?php
session_start();
require 'inc/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$currentUserId = $_SESSION['user_id'];
$currentUsername = $_SESSION['username'];

$chatWithUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

if (!$chatWithUserId) {
    die("No chat user selected.");
}

$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$chatWithUserId]);
$chatWithUser = $stmt->fetchColumn();

if (!$chatWithUser) {
    die("Chat user not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chat with <?= htmlspecialchars($chatWithUser) ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            color: #333;
            display: flex;
            justify-content: center;
            padding: 40px;
        }

        .chat-container {
            width: 650px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            margin-top: 0;
            text-align: center;
            font-weight: 600;
            color: #2c3e50;
        }

        #chat-box {
            height: 400px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            overflow-y: auto;
            margin-bottom: 20px;
            background-color: #fafafa;
        }

        .message {
            margin-bottom: 12px;
            padding: 10px 15px;
            border-radius: 20px;
            max-width: 70%;
            font-size: 14px;
            word-wrap: break-word;
            clear: both;
            position: relative;
        }

        .message.you {
            background-color: #d1ecf1;
            color: #0c5460;
            margin-left: auto;
            text-align: right;
        }

        .message.them {
            background-color: #e2e3e5;
            color: #383d41;
            margin-right: auto;
            text-align: left;
        }

        .message .reply-btn {
            font-size: 12px;
            color: #888;
            cursor: pointer;
            margin-top: 5px;
            display: inline-block;
        }

        .message .reply-btn:hover {
            text-decoration: underline;
        }

        .reply {
            margin-left: 30px;
            border-left: 3px solid #dcdcdc;
            padding-left: 10px;
            margin-top: 8px;
            font-size: 13px;
            background-color: #f8f9fa;
            border-radius: 12px;
        }

        .input-area {
            display: flex;
            gap: 10px;
        }

        #message-input {
            flex: 1;
            padding: 10px 15px;
            border-radius: 20px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        #send-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        #send-btn:hover {
            background-color: #2980b9;
        }

        .reply-indicator {
            margin-bottom: 10px;
            font-size: 13px;
            color: #666;
            padding-left: 10px;
            border-left: 4px solid #bbb;
        }

        a {
            display: block;
            text-align: right;
            color: #888;
            font-size: 13px;
            text-decoration: none;
            margin-bottom: 10px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="chat-container">
    <h2>Chatting with <?= htmlspecialchars($chatWithUser) ?></h2>
    <a href="logout.php">Logout</a>

    <div id="chat-box"></div>

    <!-- <div class="reply-indicator-newbox" id="reply-indicator-newbox" >
        <input type="text" id="message-input-newbox" placeholder="Type your message..." autocomplete="off">
    </div> -->

    <div class="input-area">
        <div id="reply-indicator" class="reply-indicator" style="display: none;"></div>
        <input type="text" id="message-input" placeholder="Type your message..." autocomplete="off">
        <button id="send-btn">Send</button>
    </div>
</div>

<script>
const currentUserId = <?= $currentUserId ?>;
const chatWithUserId = <?= $chatWithUserId ?>;

function fetchMessages() {
    $.get('api/fetch_messages.php', { user1: currentUserId, user2: chatWithUserId }, function(messages) {
        let html = '';
        messages.forEach(m => {
            let cls = (m.sender_id == currentUserId) ? 'you' : 'them';
            let name = (m.sender_id == currentUserId) ? 'You' : '<?= addslashes($chatWithUser) ?>';
            html += `<div class="message ${cls}" id="msg-${m.id}">
                        <strong>${name}:</strong> ${escapeHtml(m.message)}
                        <span class="reply-btn" onclick="replyTo(${m.id})"> Reply: -   </span>
                      </div>`;
            if (m.parent_message_id) {
                html += `<div class="reply">
                            <span class="reply-text">Replying to:</span>
                            <div class="message ${cls}">
                                <strong>${name}:</strong> ${escapeHtml(m.message)}
                            </div>
                          </div>`;
            }
        });
        $('#chat-box').html(html);
        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
    }, 'json');
}

function replyTo(messageId) {
    const messageText = $(`#msg-${messageId}`).text().trim();
    $('#message-input').val(`Replying to:   ${messageText}`);
    // $('#message-input-newbox').val(`Replying to:   ${messageText}`);
    // $('#reply-indicator-newbox').data('reply-to', messageId);
    $('#message-input').data('reply-to ', messageId);
}


function escapeHtml(text) {
    return text.replace(/[&<>"']/g, function(m) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[m];
    });
}

$('#send-btn').click(function() {
    let msg = $('#message-input').val().trim();
    const replyToId = $('#message-input').data('reply-to');
    if (msg === '') return;

    $.post('api/send_message.php', { receiver_id: chatWithUserId, message: msg, parent_message_id: replyToId }, function(response) {
        if(response.error){
            alert("Error: " + response.error);
        } else {
            $('#message-input').val('');
            $('#message-input').removeData('reply-to');
            fetchMessages();
        }
    }, 'json');
});


$('#message-input').keypress(function(e) {
    if (e.which === 13) {
        $('#send-btn').click();
        return false;
    }
});

fetchMessages();
setInterval(fetchMessages, 2000);
</script>
</body>
</html>
