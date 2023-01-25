<?php
class SpokiBearerToken
{
    private function __construct()
    {
    }

    /**
     * Restituisce il token Bearer ottenuto dalla login
     * a SPOKI.
     * 
     * @param string $username Nome utente del sito
     * @param string $password Password utente del sito
     * @return array Bearer Token e Account code
     */
    public static function getToken(string $username, string $password)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://app.spoki.it/o/token/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept' => 'application/json, text/plain, */*',
            'Accept-Language' => 'it-IT,it;q=0.9,en-US;q=0.8,en;q=0.7,la;q=0.6',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'Content-Type' => 'multipart/form-data; boundary=----WebKitFormBoundaryHemfL2nXTAhTLTUz',
            'Origin' => 'https://spoki.app',
            'Pragma' => 'no-cache',
            'Referer' => 'https://spoki.app/',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'cross-site',
            'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
            'X-Spoki-Platform-Version' => '3.7.1',
            'sec-ch-ua' => '"Not?A_Brand";v="8", "Chromium";v="108", "Google Chrome";v="108"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => '"'.$_SERVER['HTTP_SEC_CH_UA_PLATFORM'].'"',
            'Accept-Encoding' => 'gzip',
        ]);

        $data = array(
            'username' => $username,
            'password' => $password,
            'client_id' => 'GmmuED7kFgRqdsfcqMR0g6Lsn5t0VLui7dopU7DM',
            'client_secret' => 'Qf0Bb2mnL28H46Ub8tUTLhqWUnTfsJ3dqBui9EqNgGzVvQtlH1YnQqhqcS8pioNIbz5uBFMexDLcE9GoOmDcI1IPMVl7Zr1ucmgJ6BHDFBBPXJqxUG9ArxcYkB14BfMe',
            'grant_type' => 'password'
        );

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($ch);
        
        curl_close($ch);

        

        $decode_result = json_decode($response);

        return [
            'access_token' => $decode_result->access_token,
            'account' => self::getAccountCode($decode_result->access_token),
        ];
    }

    /**
     * Restituisce il codice dell'account appena loggato
     * su SPOKI
     * 
     * @param string $token Token generato per l'accesso
     * @return string Codice dell'account loggato
     */
    private static function getAccountCode(string $token){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://app.spoki.it/api/users/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'Accept-Language: it-IT,it;q=0.9';
        $headers[] = 'Authorization: Bearer '.$token;
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Origin: https://spoki.app';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Referer: https://spoki.app/';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'User-Agent: '.$_SERVER['HTTP_USER_AGENT'];
        $headers[] = 'X-Spoki-Platform-Version: 3.7.3';
        $headers[] = 'Sec-Ch-Ua: \"Not_A Brand\";v=\"99\", \"Google Chrome\";v=\"109\", \"Chromium\";v=\"109\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"'.$_SERVER['HTTP_SEC_CH_UA_PLATFORM'].'\"';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        
        $result = json_decode($result);

        curl_close($ch);

        return $result[0]->role_set[0]->account;
    }
}
