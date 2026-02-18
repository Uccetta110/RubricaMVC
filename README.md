struttura progetto:
/progetto <br>
│
├── public/
│   └── index.php
│
├── app/
│   ├── controllers/
│   │   └── HomeController.php
│   │
│   ├── models/
│   │   └── User.php
│   │
│   └── views/
│       ├── home.php
│       └── layout.php
│
├── config/
│   └── config.php
│
├── core/
│   ├── Router.php
│   ├── Controller.php
│   └── Database.php
│
└── .htaccess

public/ → Punto di ingresso

Contiene solo file accessibili dal browser.

app/controllers/ → Controller

Gestiscono le richieste.

app/models/ → Model

Gestiscono i dati e il database.

app/views/ → View

Contiene le pagine HTML/PHP.

core/ → Motore del framework

Qui metti le classi “tecniche”.

config/ → Configurazioni

Per database e variabili globali.

