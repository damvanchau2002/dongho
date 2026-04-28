<?php
$pageTitle = "Tài khoản - ChronoLux";
ob_start();
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

.auth-page {
    font-family: 'Inter', sans-serif;
    min-height: calc(100vh - 80px); /* Adjust based on header height */
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    background: #f0f4f8;
    background-image: radial-gradient(circle at 100% 0%, #1a4a7a 0%, transparent 40%),
                      radial-gradient(circle at 0% 100%, #d4af37 0%, transparent 30%);
}

.auth-container {
    display: flex;
    width: 100%;
    max-width: 1000px;
    background: #fff;
    border-radius: 24px;
    box-shadow: 0 24px 80px rgba(15, 41, 66, 0.15);
    overflow: hidden;
    min-height: 600px;
}

/* LEFT SIDE - BRANDING */
.auth-brand {
    flex: 1.2;
    background: linear-gradient(135deg, #0a192f 0%, #1a4a7a 100%);
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 50px;
    color: #fff;
    overflow: hidden;
}

.auth-brand::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at top right, rgba(212, 175, 55, 0.15), transparent 50%);
}

.auth-brand-content {
    position: relative;
    z-index: 1;
}

.auth-brand-logo {
    display: inline-block;
    font-size: 32px;
    font-weight: 800;
    color: #fff;
    margin-bottom: 40px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.auth-brand-logo i {
    color: #d4af37;
    margin-right: 8px;
}

.auth-brand-content h2 {
    font-size: 38px;
    font-weight: 800;
    margin-bottom: 20px;
    line-height: 1.2;
}

.auth-brand-content h2 span {
    color: #d4af37;
}

.auth-brand-content p {
    font-size: 16px;
    line-height: 1.6;
    color: #e2e8f0;
    font-weight: 400;
    max-width: 90%;
}

/* RIGHT SIDE - FORM */
.auth-forms {
    flex: 1;
    background: #fff;
    padding: 50px;
    display: flex;
    flex-direction: column;
}

.auth-tabs {
    display: flex;
    gap: 30px;
    margin-bottom: 40px;
    position: relative;
}

.auth-tab {
    font-size: 22px;
    font-weight: 700;
    color: #cbd5e1;
    cursor: pointer;
    transition: 0.3s ease;
    background: none;
    border: none;
    padding: 0 0 10px 0;
}

.auth-tab:hover {
    color: #94a3b8;
}

.auth-tab.active {
    color: #0f2942;
}

.auth-indicator {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 4px;
    width: 100px; /* updated via JS */
    background: linear-gradient(90deg, #d4af37, #f3d56d);
    border-radius: 4px;
    transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.form-wrapper {
    position: relative;
    flex: 1;
}

.auth-form {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    opacity: 0;
    visibility: hidden;
    transform: translateX(30px);
}

.auth-form.active {
    opacity: 1;
    visibility: visible;
    transform: translateX(0) !important;
}

.auth-form.slide-left {
    transform: translateX(-30px) !important;
}

.input-group {
    margin-bottom: 24px;
}

.input-group label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}

.input-field {
    position: relative;
}

.input-field input {
    width: 100%;
    padding: 14px 16px 14px 46px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 15px;
    color: #1a2535;
    transition: 0.3s;
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    background: #f8fafc;
}

.input-field input:focus {
    outline: none;
    border-color: #0f2942;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(15, 41, 66, 0.08);
}

.input-field i {
    position: absolute;
    top: 50%;
    left: 16px;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 16px;
    transition: 0.3s;
}

.input-field input:focus + i {
    color: #0f2942;
}

.auth-options {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 30px;
    margin-top: -10px;
}

.auth-options a {
    color: #1a4a7a;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s;
}

.auth-options a:hover {
    color: #d4af37;
}

.btn-auth {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #0f2942, #1a4a7a);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.3s;
    box-shadow: 0 8px 20px rgba(15, 41, 66, 0.2);
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

.auth-divider {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 24px 0;
    color: #94a3b8;
    font-size: 13px;
    font-weight: 500;
}
.auth-divider::before,
.auth-divider::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid #e2e8f0;
}
.auth-divider span {
    padding: 0 10px;
}

.btn-auth:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(15, 41, 66, 0.3);
    background: linear-gradient(135deg, #1a4a7a, #2563a8);
}

/* Error/Success Messages Placeholder if needed */
.msg-alert {
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.msg-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
.msg-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }

@media (max-width: 768px) {
    .auth-container { flex-direction: column; }
    .auth-brand { min-height: 200px; padding: 40px 30px; }
    .auth-brand-content h2 { font-size: 28px; }
    .auth-forms { padding: 40px 30px; }
}
</style>

<!-- GOOGLE API SCRIPT -->
<script src="https://accounts.google.com/gsi/client" async defer></script>

<div class="auth-page">
    <div class="auth-container">
        <!-- BRANDING SECTION -->
        <div class="auth-brand">
            <div class="auth-brand-content">
                <div class="auth-brand-logo"><i class="fas fa-gem"></i> ChronoLux</div>
                <h2>Khám phá<br>Sự hoàn mỹ của<br><span>Thời gian</span></h2>
                <p>Gia nhập cùng hàng ngàn khách hàng tin dùng bộ sưu tập đồng hồ cơ cao cấp, chính hãng và dịch vụ đẳng cấp từ ChronoLux.</p>
            </div>
        </div>

        <!-- FORM SECTION -->
        <div class="auth-forms">
            <div class="auth-tabs">
                <button class="auth-tab active" id="tabLogin" onclick="switchTab('login')">Đăng nhập</button>
                <button class="auth-tab" id="tabRegister" onclick="switchTab('register')">Đăng ký</button>
                <div class="auth-indicator" id="authIndicator"></div>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="msg-alert msg-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="msg-alert msg-success">
                    <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="form-wrapper">
                <!-- LOGIN FORM -->
                <form id="authLoginForm" class="auth-form active" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo SecurityHelper::csrfToken(); ?>">
                    <?php if (isset($_GET['return_url'])): ?>
                        <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_GET['return_url']); ?>">
                    <?php endif; ?>
                    
                    <div class="input-group">
                        <label>Tên đăng nhập</label>
                        <div class="input-field">
                            <input type="text" placeholder="Nhập tên tài khoản" name="tdn" required maxlength="20">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label>Mật khẩu</label>
                        <div class="input-field">
                            <input type="password" placeholder="Nhập mật khẩu" name="mk" required maxlength="32">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                    
                    <div class="auth-options">
                        <a href="#">Quên mật khẩu?</a>
                    </div>
                    
                    <button type="submit" class="btn-auth" name="nutdangnhap">Đăng nhập <i class="fas fa-arrow-right"></i></button>

                    <!-- GOOGLE LOGIN BUTTON -->
                    <div class="auth-divider"><span>Hoặc đăng nhập bằng</span></div>
                    <div id="g_id_onload"
                        data-client_id="103291429470-2pcdu1gciuj8gtot37urae3t1nta587c.apps.googleusercontent.com"
                        data-context="signin"
                        data-ux_mode="popup"
                        data-callback="handleCredentialResponse"
                        data-auto_prompt="false">
                    </div>
                    <div class="g_id_signin"
                        data-type="standard"
                        data-shape="rectangular"
                        data-theme="outline"
                        data-text="signin_with"
                        data-size="large"
                        data-logo_alignment="left"
                        style="display: flex; justify-content: center; width: 100%;">
                    </div>

                </form>
                
                <!-- REGISTER FORM -->
                <form id="authRegForm" class="auth-form" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo SecurityHelper::csrfToken(); ?>">
                    <?php if (isset($_GET['return_url'])): ?>
                        <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_GET['return_url']); ?>">
                    <?php endif; ?>
                    
                    <div class="input-group">
                        <label>Tên đăng nhập</label>
                        <div class="input-field">
                            <input type="text" placeholder="Tạo tên tài khoản (viết liền không dấu)" name="tendk" required maxlength="20">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label>Email</label>
                        <div class="input-field">
                            <input type="email" placeholder="example@email.com" name="emaildk" required maxlength="50">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label>Mật khẩu</label>
                        <div class="input-field">
                            <input type="password" placeholder="Tạo mật khẩu an toàn" name="mkdk" required maxlength="32">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-auth" name="nutdangky">Tạo tài khoản <i class="fas fa-user-check"></i></button>

                    <!-- GOOGLE LOGIN BUTTON -->
                    <div class="auth-divider"><span>Hoặc đăng ký bằng</span></div>
                    <div class="g_id_signin"
                        data-type="standard"
                        data-shape="rectangular"
                        data-theme="outline"
                        data-text="signup_with"
                        data-size="large"
                        data-logo_alignment="left"
                        style="display: flex; justify-content: center; width: 100%;">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function handleCredentialResponse(response) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'index.php?action=taikhoan';
    
    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = 'google_credential';
    tokenInput.value = response.credential;
    form.appendChild(tokenInput);

    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = 'csrf_token';
    csrfInput.value = '<?php echo SecurityHelper::csrfToken(); ?>';
    form.appendChild(csrfInput);

    <?php if (isset($_GET['return_url'])): ?>
    const returnUrlInput = document.createElement('input');
    returnUrlInput.type = 'hidden';
    returnUrlInput.name = 'return_url';
    returnUrlInput.value = '<?php echo htmlspecialchars($_GET['return_url']); ?>';
    form.appendChild(returnUrlInput);
    <?php endif; ?>

    document.body.appendChild(form);
    form.submit();
}

