<?php
session_start();


$conn = new mysqli("127.0.0.1", "root", "", "telefono");
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}


$output = '';
$showForm = false;
$selectedAction = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST["azione"]) && !isset($_POST["execute"])) {
        $selectedAction = $_POST["azione"];
        $showForm = true;
    }
    
    if (isset($_POST["execute"])) {
        $azione = $_POST["execute"];
        
        switch ($azione) {
            
            case "show":
                $sql = "SELECT id, nome, numero FROM rubrica ORDER BY id";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    $output .= '<div class="result-box success">';
                    $output .= '<h3>📞 Contatti in Rubrica</h3>';
                    $output .= '<div class="contacts-list">';
                    while ($row = $result->fetch_assoc()) {
                        $output .= '<div class="contact-item">';
                        $output .= '<span class="contact-id">#' . $row['id'] . '</span>';
                        $output .= '<span class="contact-name">' . htmlspecialchars($row['nome']) . '</span>';
                        $output .= '<span class="contact-number">' . htmlspecialchars($row['numero']) . '</span>';
                        $output .= '</div>';
                    }
                    $output .= '</div></div>';
                } else {
                    $output .= '<div class="result-box warning">📂 Nessun contatto trovato</div>';
                }
                break;
            
            case "add":
                $nome = $_POST["nome"] ?? "";
                $numero = $_POST["numero"] ?? "";
                
                if (!empty($nome) && !empty($numero)) {
                    $stmt = $conn->prepare("INSERT INTO rubrica (nome, numero) VALUES (?, ?)");
                    $stmt->bind_param("ss", $nome, $numero);
                    
                    if ($stmt->execute()) {
                        $output .= '<div class="result-box success">✓ Contatto aggiunto con successo!</div>';
                    } else {
                        $output .= '<div class="result-box error">✗ Errore nell\'aggiunta</div>';
                    }
                    $stmt->close();
                } else {
                    $output .= '<div class="result-box error">⚠ Compila tutti i campi</div>';
                }
                break;
            
            case "mod":
                $id = $_POST["id"] ?? "";
                $nome = $_POST["nome"] ?? "";
                $numero = $_POST["numero"] ?? "";
                
                if (!empty($id) && !empty($nome) && !empty($numero)) {
                    $stmt = $conn->prepare("UPDATE rubrica SET nome=?, numero=? WHERE id=?");
                    $stmt->bind_param("ssi", $nome, $numero, $id);
                    
                    if ($stmt->execute() && $stmt->affected_rows > 0) {
                        $output .= '<div class="result-box success">✓ Contatto modificato con successo!</div>';
                    } else {
                        $output .= '<div class="result-box warning">⚠ Nessuna modifica effettuata o ID non trovato</div>';
                    }
                    $stmt->close();
                } else {
                    $output .= '<div class="result-box error">⚠ Compila tutti i campi</div>';
                }
                break;
            
            case "del":
                $id = $_POST["id"] ?? "";
                
                if (!empty($id)) {
                    $stmt = $conn->prepare("DELETE FROM rubrica WHERE id=?");
                    $stmt->bind_param("i", $id);
                    
                    if ($stmt->execute() && $stmt->affected_rows > 0) {
                        $output .= '<div class="result-box success">✓ Contatto eliminato con successo!</div>';
                    } else {
                        $output .= '<div class="result-box warning">⚠ ID non trovato</div>';
                    }
                    $stmt->close();
                } else {
                    $output .= '<div class="result-box error">⚠ Inserisci un ID valido</div>';
                }
                break;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rubrica Telefonica</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            margin-bottom: 5px;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .main-card {
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            animation: slideIn 0.4s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .radio-group {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .radio-group input[type="radio"] {
            display: none;
        }

        .radio-group label {
            padding: 20px;
            font-size: 18px;
            font-weight: 600;
            background: linear-gradient(135deg, #e0f7f4 0%, #b2dfdb 100%);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            border: 3px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 80px;
        }

        .radio-group label:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .radio-group input[type="radio"]:checked + label {
            background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%);
            color: white;
            border-color: #06beb6;
            box-shadow: 0 5px 20px rgba(6, 190, 182, 0.4);
        }

        .submit-btn {
            width: 100%;
            padding: 18px;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%);
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(6, 190, 182, 0.4);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(6, 190, 182, 0.6);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Form per inserimento dati */
        .data-form {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .data-form h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 1.4em;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #06beb6;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-execute {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-execute.primary {
            background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%);
            color: white;
        }

        .btn-execute.secondary {
            background: #e0e0e0;
            color: #333;
        }

        .btn-execute:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        /* Risultati */
        .result-box {
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
            font-size: 16px;
            animation: fadeIn 0.4s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .result-box.success {
            background: #d4edda;
            border: 2px solid #28a745;
            color: #155724;
        }

        .result-box.error {
            background: #f8d7da;
            border: 2px solid #dc3545;
            color: #721c24;
        }

        .result-box.warning {
            background: #fff3cd;
            border: 2px solid #ffc107;
            color: #856404;
        }

        .result-box h3 {
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .contacts-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .contact-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            display: grid;
            grid-template-columns: 50px 1fr auto;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .contact-id {
            background: #06beb6;
            color: white;
            padding: 5px 10px;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
        }

        .contact-name {
            font-weight: 600;
            color: #333;
            font-size: 1.1em;
        }

        .contact-number {
            color: #666;
            font-family: 'Courier New', monospace;
            font-size: 1.05em;
        }

        @media (max-width: 600px) {
            .radio-group {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>☎️ Rubrica Telefonica</h1>
            <p>Gestisci i tuoi contatti</p>
        </div>

        <div class="main-card">
            <?php if (!$showForm): ?>
                <form method="POST">
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
                        <form method="POST">
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
                                <button type="button" class="btn-execute secondary" onclick="location.href='index.php'">Annulla</button>
                            </div>
                        </form>
                    
                    <?php elseif ($selectedAction == "show"): ?>
                        <h3>📋 Visualizza Tutti i Contatti</h3>
                        <form method="POST">
                            <input type="hidden" name="execute" value="show">
                            <div class="form-actions">
                                <button type="submit" class="btn-execute primary">Mostra Contatti</button>
                                <button type="button" class="btn-execute secondary" onclick="location.href='index.php'">Indietro</button>
                            </div>
                        </form>
                    
                    <?php elseif ($selectedAction == "mod"): ?>
                        <h3>✏️ Modifica Contatto</h3>
                        <form method="POST">
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
                                <button type="button" class="btn-execute secondary" onclick="location.href='index.php'">Annulla</button>
                            </div>
                        </form>
                    
                    <?php elseif ($selectedAction == "del"): ?>
                        <h3>🗑️ Elimina Contatto</h3>
                        <form method="POST">
                            <div class="form-group">
                                <label>ID del contatto da eliminare</label>
                                <input type="number" name="id" placeholder="Es: 1" required>
                            </div>
                            <input type="hidden" name="execute" value="del">
                            <div class="form-actions">
                                <button type="submit" class="btn-execute primary">Elimina</button>
                                <button type="button" class="btn-execute secondary" onclick="location.href='index.php'">Annulla</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($output)): ?>
                <?php echo $output; ?>
                <div style="text-align: center; margin-top: 20px;">
                    <button class="btn-execute primary" onclick="location.href='index.php'" style="display: inline-block; padding: 12px 30px;">
                        🏠 Torna alla Home
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>