<?php
/**
 * User Layout Component
 *
 * Cách dùng trong view:
 *   $pageTitle = 'Tiêu đề trang';
 *   ob_start();
 *   // ... HTML nội dung trang ...
 *   $content = ob_get_clean();
 *   require BASE_PATH . '/views/layouts/user-layout.php';
 *
 * Biến có thể truyền vào:
 *   $pageTitle   (string)  - Tiêu đề tab trình duyệt
 *   $extraHead   (string)  - CSS/JS thêm vào <head> (tuỳ chọn)
 *   $content     (string)  - HTML nội dung trang (từ ob_get_clean)
 */
$pageTitle = $pageTitle ?? 'ChronoLux Watch Store';
$extraHead = $extraHead ?? '';
$content   = $content   ?? '';

$isLoggedIn = isset($_SESSION['tennd']);
$isAdmin    = $isLoggedIn && (int)($_SESSION['quyennd'] ?? 0) === 1;

$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cart_count = array_sum($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- App Styles -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/css/customStyle.css?v=<?php echo time(); ?>">

    <?php echo $extraHead; ?>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<div class="navbar" style="position: relative; z-index: 999999;">
    <div class="logo">
        <a href="./" class="brand-wrap">
            <img src="images/logo.png" width="120px" alt="ChronoLux logo">
            <span class="brand-text">ChronoLux</span>
        </a>
    </div>
    <nav>
        <ul id="MenuItems" class="menu-items">
            <li><a href="./">Trang chủ</a></li>
            <li><a href="index.php?action=sanpham">Sản phẩm</a></li>
            <li><a href="index.php?action=gioithieu">Giới thiệu</a></li>
            <li><a href="index.php?action=lienhe">Liên hệ</a></li>
            <li class="nav-search-li">
                <div class="navbar-search">
                    <input type="text" id="navbar-search-input" placeholder="Tìm kiếm..." autocomplete="off">
                    <button type="button" id="navbar-search-btn"><i class="fa fa-search"></i></button>
                </div>
            </li>

            <?php if ($isLoggedIn): ?>
                <li class="navbar__user">
                    <a class="nav-link nav-link__active <?= $isAdmin ? 'active' : ''; ?>" href="#" style="display: flex; align-items: center; gap: 8px;">
                        <?php if (isset($_SESSION['avatar']) && $_SESSION['avatar']): ?>
                            <img src="<?= $_SESSION['avatar'] ?>" alt="Avatar" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                            <i class="fas fa-user-circle" style="font-size: 24px; color: #d4af37;"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars($_SESSION['tennd']); ?>
                    </a>
                    <ul class="navbar__user-menu">
                        <li class="navbar__user-menu-item">
                            <a href="index.php?action=thongtintaikhoan">
                                <i class="fas fa-user-circle me-2"></i> Tài khoản của tôi
                            </a>
                        </li>
                        <?php if ($isAdmin): ?>
                            <li class="navbar__user-menu-item">
                                <a class="nav-link active" href="index.php?action=quantri">
                                    <i class="fas fa-user-shield me-2"></i> Quản trị
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="navbar__user-menu-item">
                            <a href="index.php?action=sanpham_yeuthich">
                                <i class="fas fa-heart me-2 text-danger"></i> Yêu thích của tôi
                            </a>
                        </li>
                        <li class="navbar__user-menu-item">
                            <a href="index.php?action=thongtintaikhoan#don-hang">
                                <i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi
                            </a>
                        </li>
                        <li class="navbar__user-menu-item navbar__user-menu-item--separate">
                            <a href="index.php?action=dangxuat" id="btn-dangxuat">
                                <i class="fas fa-sign-out-alt me-2 text-danger"></i> Đăng xuất
                            </a>
                        </li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a class="nav-link" href="index.php?action=taikhoan">Đăng nhập / Đăng ký</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Cart Icon -->
    <a href="index.php?action=giohang" style="position:relative;display:inline-block;margin-right:15px;">
        <img src="images/cart.png" width="30px" height="30px" alt="Giỏ hàng">
        <?php if ($cart_count > 0): ?>
            <span style="position:absolute;top:-8px;right:-12px;background-color:#ff523b;color:#fff;
                         border-radius:50%;padding:2px 6px;font-size:11px;font-weight:700;
                         line-height:1;min-width:18px;text-align:center;
                         border:2px solid #fff;box-shadow:0 2px 4px rgba(0,0,0,.1);">
                <?= $cart_count ?>
            </span>
        <?php endif; ?>
    </a>


    <img src="images/menu.png" class="menu-icon" onclick="menutoggle()" alt="Menu">
</div>

<!-- ===== PAGE CONTENT ===== -->
<?php echo $content; ?>

<!-- ===== FOOTER ===== -->
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="footer-col-1">
                <h3>Mua sắm tiện lợi</h3>
                <p>Theo dõi bộ sưu tập đồng hồ mới nhất và ưu đãi độc quyền mỗi tuần.</p>
                <div class="app-logo">
                    <img src="images/play-store.png" alt="Google Play">
                    <img src="images/app-store.png" alt="App Store">
                </div>
            </div>
            <div class="footer-col-2">
                <img src="images/logo-white.png" alt="ChronoLux">
                <p>ChronoLux mang đến những mẫu đồng hồ chính hãng, thiết kế tinh tế và dịch vụ hậu mãi tận tâm.</p>
            </div>
            <div class="footer-col-3">
                <h3>Liên kết nhanh</h3>
                <ul>
                    <li><a href="index.php?action=gioithieu">Giới thiệu</a></li>
                    <li>Ưu đãi thành viên</li>
                    <li>Hướng dẫn chọn đồng hồ</li>
                    <li>Chính sách bảo hành</li>
                </ul>
            </div>
            <div class="footer-col-4">
                <h3>Kết nối</h3>
                <ul>
                    <li>Facebook</li>
                    <li>TikTok</li>
                    <li>Instagram</li>
                    <li>Youtube</li>
                </ul>
            </div>
        </div>
        <hr>
        <p class="copyright">Copyright 2026 - ChronoLux Watch Store</p>
    </div>
</div>

<!-- ===== SCRIPTS ===== -->
<script>
    // Mobile menu toggle
    var menuItems = document.getElementById('MenuItems');
    menuItems.style.maxHeight = '0px';
    function menutoggle() {
        menuItems.style.maxHeight = menuItems.style.maxHeight === '0px' ? '200px' : '0px';
    }

    // Inline navbar search
    function doNavSearch() {
        var keyword = document.getElementById('navbar-search-input').value.trim();
        if (keyword) {
            window.location.href = 'index.php?action=ketquatimkiem&keyword=' + encodeURIComponent(keyword);
        }
    }
    document.getElementById('navbar-search-btn').addEventListener('click', doNavSearch);
    document.getElementById('navbar-search-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') doNavSearch();
    });
