<?php /** @noinspection ALL */

namespace Pursar;

class Pursar {

    private $api_key;
    private $api_secret;

    private $pursar_url = 'http://pay.localhost:8000';
    private $pursar_ip = '127.0.0.1';

    /**
     * Pursar constructor.
     * @param $api_key
     * @param $api_secret
     */
    public function __construct($api_key, $api_secret) {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
    }

    /**
     * @param $amount
     * @param $currency
     * @param $lang
     * @param $success_url
     * @param $failure_url
     * @param $invoice_id
     * @param $invoice_details
     */
    public function pay($amount, $currency, $lang, $success_url,
                        $failure_url, $invoice_id, $invoice_details) {

        echo "<style>
                .loader {
                    position: fixed;
                    left: 0px;
                    top: 0px;
                    width: 100%;
                    height: 100%;
                    z-index: 9999;
                    background: url('http://localhost:8000/paypursar/images/loaders/loader3.gif') 50% 35% no-repeat #fff;
                    opacity: 1;
                }
                .loader h3 {text-align: center;font-size: 25px;color: #6c6c6c;}
                .loader .caption {position: relative;top: 50%;}
            </style>";

        echo '<div class="loader">
                <div class="caption">
                    <h3>redirecting to pursar, please wait...</h3>
                </div>
              </div>
              <body onload="document.forms[\'pursarForm\'].submit()">
                <form action="'.$this->pursar_url.'" style="display: none" method="POST" name="pursarForm">
                    <input type="number" name="amount" value="'.$amount.'">
                    <input type="text" name="currency" value="'.$currency.'">
                    <input type="text" name="invoiceId" value="'.$invoice_id.'">
                    <textarea name="invoiceDetails">'.$invoice_details.'</textarea> 
                    <input type="text" name="language" value="'.$lang.'">
                    <input type="text" name="apiKey" value="'.$this->api_key.'">
                    <input type="text" name="successURL" value="'.$success_url.'">
                    <input type="text" name="failureURL" value="'.$failure_url.'">
                    <input type="submit">
                </form>
            </body>';
    }

    /**
     * @return bool
     */
    public function checkSuccess() {
        return $this->validateRequest();
    }

    /**
     * @return bool
     */
    public function checkFailure() {
        return $this->validateRequest();
    }

    /**
     * @param $invoiceId
     * @return mixed
     */
    public function checkStatus($invoiceId) {

        $params = [
            'invoiceId' => $invoiceId,
            'apiKey' => $this->api_key
        ];

        $ch = curl_init($this->pursar_url.'/api/check-status');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }


    /*===========================================================
      Helper Functions
      ============================================================*/

    /**
     * @return bool
     */
    private function validateRequest() {
        $ret_api_secret = null;

        if (!isset($_POST['invoiceId'])) {
            dump($_POST['invoiceId']);
            return false;
        }

        if (isset($_POST['apiSecret'])) {
            $ret_api_secret = $_POST['apiSecret'];
        } else {
            return false;
        }

        if ($this->api_secret != $ret_api_secret) {
            return false;
        }

        if (isset($_SERVER['HTTP_REFERER'])) {
            $urlParts = parse_url($_SERVER['HTTP_REFERER']);
            $ip       = gethostbyname($urlParts['host']);

            if ($this->pursar_ip != $ip) {
                return false;
            }
        }

        return true;
    }
}