function switchTab(tab) {
    const tabLogin = document.getElementById('tabLogin');
    const tabRegister = document.getElementById('tabRegister');
    const formLogin = document.getElementById('authLoginForm');
    const formRegister = document.getElementById('authRegForm');
    const indicator = document.getElementById('authIndicator');

    if (tab === 'login') {
        tabLogin.classList.add('active');
        tabRegister.classList.remove('active');
        
        formLogin.classList.remove('slide-left');
        formLogin.classList.add('active');
        formRegister.classList.remove('active');
        formRegister.classList.add('slide-left'); // move register out to left
        
        indicator.style.width = tabLogin.offsetWidth + 'px';
        indicator.style.left = tabLogin.offsetLeft + 'px';
    } else {
        tabRegister.classList.add('active');
        tabLogin.classList.remove('active');
        
        formRegister.classList.remove('slide-left');
        formRegister.classList.add('active');
        formLogin.classList.remove('active');
        formLogin.classList.add('slide-left'); // move login out to left
        
        indicator.style.width = tabRegister.offsetWidth + 'px';
        indicator.style.left = tabRegister.offsetLeft + 'px';
    }
}

// Initialize indicator position
window.addEventListener('load', () => {
    const tabLogin = document.getElementById('tabLogin');
    const indicator = document.getElementById('authIndicator');
    indicator.style.width = tabLogin.offsetWidth + 'px';
    indicator.style.left = tabLogin.offsetLeft + 'px';
});

// Update on resize
window.addEventListener('resize', () => {
    const activeTab = document.querySelector('.auth-tab.active');
    const indicator = document.getElementById('authIndicator');
    if(activeTab && indicator) {
        indicator.style.width = activeTab.offsetWidth + 'px';
        indicator.style.left = activeTab.offsetLeft + 'px';
    }
});
</script>
<?php
$content = ob_get_clean();
require BASE_PATH . '/views/layouts/user-layout.php';
?>