</script>

<!-- ===== CHATBOT AI ===== -->
<div class="chatbot-container" id="chatbotContainer">
    <div class="chatbot-header">
        <div class="chatbot-title">
            <i class="fas fa-robot" id="chatIcon"></i> <span id="chatTitle">AI Assistant</span>
        </div>
        <div class="chatbot-actions" style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 0.7rem;">Gặp NV</span>
            <label class="switch-mode" style="margin: 0; position: relative; display: inline-block; width: 34px; height: 20px;">
                <input type="checkbox" id="chatModeToggle" onchange="toggleChatMode()" style="opacity: 0; width: 0; height: 0;">
                <span class="slider" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px;"></span>
            </label>
            <i class="fas fa-times" onclick="toggleChatbot()" style="cursor: pointer; margin-left: 5px;"></i>
        </div>
    </div>
    <div class="chatbot-body" id="chatbotBody">
        <div class="chat-message bot-message">
            <p>Xin chào! Tôi là trợ lý ảo của ChronoLux. Tôi có thể giúp gì cho bạn hôm nay? (Ví dụ: "Gợi ý đồng hồ nam dưới 10 triệu")</p>
        </div>
    </div>
    <div class="chatbot-footer">
        <input type="text" id="chatbotInput" placeholder="Nhập câu hỏi của bạn..." onkeypress="handleChatKeyPress(event)">
        <button onclick="sendChatMessage()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<div class="chatbot-button" id="chatbotBtn" onclick="toggleChatbot()">
    <i class="fas fa-comment-dots"></i>
</div>

