-- Aggiungi queste tabelle al database 'telefono' esistente

-- Tabella per gli SMS
CREATE TABLE IF NOT EXISTS `sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mittente` varchar(20) NOT NULL,
  `destinatario` varchar(20) NOT NULL,
  `messaggio` text NOT NULL,
  `data_invio` datetime NOT NULL,
  `letto` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_destinatario` (`destinatario`),
  KEY `idx_mittente` (`mittente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabella per tracciare lo stato "sta scrivendo"
CREATE TABLE IF NOT EXISTS `typing_status` (
  `numero` varchar(20) NOT NULL,
  `destinatario` varchar(20) NOT NULL,
  `ultimo_aggiornamento` datetime NOT NULL,
  PRIMARY KEY (`numero`, `destinatario`),
  KEY `idx_destinatario` (`destinatario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserisci alcuni dati di esempio (opzionale)
-- Assicurati che esistano contatti nella rubrica prima di inserire SMS

INSERT INTO `sms` (`mittente`, `destinatario`, `messaggio`, `data_invio`, `letto`) VALUES
('333-1234567', '333-7654321', 'Ciao! Come stai?', NOW(), 0),
('333-7654321', '333-1234567', 'Tutto bene, grazie! E tu?', NOW(), 1);
