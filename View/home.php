<?php ob_start(); ?>

<div class="menu-actions">
    <h2>Seleziona un'azione</h2>
    <div class="action-grid">
        <a href="?action=rubrica" class="action-card">
            <span class="icon">📒</span>
            <span class="label">Rubrica</span>
        </a>
        
        <?php if ($mioNumero): ?>
            <a href="?action=sms_inbox" class="action-card">
                <span class="icon">📨</span>
                <span class="label">SMS Ricevuti</span>
                <?php if ($unreadCount > 0): ?>
                    <span class="badge"><?= $unreadCount ?></span>
                <?php endif; ?>
            </a>
            <a href="?action=sms_compose" class="action-card">
                <span class="icon">✉️</span>
                <span class="label">Invia SMS</span>
            </a>
        <?php else: ?>
            <a href="?action=login" class="action-card">
                <span class="icon">🔐</span>
                <span class="label">Collegati</span>
            </a>
        <?php endif; ?>
    </div>
</div>



<?php 
$content = ob_get_clean();
$title = 'Home - Rubrica';
include __DIR__ . '/layout.php';
?>
