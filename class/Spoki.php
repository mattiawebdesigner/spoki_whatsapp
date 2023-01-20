<?php

/**
 * Gestisce le API Spoki per la messaggistica WhatsApp.
 * 
 * Le API di spoki vengono verificate attraverso una API_KEY 
 * ({{Spoki-Api-Key}}) che comunque non è neccessaria per l'invio 
 * di messaggi attraverso il metodo POST che necessita di un 
 * identificativo <strong>uuid</strong>.
 *  
 */
class Spoki
{
    private $uuid;

    /**
     * Setta i parametri fondamentali all'utilizzo
     * dell'API.
     * 
     * @param $uuid 
     *        Codice Unico Identificativo.
     *        Questo codice viene utilizzato nell'URL
     *        per inviare la richiesta di utilizzo
     *        dell'API ai server di <strong>Spoki</strong>
     */
    public function __construct($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Invia il messaggio ad un unico contatto
     * 
     * @param object $contatto Contatto al quale inviare il messaggio WhatsApp
     * @param string $message Messaggio da inviare al cliente
     * @param string $url URL alla quale inviare il messaggio POST
     * 
     * @return string|bool
     *          Restituisce una stringa di risposta, se esiste.
     *          Portebbe restituire true in caso di successo o 
     *          false in caso di fallimento
     */
    public function sendSingleMessage(object $contatto, string $message, string $url = "https://app.spoki.it/wh/ap/") /*: string|bool*/
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://app.spoki.it/api/messages/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"text\":\"" . $message . "\",\"type\":1,\"account\":" . $contatto->getAccountCode() . ",\"to_phone\":\"" . $contatto->getPhone() . "\",\"media_set\":[],\"contact\":" . $contatto->getContactId() . "}");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'Accept-Language: it-IT,it;q=0.9,en-US;q=0.8,en;q=0.7';
        $headers[] = 'Authorization: Bearer ' . SPOKI_AUTHORIZATION_BEARER;
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
        $headers[] = 'X-Spoki-Account: '.$contatto->getAccountCode();
        $headers[] = 'X-Spoki-Platform-Version: 3.7.1';
        $headers[] = 'Sec-Ch-Ua: \"Not?A_Brand\";v=\"8\", \"Chromium\";v=\"108\", \"Google Chrome\";v=\"108\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"'.$_SERVER['HTTP_SEC_CH_UA_PLATFORM'].'\"';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }

    /**
     * Invia un template al cliente.
     * 
     * Il template da inviare deve essere precedentemente
     * creato su SPOKI (da li recuperare il suo ID) e 
     * approvato da WhatsApp secondo le politiche internazionali
     * 
     * @param object $contact Contatto al quale inviare il template
     * @param string $template_id Codice identificativo del template
     * @param array $custom_field 
     *              Parametri opzionali gestiti come array associativo:
     *                  nome_parametro => valore_parametro
     *              Viene utilizzato SOLO nei template che ammettono
     *              parametri dinamici, di default è un array vuoto
     */
    public static function sendTemplateToClient(object $contact, string $template_id, array $custom_field = [])
    {
        $url = "https://app.spoki.it/api/messages/";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json, text/plain, */*",
            "Accept-Language: it-IT,it;q=0.9,en-US;q=0.8,en;q=0.7,la;q=0.6",
            "Authorization: Bearer ".SPOKI_AUTHORIZATION_BEARER,
            "Cache-Control: no-cache",
            "Connection: keep-alive",
            "Content-Type: application/json",
            "Origin: https://spoki.app",
            "Pragma: no-cache",
            "Referer: https://spoki.app/",
            "Sec-Fetch-Dest: empty",
            "Sec-Fetch-Mode: cors",
            "Sec-Fetch-Site: cross-site",
            "User-Agent: ".$_SERVER['HTTP_USER_AGENT'],
            "X-Spoki-Account: ".$contact->getAccountCode(),
            "X-Spoki-Platform-Version: 3.7.1",
            "sec-ch-ua: \"Not?A_Brand\";v=\"8\", \"Chromium\";v=\"108\", \"Google Chrome\";v=\"108\"",
            "sec-ch-ua-mobile: ?0",
            "sec-ch-ua-platform: \"".$_SERVER['HTTP_SEC_CH_UA_PLATFORM']."\"",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = '{"text":"","type":2,"account":'.$contact->getAccountCode().',"to_phone":"'.$contact->getPhone().'","template":'.$template_id;
        $data .= ', "custom_fields":{';

        //creazione dei cusom fields
        $i = 0;
        foreach($custom_field as $k => $v){
            $data .= '"'.$k.'":"'.$v.'"';
            if(++ $i< sizeof($custom_field)){
                $data .= ',';
            }
        }
        $data .= '},"media_set":[],"contact":'.$contact->getContactId().'}';
        //--------------

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $resp = curl_exec($curl);
        curl_close($curl);
        var_dump($resp);
    }

    /**
     * Restituisce il codice univoco
     * 
     * @return string Codice Univoco Identificativo dell'API
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Realizza una stringa JSON
     * 
     * @param array $arr Array da convertire in unsa stringa JSON
     * @return string Stringa convertita in JSON
     */
    private function createJsonString(array $arr)
    {
        $result = "{";

        $cont = 0; //conta i cicli per aver un controllo sull'ultimo elemento dell'array
        foreach ($arr as $key => $val) {
            $result .= '"';
            $result .= $key;
            $result .= '" : "';
            $result .= $val . '"';

            if (++$cont < sizeof($arr)) {
                $result .= ", ";
            }
        }
        $result .= "}";

        return $result;
    }
}
