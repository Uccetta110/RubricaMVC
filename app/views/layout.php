<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Rubrica Telefonica' ?></title>
    <link rel="stylesheet" href="/style/css/style.css">
</head>
<body>
    <div class="container" style="place-items: center;">
        <div class="header">
            <h1>☎️ Rubrica Telefonica</h1>
            <p>Gestisci i tuoi contatti e messaggi</p>
            <?php if (isset($_SESSION['mio_numero'])): ?>
                <div class="user-info">
                    <span>📱 Connesso come: <strong><?= htmlspecialchars($_SESSION['mio_nome'] ?? $_SESSION['mio_numero']) ?></strong></span>
                    <a href="?action=logout" class="logout-btn">Esci</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="main-card" style="max-width: 500px;">
            <?= $content ?>
        </div>
    </div>
</body>
</html>