<style>
/* Chatbot CSS */
.chatbot-button {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    background: #ff523b;
    color: white;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 28px;
    box-shadow: 0 4px 15px rgba(255, 82, 59, 0.4);
    cursor: pointer;
    z-index: 9999;
    transition: transform 0.3s;
}
.chatbot-button:hover {
    transform: scale(1.1);
}
.chatbot-container {
    position: fixed;
    bottom: 100px;
    right: 30px;
    width: 350px;
    height: 500px;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.2);
    display: flex;
    flex-direction: column;
    z-index: 10000;
    overflow: hidden;
    transition: all 0.3s ease;
    transform: translateY(20px);
    opacity: 0;
    pointer-events: none;
}
.chatbot-container.active {
    transform: translateY(0);
    opacity: 1;
    pointer-events: auto;
}
.chatbot-header {
    background: #ff523b;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}
.chatbot-title {
    font-weight: 600;
    font-size: 16px;
}
.chatbot-body {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    background: #f9f9f9;
}
.chat-message {
    margin-bottom: 15px;
    max-width: 85%;
    line-height: 1.5;
    font-size: 14px;
}
.chat-message p {
    padding: 10px 15px;
    border-radius: 15px;
    margin: 0;
}
.user-message {
    margin-left: auto;
}
.user-message p {
    background: #ff523b;
    color: white;
    border-bottom-right-radius: 0;
}
.bot-message p {
    background: #e5e5ea;
    color: #333;
    border-bottom-left-radius: 0;
}
.chatbot-footer {
    padding: 15px;
    background: #fff;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
}
.chatbot-footer input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 20px;
    outline: none;
}
.chatbot-footer button {
    background: #ff523b;
    color: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
}
.typing-indicator {
    display: none;
    padding: 10px 15px;
    background: #e5e5ea;
    border-radius: 15px;
    border-bottom-left-radius: 0;
    width: fit-content;
    margin-bottom: 15px;
}
.typing-indicator span {
    display: inline-block;
    width: 6px;
    height: 6px;
    background: #888;
    border-radius: 50%;
    margin: 0 2px;
    animation: typing 1.4s infinite both;
}
.typing-indicator span:nth-child(1) { animation-delay: 0s; }
.typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
.typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
.switch-mode input:checked + .slider { background-color: #4CAF50; }
.switch-mode .slider:before {
    position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px;
    background-color: white; transition: .4s; border-radius: 50%;
}
.switch-mode input:checked + .slider:before { transform: translateX(14px); }
@keyframes typing {
    0%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-5px); }
}
</style>

