<?php ob_start(); ?>

<div class="sms-compose">
    <h2>✉️ Nuovo Messaggio</h2>
    
    <?php if ($isTyping): ?>
        <div class="typing-indicator">
            ✍️ Il destinatario sta scrivendo...
        </div>
    <?php endif; ?>
    
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

// Notifica stato "sta scrivendo"
let typingTimer;
const typingDelay = 3000; // 3 secondi di inattività

messaggio.addEventListener('input', function() {
    const destinatario = document.getElementById('destinatario').value;
    
    if (destinatario && this.value.length > 0) {
        // Notifica che sto scrivendo
        sendTypingStatus(destinatario, true);
        
        // Reset del timer
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            sendTypingStatus(destinatario, false);
        }, typingDelay);
    }
});

// Quando cambio destinatario o invio il form, rimuovo lo stato
document.getElementById('destinatario').addEventListener('change', function() {
    const oldDest = this.dataset.oldValue || '';
    if (oldDest) {
        sendTypingStatus(oldDest, false);
    }
    this.dataset.oldValue = this.value;
});

document.getElementById('smsForm').addEventListener('submit', function() {
    const destinatario = document.getElementById('destinatario').value;
    if (destinatario) {
        sendTypingStatus(destinatario, false);
    }
});

function sendTypingStatus(destinatario, isTyping) {
    fetch('?action=sms_typing', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'destinatario=' + encodeURIComponent(destinatario) + '&typing=' + (isTyping ? '1' : '0')
    });
}

// Controlla periodicamente se il destinatario sta scrivendo
<?php if ($destinatario): ?>
setInterval(function() {
    const destinatario = document.getElementById('destinatario').value;
    if (destinatario) {
        fetch('?action=sms_check_typing&destinatario=' + encodeURIComponent(destinatario))
            .then(response => response.json())
            .then(data => {
                const indicator = document.querySelector('.typing-indicator');
                if (data.typing) {
                    if (!indicator) {
                        const div = document.createElement('div');
                        div.className = 'typing-indicator';
                        div.textContent = '✍️ Il destinatario sta scrivendo...';
                        document.querySelector('.sms-compose h2').after(div);
                    }
                } else if (indicator) {
                    indicator.remove();
                }
            });
    }
}, 2000); // Controlla ogni 2 secondi
<?php endif; ?>
</script>

<?php 
$content = ob_get_clean();
$title = 'Nuovo Messaggio';
include __DIR__ . '/../layout.php';
?>
