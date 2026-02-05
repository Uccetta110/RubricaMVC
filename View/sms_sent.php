<?php ob_start(); ?>

<div class="sms-sent-confirmation">
    <?php if ($success): ?>
        <div class="result-box success">
            <h3>✅ Messaggio Inviato!</h3>
            <p>Il tuo SMS è stato inviato con successo.</p>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <button class="btn-execute primary" onclick="location.href='?action=sms_compose&destinatario=<?= urlencode($destinatario) ?>'" style="display: inline-block; padding: 12px 30px;">
                ↩️ Invia un altro messaggio
            </button>
            <button class="btn-execute secondary" onclick="location.href='?action=sms_inbox'" style="display: inline-block; padding: 12px 30px;">
                📨 Vai agli SMS ricevuti
            </button>
            <button class="btn-execute secondary" onclick="location.href='?'" style="display: inline-block; padding: 12px 30px;">
                🏠 Torna alla Home
            </button>
        </div>
    <?php else: ?>
        <div class="result-box error">
            <h3>❌ Errore</h3>
            <p>Si è verificato un errore nell'invio del messaggio.</p>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <button class="btn-execute primary" onclick="location.href='?action=sms_compose'" style="display: inline-block; padding: 12px 30px;">
                🔄 Riprova
            </button>
            <button class="btn-execute secondary" onclick="location.href='?'" style="display: inline-block; padding: 12px 30px;">
                🏠 Torna alla Home
            </button>
        </div>
    <?php endif; ?>
</div>

<?php 
$content = ob_get_clean();
$title = 'Messaggio Inviato';
include __DIR__ . '/layout.php';
?>