<!-- Bao gồm thư viện Marked.js để render Markdown từ AI -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<script>
    let isChatOpen = false;

    function toggleChatbot() {
        const container = document.getElementById('chatbotContainer');
        isChatOpen = !isChatOpen;
        if (isChatOpen) {
            container.classList.add('active');
            document.getElementById('chatbotInput').focus();
        } else {
            container.classList.remove('active');
        }
    }

    function handleChatKeyPress(e) {
        if (e.key === 'Enter') {
            sendChatMessage();
        }
    }

    function appendMessage(text, isUser) {
        const body = document.getElementById('chatbotBody');
        const div = document.createElement('div');
        div.className = 'chat-message ' + (isUser ? 'user-message' : 'bot-message');
        
        if (isUser) {
            let htmlText = text.replace(/\n/g, '<br>');
            div.innerHTML = '<p>' + htmlText + '</p>';
        } else {
            // Sử dụng thư viện marked để chuyển đổi Markdown sang HTML
            let htmlText = (typeof marked !== 'undefined') ? marked.parse(text) : text.replace(/\n/g, '<br>');
            div.innerHTML = '<div style="background: #e5e5ea; color: #333; padding: 10px 15px; border-radius: 15px; border-bottom-left-radius: 0;">' + htmlText + '</div>';
        }
        
        body.appendChild(div);
        body.scrollTop = body.scrollHeight;
    }

    function showTypingIndicator() {
        const body = document.getElementById('chatbotBody');
        const div = document.createElement('div');
        div.id = 'typingIndicator';
        div.className = 'typing-indicator';
        div.style.display = 'block';
        div.innerHTML = '<span></span><span></span><span></span>';
        body.appendChild(div);
        body.scrollTop = body.scrollHeight;
    }

    function hideTypingIndicator() {
        const indicator = document.getElementById('typingIndicator');
        if (indicator) {
            indicator.remove();
        }
    }

    let chatMode = 'ai'; // 'ai' or 'admin'
    let chatPolling = null;
    let lastUserMsgCount = 0;
    const isLoggedIn = <?php echo isset($_SESSION['id_nd']) ? 'true' : 'false'; ?>;

    function toggleChatMode() {
        const isChecked = document.getElementById('chatModeToggle').checked;
        chatMode = isChecked ? 'admin' : 'ai';
        const body = document.getElementById('chatbotBody');
        body.innerHTML = '';
        
        const title = document.getElementById('chatTitle');
        const icon = document.getElementById('chatIcon');
        
        if (chatMode === 'admin') {
            title.innerText = 'Nhân viên Hỗ trợ';
            icon.className = 'fas fa-user-headset';
            
            if (!isLoggedIn) {
                appendMessage('Vui lòng đăng nhập tài khoản để chat với nhân viên hỗ trợ.', false);
                document.getElementById('chatbotInput').disabled = true;
                return;
            }
            document.getElementById('chatbotInput').disabled = false;
            
            // Start polling
            loadUserMessages(true);
            chatPolling = setInterval(() => loadUserMessages(false), 2000);
        } else {
            title.innerText = 'AI Assistant';
            icon.className = 'fas fa-robot';
            clearInterval(chatPolling);
            document.getElementById('chatbotInput').disabled = false;
            appendMessage('Bạn đã quay lại trò chuyện với AI. Tôi có thể giúp gì cho bạn?', false);
        }
    }

    function loadUserMessages(forceScroll = false) {
        if (!isLoggedIn || chatMode !== 'admin') return;
        
        fetch('index.php?action=api/chat/get')
        .then(res => res.json())
        .then(data => {
            if (data.success && data.messages.length > lastUserMsgCount) {
                const body = document.getElementById('chatbotBody');
                body.innerHTML = '';
                
                if (data.messages.length === 0) {
                    appendMessage('Bạn đã kết nối với Nhân viên. Vui lòng để lại tin nhắn...', false);
                } else {
                    data.messages.forEach(msg => {
                        const isUser = msg.sender_type === 'user';
                        const div = document.createElement('div');
                        div.className = 'chat-message ' + (isUser ? 'user-message' : 'bot-message');
                        let htmlText = msg.message.replace(/\n/g, '<br>');
                        if (isUser) {
                            div.innerHTML = '<p>' + htmlText + '</p>';
                        } else {
                            div.innerHTML = '<div style="background: #e5e5ea; color: #333; padding: 10px 15px; border-radius: 15px; border-bottom-left-radius: 0;">' + htmlText + '</div>';
                        }
                        body.appendChild(div);
                    });
                }
                
                if (forceScroll || data.messages.length > lastUserMsgCount) {
                    body.scrollTop = body.scrollHeight;
                }
                lastUserMsgCount = data.messages.length;
            }
        });
    }

    async function sendChatMessage() {
        const input = document.getElementById('chatbotInput');
        const message = input.value.trim();
        
        if (!message) return;
        
        input.value = '';
        
        if (chatMode === 'admin') {
            appendMessage(message, true);
            input.disabled = true;
            try {
                await fetch('index.php?action=api/chat/send', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ message: message })
                });
                loadUserMessages(true);
            } catch (e) {
                console.error(e);
            } finally {
                input.disabled = false;
                input.focus();
            }
            return;
        }

        // --- AI MODE ---
        appendMessage(message, true);
        input.disabled = true;
        
        showTypingIndicator();
        
        try {
            const response = await fetch('index.php?action=api/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: message })
            });
            
            const data = await response.json();
            hideTypingIndicator();
            appendMessage(data.reply, false);

            
        } catch (error) {
            hideTypingIndicator();
            appendMessage("Lỗi kết nối tới hệ thống AI. Vui lòng thử lại sau.", false);
        } finally {
            input.disabled = false;
            input.focus();
        }
    }

    // Favorite Product Logic
    function toggleFavorite(id_sp) {
        if (!isLoggedIn) {
            alert('Bạn cần đăng nhập để thả tim sản phẩm!');
            window.location.href = 'index.php?action=taikhoan';
            return;
        }
        
        fetch('index.php?action=api/favorite/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_sp: id_sp })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Toggle heart icon color
                const btn = document.getElementById('fav-btn-' + id_sp);
                if (btn) {
                    if (data.is_favorite) {
                        btn.classList.remove('far');
                        btn.classList.add('fas', 'text-danger');
                    } else {
                        btn.classList.remove('fas', 'text-danger');
                        btn.classList.add('far');
                    }
                }
            } else {
                alert(data.message || 'Lỗi xử lý yêu thích');
                if (data.require_login) {
                    window.location.href = 'index.php?action=taikhoan';
                }
            }
        })
        .catch(err => {
            console.error('Lỗi khi thả tim:', err);
        });
    }
</script>

</body>
</html>
