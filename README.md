# SPOKI WHATSAPP
#### Autore: mattiawebdesigner
#### Email: mattiawebdesigner@gmail.com

<br />
Quest'API serve per implementara le funzionalità di gestione messaggi WhatsApp attraverso la piattaforma di SPOKI (https://spoki.it/).
<br /><br /><br />

Le classi da utilizzare sono tre:

- **SpokiBearerToken** <br />
Attraverso il metodo 
```
getToken(string $username, string $password)
```
è possibile ottenere il Token Bearer passando i propri username e password di SPOKI
- **SpokiContatto** <br />
Utile per la gestione del contatto al quale si vuole inviare un messaggio WhatsApp. <br />
Il costruttore accetta il numero di telefono (obbligatorio) e il nome e cognome (opzionali). 
Se il numero non è presente in rubrica allora viene aggiunto (sfruttando il nome e il cognome)
```
public function __construct($phone, $first_name="", $last_name="")
```
- **Spoki** <br />
Il cuore dell'API. <br />
Attraverso il costruttore va passato l'identificativo **uid**
```
public function __construct($uuid)
```
Attraverso il metodo
```
public function sendSingleMessage(object $contatto, string $message, string $url = "https://app.spoki.it/wh/ap/")
```
è possibile inviare un messaggio. Questo metodo ha 3 parametri obbligatori che sono:
1. **$contatto**: contatto del cliente (oggetto alla classe **SpokiContatto**)
2. **$message**: messaggio da inviare al cliente
3. **$url**: URL necessaria all'API. è un valore opzionale in quanto già indicato un URL di default
<br /><br />
Attraverso il metodo statico
```
public static function sendTemplateToClient(object $contact, string $template_id, array $custom_field = [])
 ```
 è anche possibile inviare un template message definito nel sito, e approvato da WhatsApp, ad un cliente. 
 Il metodo per funzionare correttamente ha bisogno dell'oggetto del contatto, dell'ID del template e di un campo opzionale (tipo array)
 se si vogliono passare dei parametri utili al template.
