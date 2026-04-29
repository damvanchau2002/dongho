<?php
$pageTitle = "Vòng Quay May Mắn - ChronoLux";
ob_start();
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap');

.wheel-page { font-family:'Inter',sans-serif; min-height:100vh; padding:40px 0 80px; background: linear-gradient(135deg, #0a0e1a 0%, #0f2942 50%, #1a0a2e 100%); position:relative; overflow:hidden; }

.wheel-page::before {
    content:'';position:absolute;inset:0;
    background: radial-gradient(ellipse at 20% 50%, rgba(212,175,55,.08) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(100,50,200,.08) 0%, transparent 60%);
    pointer-events:none;
}

.wheel-container { max-width:1100px; margin:0 auto; padding:0 20px; position:relative; z-index:1; }

/* HEADER */
.wh-header { text-align:center; margin-bottom:48px; }
.wh-header h1 { font-size:42px; font-weight:900; background:linear-gradient(135deg,#d4af37,#f5d060,#d4af37); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; margin:0 0 12px; letter-spacing:-1px; }
.wh-header p { color:rgba(255,255,255,.6); font-size:16px; margin:0; }
.wh-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(212,175,55,.15); border:1px solid rgba(212,175,55,.3); color:#d4af37; padding:8px 20px; border-radius:50px; font-size:13px; font-weight:700; margin-bottom:20px; letter-spacing:.5px; }

/* MAIN LAYOUT */
.wh-layout { display:grid; grid-template-columns:1fr 380px; gap:40px; align-items:start; }

/* WHEEL WRAPPER */
.wheel-section { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.08); border-radius:28px; padding:48px 40px; text-align:center; backdrop-filter:blur(10px); }

.wheel-wrapper { position:relative; display:inline-block; margin:0 auto 32px; }

/* Triangle pointer */
.wheel-pointer {
    position:absolute; top:-18px; left:50%; transform:translateX(-50%);
    width:0; height:0;
    border-left:14px solid transparent;
    border-right:14px solid transparent;
    border-top:30px solid #d4af37;
    filter: drop-shadow(0 4px 12px rgba(212,175,55,.8));
    z-index:10;
}

canvas#wheelCanvas {
    border-radius:50%;
    box-shadow: 0 0 0 6px rgba(212,175,55,.3), 0 0 0 12px rgba(212,175,55,.1), 0 30px 80px rgba(0,0,0,.5);
    display:block;
}

/* Center spin button on canvas */
.spin-center {
    position:absolute;
    top:50%; left:50%;
    transform:translate(-50%,-50%);
    width:70px; height:70px;
    border-radius:50%;
    background:linear-gradient(135deg,#d4af37,#f5d060);
    border:4px solid #fff;
    cursor:pointer;
    font-size:11px;
    font-weight:900;
    color:#0f2942;
    letter-spacing:.5px;
    box-shadow:0 4px 20px rgba(212,175,55,.6);
    transition:.2s;
    z-index:10;
    display:flex; align-items:center; justify-content:center;
}
.spin-center:hover:not(:disabled) { transform:translate(-50%,-50%) scale(1.08); box-shadow:0 8px 30px rgba(212,175,55,.8); }
.spin-center:disabled { cursor:not-allowed; opacity:.6; }

.spin-btn-main {
    background:linear-gradient(135deg,#d4af37,#f5d060);
    color:#0f2942;
    border:none;
    padding:16px 48px;
    border-radius:50px;
    font-size:18px;
    font-weight:900;
    cursor:pointer;
    transition:.25s;
    box-shadow:0 8px 30px rgba(212,175,55,.4);
    display:inline-flex; align-items:center; gap:10px;
    letter-spacing:.3px;
}
.spin-btn-main:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 12px 40px rgba(212,175,55,.6); }
.spin-btn-main:disabled { opacity:.5; cursor:not-allowed; transform:none; }

.wh-status { margin-top:20px; font-size:14px; color:rgba(255,255,255,.55); min-height:24px; }
.wh-status.used { color:#fc8181; font-weight:600; }

/* PRIZES TABLE */
.prizes-section { }
.prizes-card { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.08); border-radius:20px; padding:24px; margin-bottom:20px; backdrop-filter:blur(10px); }
.prizes-title { font-size:15px; font-weight:800; color:#d4af37; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
.prize-row { display:flex; align-items:center; justify-content:space-between; padding:10px 14px; border-radius:10px; margin-bottom:8px; transition:.2s; }
.prize-row:hover { background:rgba(255,255,255,.05); }
.prize-dot { width:14px; height:14px; border-radius:50%; flex-shrink:0; margin-right:10px; }
.prize-name { font-size:14px; color:rgba(255,255,255,.85); font-weight:600; flex:1; }
.prize-chance { font-size:12px; color:rgba(255,255,255,.4); font-weight:600; background:rgba(255,255,255,.06); padding:3px 10px; border-radius:20px; }

/* HISTORY */
.history-card { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.08); border-radius:20px; padding:24px; backdrop-filter:blur(10px); }
.history-title { font-size:15px; font-weight:800; color:#d4af37; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
.history-item { display:flex; justify-content:space-between; align-items:center; padding:12px 14px; border-radius:10px; margin-bottom:8px; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.06); }
.history-item-label { font-size:13px; color:rgba(255,255,255,.8); font-weight:600; }
.history-item-code { font-size:12px; font-weight:700; color:#d4af37; background:rgba(212,175,55,.1); padding:3px 10px; border-radius:6px; }
.history-item-date { font-size:11px; color:rgba(255,255,255,.35); }
.no-history { text-align:center; color:rgba(255,255,255,.35); font-size:13px; padding:20px 0; }

/* MODAL */
.prize-modal-bg { position:fixed; inset:0; background:rgba(0,0,0,.8); backdrop-filter:blur(8px); z-index:9999; display:none; align-items:center; justify-content:center; padding:20px; }
.prize-modal-bg.active { display:flex; }
.prize-modal { background:linear-gradient(160deg,#0f2942,#1a4a7a); border:1px solid rgba(212,175,55,.3); border-radius:28px; padding:48px 40px; max-width:480px; width:100%; text-align:center; animation:popIn .4s cubic-bezier(.34,1.56,.64,1); box-shadow:0 40px 100px rgba(0,0,0,.6); }
@keyframes popIn { from{transform:scale(.5);opacity:0} to{transform:scale(1);opacity:1} }
.prize-icon { font-size:72px; margin-bottom:20px; animation:bounce 1s ease .4s both; }
@keyframes bounce { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-16px)} }
.prize-modal h2 { font-size:28px; font-weight:900; color:#d4af37; margin:0 0 8px; }
.prize-modal p { color:rgba(255,255,255,.7); font-size:15px; margin:0 0 24px; }
.prize-code-box { background:rgba(212,175,55,.15); border:2px dashed rgba(212,175,55,.5); border-radius:14px; padding:16px 24px; margin-bottom:24px; }
.prize-code-box .code-label { font-size:12px; color:rgba(255,255,255,.5); text-transform:uppercase; letter-spacing:1px; margin-bottom:6px; }
.prize-code-box .code-val { font-size:28px; font-weight:900; color:#d4af37; letter-spacing:4px; }
.btn-copy-code { background:rgba(212,175,55,.2); border:1px solid rgba(212,175,55,.4); color:#d4af37; padding:8px 20px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; transition:.2s; display:inline-flex; align-items:center; gap:6px; margin-bottom:20px; }
.btn-copy-code:hover { background:rgba(212,175,55,.35); }
.btn-close-modal { background:linear-gradient(135deg,#d4af37,#f5d060); color:#0f2942; border:none; padding:14px 40px; border-radius:12px; font-size:16px; font-weight:800; cursor:pointer; transition:.2s; }
.btn-close-modal:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(212,175,55,.4); }

/* Confetti particles */
.confetti { position:fixed; inset:0; pointer-events:none; z-index:10000; overflow:hidden; }
.confetti-particle { position:absolute; width:8px; height:8px; border-radius:2px; animation:fall linear forwards; }
@keyframes fall { to { transform:translateY(110vh) rotate(720deg); opacity:0; } }

@media(max-width:900px) { .wh-layout { grid-template-columns:1fr; } .wh-header h1 { font-size:28px; } }
@media(max-width:500px) { canvas#wheelCanvas { width:280px!important; height:280px!important; } }
</style>

<?php
$canSpin = $canSpin ?? false;
// Prizes data (mirror PHP side for JS)
$prizes_js = [
    ['label' => 'May mắn\nlần sau',   'color' => '#1a4a7a', 'text' => '#ffffff'],
    ['label' => 'Giảm 5%',            'color' => '#d4af37', 'text' => '#0f2942'],
    ['label' => 'Giảm 10%',           'color' => '#16213e', 'text' => '#d4af37'],
    ['label' => 'Giảm\n50.000đ',      'color' => '#e53e3e', 'text' => '#ffffff'],
    ['label' => 'Giảm 15%',           'color' => '#2d6a4f', 'text' => '#ffffff'],
    ['label' => 'Giảm\n100.000đ',     'color' => '#7b2d8b', 'text' => '#ffffff'],
];
?>

<div class="wheel-page">
<div class="wheel-container">

    <!-- HEADER -->
    <div class="wh-header">
        <div class="wh-badge"><i class="fas fa-gift"></i> Trò chơi miễn phí – 1 lần/ngày</div>
        <h1>🎡 Vòng Quay May Mắn</h1>
        <p>Quay thử vận may – Nhận mã giảm giá hấp dẫn ngay hôm nay!</p>
    </div>

    <div class="wh-layout">
        <!-- WHEEL SECTION -->
        <div class="wheel-section">
            <div class="wheel-wrapper">
                <div class="wheel-pointer"></div>
                <canvas id="wheelCanvas" width="380" height="380"></canvas>
                <button class="spin-center" id="spinCenterBtn" <?= !$canSpin ? 'disabled' : '' ?>>
                    QUAY
                </button>
            </div>

            <button class="spin-btn-main" id="spinMainBtn" <?= !$canSpin ? 'disabled' : '' ?>>
                <i class="fas fa-dice"></i>
                <?= $canSpin ? 'QUAY NGAY' : 'Đã quay hôm nay' ?>
            </button>
            <div class="wh-status <?= !$canSpin ? 'used' : '' ?>" id="spinStatus">
                <?= $canSpin
                    ? '✨ Bạn còn 1 lượt quay hôm nay!'
                    : '⏰ Lượt quay đã dùng. Quay lại vào ngày mai nhé!' ?>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="prizes-section">
            <!-- Prize list -->
            <div class="prizes-card">
                <div class="prizes-title"><i class="fas fa-trophy"></i> Bảng phần thưởng</div>
                <?php
                $prizesMeta = [
                    ['dot'=>'#1a4a7a', 'name'=>'Chúc bạn may mắn lần sau', 'chance'=>'40%'],
                    ['dot'=>'#d4af37', 'name'=>'Mã giảm 5%',               'chance'=>'28%'],
                    ['dot'=>'#16213e', 'name'=>'Mã giảm 10%',              'chance'=>'17%'],
                    ['dot'=>'#e53e3e', 'name'=>'Mã giảm 50.000đ',          'chance'=>'10%'],
                    ['dot'=>'#2d6a4f', 'name'=>'Mã giảm 15%',              'chance'=>'4%'],
                    ['dot'=>'#7b2d8b', 'name'=>'Mã giảm 100.000đ',         'chance'=>'1%'],
                ];
                foreach ($prizesMeta as $pm):
                ?>
                <div class="prize-row">
                    <div class="prize-dot" style="background:<?= $pm['dot'] ?>"></div>
                    <span class="prize-name"><?= $pm['name'] ?></span>
                    <span class="prize-chance"><?= $pm['chance'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- History -->
            <div class="history-card">
                <div class="history-title"><i class="fas fa-history"></i> Lịch sử quay của bạn</div>
                <?php if (empty($lichSu)): ?>
                    <div class="no-history"><i class="fas fa-inbox" style="font-size:28px;margin-bottom:8px;display:block;"></i>Chưa có lịch sử quay nào</div>
                <?php else: ?>
                    <?php foreach (array_slice($lichSu, 0, 10) as $h): ?>
                    <div class="history-item">
                        <div>
                            <div class="history-item-label"><?= htmlspecialchars($h['phan_thuong']) ?></div>
                            <div class="history-item-date"><?= date('d/m/Y', strtotime($h['ngay_quay'])) ?></div>
                        </div>
                        <?php if ($h['ma_code']): ?>
                            <span class="history-item-code"><?= htmlspecialchars($h['ma_code']) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
</div>

<!-- PRIZE MODAL -->
<div class="prize-modal-bg" id="prizeModal">
    <div class="prize-modal">
        <div class="prize-icon" id="modalIcon">🎉</div>
        <h2 id="modalTitle">Chúc mừng!</h2>
        <p id="modalDesc">Bạn đã nhận được phần thưởng.</p>
        <div class="prize-code-box" id="codeBox" style="display:none;">
            <div class="code-label">Mã giảm giá của bạn</div>
            <div class="code-val" id="codeVal"></div>
            <div style="font-size:12px;color:rgba(255,255,255,.4);margin-top:6px;">Hạn sử dụng: 7 ngày · Dùng 1 lần</div>
        </div>
        <div id="copyBtnWrap" style="display:none;">
            <button class="btn-copy-code" onclick="copyCode()"><i class="fas fa-copy"></i> Sao chép mã</button>
        </div>
        <br>
        <button class="btn-close-modal" onclick="closeModal()">
            <i class="fas fa-check"></i> Tuyệt vời, đóng lại
        </button>
    </div>
</div>

<div class="confetti" id="confettiContainer"></div>

<script>
// =========================================================
//  WHEEL SETUP
// =========================================================
const prizes = <?= json_encode($prizes_js) ?>;
const NUM = prizes.length;
const ARC = (2 * Math.PI) / NUM;

const canvas = document.getElementById('wheelCanvas');
const ctx = canvas.getContext('2d');
const W = canvas.width;
const H = canvas.height;
const cx = W / 2, cy = H / 2, R = W / 2 - 8;

let currentAngle = 0;
let isSpinning = false;
let canSpin = <?= $canSpin ? 'true' : 'false' ?>;

function drawWheel(angle) {
    ctx.clearRect(0, 0, W, H);

    prizes.forEach((prize, i) => {
        const start = angle + i * ARC;
        const end   = start + ARC;

        // Slice
        ctx.beginPath();
        ctx.moveTo(cx, cy);
        ctx.arc(cx, cy, R, start, end);
        ctx.closePath();
        ctx.fillStyle = prize.color;
        ctx.fill();
        ctx.strokeStyle = 'rgba(255,255,255,.15)';
        ctx.lineWidth = 2;
        ctx.stroke();

        // Label
        ctx.save();
        ctx.translate(cx, cy);
        ctx.rotate(start + ARC / 2);
        ctx.textAlign = 'right';
        ctx.fillStyle = prize.text;
        ctx.font = 'bold 13px Inter, sans-serif';
        const lines = prize.label.split('\n');
        lines.forEach((line, li) => {
            ctx.fillText(line, R - 14, (li - (lines.length - 1) / 2) * 18);
        });
        ctx.restore();
    });

    // Center circle
    ctx.beginPath();
    ctx.arc(cx, cy, 38, 0, 2 * Math.PI);
    ctx.fillStyle = '#0f2942';
    ctx.fill();
    ctx.strokeStyle = 'rgba(212,175,55,.5)';
    ctx.lineWidth = 3;
    ctx.stroke();
}

drawWheel(currentAngle);

// =========================================================
//  SPIN LOGIC
// =========================================================
function spin(targetIndex) {
    const targetAngle = (2 * Math.PI) - (targetIndex * ARC + ARC / 2);
    const fullSpins   = (Math.floor(Math.random() * 4) + 6) * 2 * Math.PI;
    const finalAngle  = fullSpins + targetAngle;
    const duration    = 4500;
    const startTime   = performance.now();
    const startAngle  = currentAngle;

    function easeOut(t) {
        return 1 - Math.pow(1 - t, 4);
    }

    function animate(now) {
        const elapsed = now - startTime;
        const progress = Math.min(elapsed / duration, 1);
        currentAngle = startAngle + easeOut(progress) * finalAngle;
        drawWheel(currentAngle % (2 * Math.PI));

        if (progress < 1) {
            requestAnimationFrame(animate);
        } else {
            currentAngle = currentAngle % (2 * Math.PI);
            isSpinning = false;
        }
    }

    requestAnimationFrame(animate);
}

// =========================================================
//  API CALL
// =========================================================
function doSpin() {
    if (isSpinning || !canSpin) return;
    isSpinning = true;

    document.getElementById('spinMainBtn').disabled = true;
    document.getElementById('spinCenterBtn').disabled = true;
    document.getElementById('spinStatus').textContent = '🎡 Đang quay...';

    fetch('index.php?action=api/vongquay_spin', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ _token: 'wheel' })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            isSpinning = false;
            document.getElementById('spinStatus').textContent = '❌ ' + data.message;
            document.getElementById('spinStatus').className = 'wh-status used';
            document.getElementById('spinMainBtn').disabled = true;
            return;
        }

        // Spin to prize_index
        spin(data.prize_index);

        setTimeout(() => {
            showPrizeModal(data.prize_label, data.ma_code);
            canSpin = false;
            document.getElementById('spinMainBtn').textContent = '✅ Đã quay hôm nay';
            document.getElementById('spinStatus').className = 'wh-status used';
            document.getElementById('spinStatus').textContent = '⏰ Lượt quay đã dùng. Quay lại vào ngày mai nhé!';
        }, 5200);
    })
    .catch(() => {
        isSpinning = false;
        document.getElementById('spinStatus').textContent = '❌ Có lỗi xảy ra, vui lòng thử lại.';
    });
}

document.getElementById('spinMainBtn').addEventListener('click', doSpin);
document.getElementById('spinCenterBtn').addEventListener('click', doSpin);

// =========================================================
//  MODAL
// =========================================================
function showPrizeModal(label, code) {
    const modal = document.getElementById('prizeModal');
    const isWin = !!code;

    document.getElementById('modalIcon').textContent = isWin ? '🎉' : '😊';
    document.getElementById('modalTitle').textContent = isWin ? 'Chúc mừng! Bạn đã trúng thưởng!' : 'Chúc bạn may mắn lần sau!';
    document.getElementById('modalDesc').textContent = isWin
        ? `Bạn nhận được: ${label}`
        : 'Đừng nản lòng! Hãy quay lại vào ngày mai để thử vận may nhé 💪';

    if (isWin) {
        document.getElementById('codeVal').textContent = code;
        document.getElementById('codeBox').style.display = 'block';
        document.getElementById('copyBtnWrap').style.display = 'block';
        launchConfetti();
    } else {
        document.getElementById('codeBox').style.display = 'none';
        document.getElementById('copyBtnWrap').style.display = 'none';
    }

    modal.classList.add('active');
}

function closeModal() {
    document.getElementById('prizeModal').classList.remove('active');
}

function copyCode() {
    const code = document.getElementById('codeVal').textContent;
    navigator.clipboard.writeText(code).then(() => {
        const btn = document.querySelector('.btn-copy-code');
        btn.innerHTML = '<i class="fas fa-check"></i> Đã sao chép!';
        setTimeout(() => { btn.innerHTML = '<i class="fas fa-copy"></i> Sao chép mã'; }, 2000);
    });
}

// =========================================================
//  CONFETTI
// =========================================================
function launchConfetti() {
    const container = document.getElementById('confettiContainer');
    const colors = ['#d4af37','#f5d060','#e53e3e','#2ecc71','#3498db','#9b59b6','#fff'];
    for (let i = 0; i < 80; i++) {
        const p = document.createElement('div');
        p.className = 'confetti-particle';
        p.style.left = Math.random() * 100 + 'vw';
        p.style.top = '-10px';
        p.style.background = colors[Math.floor(Math.random() * colors.length)];
        p.style.width = (Math.random() * 8 + 6) + 'px';
        p.style.height = (Math.random() * 8 + 6) + 'px';
        p.style.borderRadius = Math.random() > .5 ? '50%' : '2px';
        p.style.animationDuration = (Math.random() * 2 + 2) + 's';
        p.style.animationDelay = Math.random() * 1.5 + 's';
        container.appendChild(p);
        setTimeout(() => p.remove(), 5000);
    }
}
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/views/layouts/user-layout.php';
?>
