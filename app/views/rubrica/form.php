<?php ob_start(); ?>

<?php if (!$showForm): ?>
    <form method="POST" action="?action=rubrica">
        <div class="radio-group">
            <input type="radio" id="aggiungi" name="azione" value="add" required>
            <label for="aggiungi">✨ Aggiungi</label>

            <input type="radio" id="visualizza" name="azione" value="show">
            <label for="visualizza">📋 Visualizza</label>

            <input type="radio" id="modifica" name="azione" value="mod">
            <label for="modifica">🔧 Modifica</label>

            <input type="radio" id="elimina" name="azione" value="del">
            <label for="elimina">❌ Elimina</label>
        </div>
        <button type="submit" class="submit-btn">Continua →</button>
    </form>
<?php else: ?>
    <div class="data-form">
        <?php if ($selectedAction == "add"): ?>
            <h3>✨ Aggiungi Nuovo Contatto</h3>
            <form method="POST" action="?action=rubrica">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" placeholder="Es: Mario Rossi" required>
                </div>
                <div class="form-group">
                    <label>Numero di telefono</label>
                    <input type="text" name="numero" placeholder="Es: 333-1234567" required>
                </div>
                <input type="hidden" name="execute" value="add">
                <div class="form-actions">
                    <button type="submit" class="btn-execute primary">Aggiungi</button>
                    <button type="button" class="btn-execute secondary" onclick="location.href='?action=rubrica'">Annulla</button>
                </div>
            </form>
        
        <?php elseif ($selectedAction == "show"): ?>
            <h3>📋 Visualizza Tutti i Contatti</h3>
            <form method="POST" action="?action=rubrica">
                <input type="hidden" name="execute" value="show">
                <div class="form-actions">
                    <button type="submit" class="btn-execute primary">Mostra Contatti</button>
                    <button type="button" class="btn-execute secondary" onclick="location.href='?action=rubrica'">Indietro</button>
                </div>
            </form>
        
        <?php elseif ($selectedAction == "mod"): ?>
            <h3>✏️ Modifica Contatto</h3>
            <form method="POST" action="?action=rubrica">
                <div class="form-group">
                    <label>ID del contatto</label>
                    <input type="number" name="id" placeholder="Es: 1" required>
                </div>
                <div class="form-group">
                    <label>Nuovo Nome</label>
                    <input type="text" name="nome" placeholder="Es: Mario Rossi" required>
                </div>
                <div class="form-group">
                    <label>Nuovo Numero</label>
                    <input type="text" name="numero" placeholder="Es: 333-1234567" required>
                </div>
                <input type="hidden" name="execute" value="mod">
                <div class="form-actions">
                    <button type="submit" class="btn-execute primary">Modifica</button>
                    <button type="button" class="btn-execute secondary" onclick="location.href='?action=rubrica'">Annulla</button>
                </div>
            </form>
        
        <?php elseif ($selectedAction == "del"): ?>
            <h3>🗑️ Elimina Contatto</h3>
            <form method="POST" action="?action=rubrica">
                <div class="form-group">
                    <label>ID del contatto da eliminare</label>
                    <input type="number" name="id" placeholder="Es: 1" required>
                </div>
                <input type="hidden" name="execute" value="del">
                <div class="form-actions">
                    <button type="submit" class="btn-execute primary">Elimina</button>
                    <button type="button" class="btn-execute secondary" onclick="location.href='?action=rubrica'">Annulla</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (!empty($output)): ?>
    <div class="result-box <?= $output['type'] ?>">
        <?= $output['message'] ?>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <button class="btn-execute primary" onclick="location.href='?action=rubrica'" style="display: inline-block; padding: 12px 30px;">
            🔄 Nuova Operazione
        </button>
        <button class="btn-execute secondary" onclick="location.href='?'" style="display: inline-block; padding: 12px 30px;">
            🏠 Torna alla Home
        </button>
    </div>
<?php endif; ?>

<?php 
$content = ob_get_clean();
$title = 'Rubrica';
include __DIR__ . '/../layout.php';
?>
