<?php ob_start(); ?>

<div class="login-form">
    <h2>🔐 Collegati</h2>
    <p>Inserisci il tuo numero di telefono per accedere</p>
    
    <?php if (isset($error)): ?>
        <div class="result-box error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" action="?action=login">
        <div class="form-group">
            <label>Numero di telefono</label>
            <input type="text" name="numero" placeholder="Es: 333-1234567" required>
        </div>
        <button type="submit" class="submit-btn">Collegati</button>
    </form>
    
    <div style="margin-top: 20px; text-align: center;">
        <a href="?" class="text-link">← Torna alla home</a>
    </div>
</div>

<?php 
$content = ob_get_clean();
$title = 'Login - Rubrica';
include __DIR__ . '/layout.php';
?>
