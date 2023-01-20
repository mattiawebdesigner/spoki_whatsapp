<?php
/**
 * Questa classe viene utilizzata per la creazione
 * di un contatto per l'invio di messaggi WhatsApp
 * automatici con le API di Spoki.
 */
class SpokiContatto{
    /**
     * Codice unico identificativo
     */
    private $uid;
    /**
     * Codice del contatto su SPOKI
     */
    private $account_code;
    /**
     * Codice del contatto registrato
     * su Spoki
     */
    private $contact_id;
    /**
     * Nome del contatto
     */
    private $first_name;
    /**
     * Cognome del contatto
     */
    private $last_name;
    /**
     * Numero di telefono del contatto e prefisso
     */
    private $phone;
    /**
     * Email del contatto
     */
    private $email;

    /**
     * Setta gli attributi con i valori
     * passati attraverso i parametri
     * 
     * @param array $phone 
     *                  Telefono del contatto con relativo prefisso
     */
    public function __construct($phone, $first_name="", $last_name="")
    {

        $this->phone        = $phone;
        $this->first_name   = $first_name;
        $this->last_name    = $last_name;

        $this->autoCompile();

    }

    /**
     * Compila automaticamente gli attributi attraverso la
     * lettura dell'ID
     */
    private function autoCompile(){
        $this->readResponseBySpoki();
    }

    /**
     * Interroga il server di spoki per reperire
     * i dati del contatto.
     * 
     * Per leggere i dati del contatto viene inviato
     * il suo numero.
     * 
     * <strong>Attenzione: </strong> Potrebbero essere restituiti
     * più contatti nel caso in cui si cerca per esempio un numero
     * parziale
     */
    private function readResponseBySpoki(){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://app.spoki.it/api/contacts/?is_deleted=False&search={$this->phone}&page_size=100&page=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'Accept-Language: it-IT,it;q=0.9,en-US;q=0.8,en;q=0.7';
        $headers[] = 'Authorization: Bearer '.SPOKI_AUTHORIZATION_BEARER;
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Origin: https://spoki.app';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Referer: https://spoki.app/';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
        $headers[] = 'X-Spoki-Account: '.$this->account_code;
        $headers[] = 'X-Spoki-Platform-Version: 3.7.1';
        $headers[] = 'Sec-Ch-Ua: \"Not?A_Brand\";v=\"8\", \"Chromium\";v=\"108\", \"Google Chrome\";v=\"108\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"'.$_SERVER['HTTP_SEC_CH_UA_PLATFORM'].'\"';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $result = json_decode($result);
        if (curl_errno($ch)) {
            //echo 'Error:' . curl_error($ch);
        }else{
            $result = $result->results[0];

            if(!empty($result)){//C'è un riscontro
                //popolo i dati del contatto
                $this->first_name   = $result->first_name;
                $this->last_name    = $result->last_name;
                $this->phone        = $result->phone;
                $this->email        = $result->email;
                $this->uid          = $result->uid;
                $this->account_code = $result->account;
                $this->contact_id   = $result->id;
            }else{//Numero di telefono non trovato, va creato
                return false;
                //$this->createContact();
            }

            // echo "<pre>";
            // var_dump($this->first_name);
            // var_dump($this->last_name);
            // var_dump($this->email);
            // var_dump($this->phone);
            // var_dump($this->uid);
            // var_dump($this->account_code);
            // var_dump($this->contact_id);
            // print_r($result);
            // echo "</pre>";
        }
        curl_close($ch);
    }

    /**
     * Crea un nuovo contatto su Spoki
     */
    private function createContact(){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://app.spoki.it/api/contacts/sync/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"phone\":\"".$this->phone."\",\"first_name\":\"".$this->first_name."\",\"last_name\":\"".$this->last_name."\"}");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'Accept-Language: it-IT,it;q=0.9,en-US;q=0.8,en;q=0.7';
        $headers[] = 'Authorization: Bearer '.SPOKI_AUTHORIZATION_BEARER;
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Origin: https://spoki.app';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Referer: https://spoki.app/';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
        $headers[] = 'X-Spoki-Account: '.$this->account_code;
        $headers[] = 'X-Spoki-Platform-Version: 3.7.1';
        $headers[] = 'Sec-Ch-Ua: \"Not?A_Brand\";v=\"8\", \"Chromium\";v=\"108\", \"Google Chrome\";v=\"108\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"'.$_SERVER['HTTP_SEC_CH_UA_PLATFORM'].'\"';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }else{
            //Se non ci sono errori invio il template
            //di presentazione
            $contact = new SpokiContatto($this->phone);

            Spoki::sendTemplateToClient($contact, "");
        }

        // echo "<pre>";
        // print_r($result);
        // echo "</pre>";

        curl_close($ch);
    }

    //Metodi get
    public function getUid(){return $this->uid;}
    public function getContactId(){return $this->contact_id;}
    public function getAccountCode(){return $this->account_code;}
    public function getFirstName(){return $this->first_name;}
    public function getLastName(){return $this->last_name;}
    public function getPhone(){return $this->phone;}
    public function getEmail(){return $this->email;}
}