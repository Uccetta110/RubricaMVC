<?php ob_start(); ?>

<div class="sms-container">
    <h2>📨 SMS Ricevuti</h2>
    
    <?php if (count($messaggi) > 0): ?>
        <div class="sms-list">
            <?php foreach ($messaggi as $msg): ?>
                <div class="sms-item <?= $msg['letto'] ? '' : 'unread' ?>">
                    <div class="sms-header">
                        <span class="sms-sender">
                            <?= htmlspecialchars($msg['mittente_nome'] ?? $msg['mittente']) ?>
                        </span>
                        <span class="sms-number"><?= htmlspecialchars($msg['mittente']) ?></span>
                        <span class="sms-date"><?= date('d/m/Y H:i', strtotime($msg['data_invio'])) ?></span>
                    </div>
                    <div class="sms-body">
                        <?= nl2br(htmlspecialchars($msg['messaggio'])) ?>
                    </div>
                    <div class="sms-actions">
                        <a href="?action=sms_compose&destinatario=<?= urlencode($msg['mittente']) ?>" class="btn-reply">
                            ↩️ Rispondi
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="result-box warning">
            📂 Nessun messaggio ricevuto
        </div>
    <?php endif; ?>
    
    <div style="text-align: center; margin-top: 20px;">
        <button class="btn-execute primary" onclick="location.href='?action=sms_compose'" style="display: inline-block; padding: 12px 30px;">
            ✉️ Invia Nuovo SMS
        </button>
        <button class="btn-execute secondary" onclick="location.href='?'" style="display: inline-block; padding: 12px 30px;">
            🏠 Torna alla Home
        </button>
    </div>
</div>

<?php 
$content = ob_get_clean();
$title = 'SMS Ricevuti';
include __DIR__ . '/layout.php';
?>
