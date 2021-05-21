<?php

/**
 * Advance CURL PHP Class
 * By Hadi AkbariJedi.
 * Date: 11/4/2019
 * Time: 4:29 PM
 */

class MyCurl
{
//  Class  Parameters
    private $url;
    private $params;
    private $headers;
    private $curl;
    private $mh;

    //Get CURL Ready
    public function craete_curl($url, $param, $headers = array())
    {

        // create both cURL resources
        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "UTF-8",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_POSTFIELDS => $param,
            CURLOPT_POST => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $headers,
        ));
        return $this->curl;
    }

    // ADD header to CURL
    public function add_handler($curl)
    {

        //create the multiple cURL handle
        $this->mh = curl_multi_init();

        //add the two handles
        curl_multi_add_handle($this->mh, $curl);

        return $this->mh;
    }

    // EXECUTE ONE CURL
    public function exec_curl($mh, $curl)
    {

        $active = '';

        //execute the multi handle
        do {
            $status = curl_multi_exec($mh, $active);

            $res = curl_multi_getcontent($this->curl); // get the content

            if ($active) {
                // Wait a short time for more activity
                curl_multi_select($mh);
            }
        } while ($active && $status == CURLM_OK);

        //close the handles
        curl_multi_remove_handle($mh, $curl);
        curl_multi_close($mh);
        return $res;
    }

    //  Call THIS
    public function go($url, $params, $headers)
    {
        $this->url = $url;
        $this->params = $params;
        $this->headers = $headers;

        // Create curl
        $curl = $this->craete_curl($this->url, $this->params, $this->headers);

        //Add to Handler
        $mh = $this->add_handler($curl);

        //Execute curl
        return $this->exec_curl($mh, $curl);
    }
}
