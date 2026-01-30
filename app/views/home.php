<?php ob_start(); ?>

<?php if ($mioNumero): ?>
    <div class="menu-actions">
        <h2>Seleziona un'azione</h2>
        <div class="action-grid">
            <a href="?action=rubrica" class="action-card">
                <span class="icon">📒</span>
                <span class="label">Rubrica</span>
            </a>
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
        </div>
    </div>
<?php else: ?>
    <div class="welcome-message">
        <h2>Benvenuto!</h2>
        <p>Per utilizzare la rubrica e inviare SMS, devi prima collegarti.</p>
        <a href="?action=login" class="submit-btn">Collegati</a>
    </div>
<?php endif; ?>

<?php 
$content = ob_get_clean();
$title = 'Home - Rubrica';
include __DIR__ . '/layout.php';
?>
