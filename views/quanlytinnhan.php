<?php
    $pageTitle = "Quản lý Tin nhắn - ChronoLux";

	if (isset($_POST['nutdx'])) {
		session_unset();
        header("Location: index.php");
        exit;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Quản lý Tin nhắn - ChronoLux</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/admin_custom.css" />
    <style>
        .chat-container {
            display: flex;
            height: calc(100vh - 150px);
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .user-list {
            width: 300px;
            border-right: 1px solid #eee;
            overflow-y: auto;
            background: #fdfdfd;
        }
        .user-item {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        .user-item:hover, .user-item.active {
            background: #f0f4f8;
        }
        .user-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .last-msg {
            font-size: 0.85rem;
            color: #777;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .msg-time {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 0.75rem;
            color: #999;
        }
        .unread-badge {
            background: #ef4444;
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            position: absolute;
            bottom: 15px;
            right: 20px;
        }
        
        .chat-box {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f4f7f6;
        }
        .chat-header {
            padding: 15px 25px;
            background: #fff;
            border-bottom: 1px solid #eee;
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
        }
        .chat-history {
            flex: 1;
            padding: 25px;
            overflow-y: auto;
        }
        .message {
            margin-bottom: 15px;
            max-width: 70%;
            display: flex;
            flex-direction: column;
        }
        .message.admin {
            margin-left: auto;
            align-items: flex-end;
        }
        .message.user {
            margin-right: auto;
            align-items: flex-start;
        }
        .msg-content {
            padding: 12px 18px;
            border-radius: 15px;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        .message.admin .msg-content {
            background: #0284c7;
            color: white;
            border-bottom-right-radius: 2px;
        }
        .message.user .msg-content {
            background: #fff;
            color: #333;
            border-bottom-left-radius: 2px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .msg-time-small {
            font-size: 0.7rem;
            color: #aaa;
            margin-top: 5px;
        }
        
        .chat-input-area {
            padding: 15px 25px;
            background: #fff;
            border-top: 1px solid #eee;
            display: flex;
            gap: 15px;
        }
        .chat-input-area input {
            flex: 1;
            padding: 12px 20px;
            border: 1px solid #ddd;
            border-radius: 25px;
            outline: none;
            transition: border-color 0.3s;
        }
        .chat-input-area input:focus {
            border-color: #0284c7;
        }
        .chat-input-area button {
            background: #0284c7;
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.3s;
        }
        .chat-input-area button:hover {
            background: #026aa1;
        }
        .empty-chat {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            color: #999;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg admin-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="./">CHRONOLUX</a>
            <div class="collapse navbar-collapse justify-content-between">
                <ul class="navbar-nav mb-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ cửa hàng</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=quantri">Bảng điều khiển</a></li>
                </ul>
                <div class="user-info">
                    <?php if (isset($_SESSION['tennd'])): ?>
                        <span class="user-name">Xin chào, <?php echo $_SESSION['tennd']; ?></span>
                        <form method="post" class="m-0">
                            <input type="submit" name="nutdx" value="Đăng xuất" class="btn-logout">
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

	<div class="container-fluid px-4 mb-5">
	  <div class="row">
	    <div class="col-lg-2 col-md-3 mb-4">
            <div class="admin-sidebar">
                <?php include APP_PATH . '/views/components/admin-sidebar.php'; ?>
            </div>
	    </div>
	    <div class="col-lg-10 col-md-9">
            <h2 class="section-title mb-4">Hộp thư Khách hàng</h2>
            <div class="chat-container">
                <div class="user-list" id="userList">
                    <!-- Load users via JS -->
                    <div style="padding: 20px; text-align: center; color: #777;">Đang tải...</div>
                </div>
                <div class="chat-box" id="chatBox">
                    <div class="empty-chat">Chọn một khách hàng để bắt đầu chat</div>
                </div>
            </div>
	    </div>
	  </div>
	</div>

    <script>
        let currentUserId = 0;
        let currentUserName = '';
        let lastMessageCount = 0;

        function loadInbox() {
            fetch('index.php?action=api/chat/inbox')
            .then(res => res.json())
            .then(data => {
                if (data.success && data.users) {
                    const list = document.getElementById('userList');
                    let html = '';
                    if (data.users.length === 0) {
                        html = '<div style="padding: 20px; text-align: center; color: #777;">Chưa có tin nhắn nào</div>';
                    } else {
                        data.users.forEach(u => {
                            const unread = u.unread_count > 0 ? `<div class="unread-badge">${u.unread_count}</div>` : '';
                            const activeClass = u.id_nd == currentUserId ? 'active' : '';
                            // HTML escape để tránh XSS
                            const escapedName = u.ten_nd.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
                            const escapedLastMsg = u.last_message ? u.last_message.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;') : '';
                            const escapedTime = u.last_time_formatted.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
                            html += `
                                <div class="user-item ${activeClass}" onclick="openChat(${u.id_nd}, '${escapedName}')">
                                    <div class="user-name">${escapedName}</div>
                                    <div class="last-msg">${escapedLastMsg}</div>
                                    <div class="msg-time">${escapedTime}</div>
                                    ${unread}
                                </div>
                            `;
                        });
                    }
                    list.innerHTML = html;
                }
            });
        }

        function openChat(userId, userName) {
            currentUserId = userId;
            currentUserName = userName;
            lastMessageCount = 0;
            
            // Highlight list item
            document.querySelectorAll('.user-item').forEach(el => el.classList.remove('active'));
            // Dựng khung chat
            const box = document.getElementById('chatBox');
            box.innerHTML = `
                <div class="chat-header">
                    <i class="fas fa-user-circle mr-2"></i> ${userName}
                </div>
                <div class="chat-history" id="chatHistory">
                    <div style="text-align: center; color: #999;">Đang tải tin nhắn...</div>
                </div>
                <div class="chat-input-area">
                    <input type="text" id="adminChatInput" placeholder="Nhập tin nhắn..." onkeypress="handleKeyPress(event)">
                    <button onclick="sendAdminMessage()"><i class="fas fa-paper-plane"></i></button>
                </div>
            `;
            
            loadMessages();
        }

        function loadMessages() {
            if (currentUserId === 0) return;
            
            fetch('index.php?action=api/chat/get&user_id=' + currentUserId)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.messages.length > lastMessageCount) {
                        const history = document.getElementById('chatHistory');
                        let html = '';
                        data.messages.forEach(msg => {
                            const role = msg.sender_type === 'admin' ? 'admin' : 'user';
                            // HTML escape để tránh XSS
                            const escapedMsg = msg.message.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
                            const escapedTime = msg.time.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
                            html += `
                                <div class="message ${role}">
                                    <div class="msg-content">${escapedMsg}</div>
                                    <div class="msg-time-small">${escapedTime}</div>
                                </div>
                            `;
                        });
                        history.innerHTML = html;
                        history.scrollTop = history.scrollHeight;
                        lastMessageCount = data.messages.length;
                    }
                }
            });
        }

        function handleKeyPress(e) {
            if (e.key === 'Enter') sendAdminMessage();
        }

        function sendAdminMessage() {
            const input = document.getElementById('adminChatInput');
            const msg = input.value.trim();
            if (!msg || currentUserId === 0) return;
            
            input.value = '';
            input.disabled = true;

            fetch('index.php?action=api/chat/send', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ message: msg, user_id: currentUserId })
            })
            .then(res => res.json())
            .then(data => {
                input.disabled = false;
                input.focus();
                if (data.success) {
                    loadMessages(); // reload immediately
                    loadInbox();
                }
            });
        }

        // Khởi động vòng lặp polling
        loadInbox();
        setInterval(() => {
            loadInbox();
            if (currentUserId > 0) {
                loadMessages();
            }
        }, 2000); // 2s polling
    </script>
</body>
</html>
