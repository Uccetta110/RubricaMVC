<?php ob_start(); ?>

<div class="result-box success">
    <h3>📞 Contatti in Rubrica</h3>
    <?php if (count($contatti) > 0): ?>
        <div class="contacts-list">
            <?php foreach ($contatti as $contatto): ?>
                <div class="contact-item">
                    <span class="contact-id">#<?= $contatto['id'] ?></span>
                    <span class="contact-name"><?= htmlspecialchars($contatto['nome']) ?></span>
                    <span class="contact-number"><?= htmlspecialchars($contatto['numero']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: #666; margin-top: 15px;">📂 Nessun contatto trovato</p>
    <?php endif; ?>
</div>

<div style="text-align: center; margin-top: 20px;">
    <button class="btn-execute primary" onclick="location.href='?action=rubrica'" style="display: inline-block; padding: 12px 30px;">
        🔄 Nuova Operazione
    </button>
    <button class="btn-execute secondary" onclick="location.href='?'" style="display: inline-block; padding: 12px 30px;">
        🏠 Torna alla Home
    </button>
</div>

<?php 
$content = ob_get_clean();
$title = 'Visualizza Contatti';
include __DIR__ . '/../layout.php';
?>
