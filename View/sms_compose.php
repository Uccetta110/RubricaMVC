<?php ob_start(); ?>

<div class="sms-compose">
    <h2>✉️ Nuovo Messaggio</h2>
    
    <form method="POST" action="?action=sms_send" id="smsForm">
        <div class="form-group">
            <label>Destinatario</label>
            <select name="destinatario" id="destinatario" required>
                <option value="">Seleziona un contatto</option>
                <?php foreach ($contatti as $contatto): ?>
                    <?php if ($contatto['numero'] != $mioNumero): ?>
                        <option value="<?= htmlspecialchars($contatto['numero']) ?>" 
                                <?= $contatto['numero'] == $destinatario ? 'selected' : '' ?>>
                            <?= htmlspecialchars($contatto['nome']) ?> - <?= htmlspecialchars($contatto['numero']) ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Messaggio</label>
            <textarea name="messaggio" id="messaggio" rows="5" placeholder="Scrivi il tuo messaggio..." required></textarea>
            <small class="char-counter"><span id="charCount">0</span> caratteri</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-execute primary">📤 Invia</button>
            <button type="button" class="btn-execute secondary" onclick="location.href='?'">Annulla</button>
        </div>
    </form>
</div>

<script>
// Contatore caratteri
const messaggio = document.getElementById('messaggio');
const charCount = document.getElementById('charCount');

messaggio.addEventListener('input', function() {
    charCount.textContent = this.value.length;
});
</script>

<?php 
$content = ob_get_clean();
$title = 'Nuovo Messaggio';
include __DIR__ . '/layout.php';
?>
