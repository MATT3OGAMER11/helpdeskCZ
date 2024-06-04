# Consegna
Per il servizio o parte di esso messo in essere ad Informatica si realizzi endpoint REST (json) per un client mobile Flutter CRUD!

# Descrizione
L'applicazione di nome helpdesk si occupa delle segnalazioni di problemi all'interno dello zuccante, nella versione mobile l'utente può segnalare problemi riguardo:
 - aule
 - dispositivi all'interno delle aule

# Struttura del progetto
Il progetto è composto da 3 cartelle principali:
 - **model** contiene i model che rappresentano le entità del progetto
 - **localdb** contiene i file per la creazione e il funzionamento del dblocale con il pachetto floor
 - **widget** contiene i widget che verranno utilizzati all'interno dell'applicazione
 - main.dart e menu.dart sono i file principali dell'applicazione

# Package utilizzati
 - **http** per le chiamate REST
 - **floor** per la gestione del db locale
 - **random_string** per la generazione di stringhe randomiche
 - **flutter_session_manager** per la gestione delle sessioni
 - **qr_code_scanner** per la lettura dei QR code

# Strategie usate
- **localdb** nel database locale del dispositivo vengono salvate le informazioni relative a:
    - piani
    - aule
    - dispositivi<br>
   questa scelta è stata fatta per evitare di fare chiamate REST per ottenere queste informazioni ogni volta che l'utente apre l'applicazione, siccome queste informazioni vengono cambiate di rado, in caso l'utente creda di avere una versione con dati non aggiornati o per altri motivi tropa un opzione nel menù laterale per aggiornare i dati.

- **session_manager** per la gestione delle sessioni si è scelto di utilizzare il package flutter_session_manager, questo package permette di salvare le informazioni della sessione in modo sicuro e di recuperarle in modo semplice. Quando l'utente effettua il login vengono salvate le informazioni riguardanti l'utente loggato, inoltre viene salvato in un campo id una stringa generate casualmente, tutti gli id sessioni però avrà all'inizio la scritta FLUTTER perciò saranno composti da FLUTTERstringacasuale.

