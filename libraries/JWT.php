<?php



/**
 * JSON Web Token implementation
 *
 * @author Neuman Vong <neuman@twilio.com>
 * @minor changes by Vince Urag <check()>
 */
class Jwt
{

        /**
         * @param string      $jwt    The JWT
         * @param string|null $key    The secret key
         * @param bool        $verify Don't skip verification process
         *
         * @return object The JWT's payload as a PHP object
         */
        public function decode($jwt, $key = null, $verify = true)
        {
            $tks = explode('.', $jwt);
            if (count($tks) != 3) {
                throw new UnexpectedValueException('Wrong number of segments');
            }
            list($headb64, $payloadb64, $cryptob64) = $tks;
            if (null === ($header = $this->jsonDecode($this->urlsafeB64Decode($headb64)))
            ) {
                throw new UnexpectedValueException('Invalid segment encoding');
            }
            if (null === $payload = $this->jsonDecode($this->urlsafeB64Decode($payloadb64))
            ) {
                throw new UnexpectedValueException('Invalid segment encoding');
            }
            $sig = $this->urlsafeB64Decode($cryptob64);
            if ($verify) {
                if (empty($header->alg)) {
                    throw new DomainException('Empty algorithm');
                }
                if ($sig != $this->sign("$headb64.$payloadb64", $key, $header->alg)) {
                    throw new UnexpectedValueException('Signature verification failed');
                }
            }
            return $payload;
        }

        /**
         * @param object|array $payload PHP object or array
         * @param string       $key     The secret key
         * @param string       $algo    The signing algorithm
         *
         * @return string A JWT
         */
        public function encode($payload, $key, $algo = 'HS256')
        {
            $header = array('typ' => 'jwt', 'alg' => $algo);

            $segments = array();
            $segments[] = $this->urlsafeB64Encode($this->jsonEncode($header));
            $segments[] = $this->urlsafeB64Encode($this->jsonEncode($payload));
            $signing_input = implode('.', $segments);

            $signature = $this->sign($signing_input, $key, $algo);
            $segments[] = $this->urlsafeB64Encode($signature);

            return implode('.', $segments);
        }

        /**
         * @param string $msg    The message to sign
         * @param string $key    The secret key
         * @param string $method The signing algorithm
         *
         * @return string An encrypted message
         */
        public function sign($msg, $key, $method = 'HS256')
        {
            $methods = array(
                'HS256' => 'sha256',
                'HS384' => 'sha384',
                'HS512' => 'sha512',
            );
            if (empty($methods[$method])) {
                throw new DomainException('Algorithm not supported');
            }
            return hash_hmac($methods[$method], $msg, $key, true);
        }

        /**
         * @param string $input JSON string
         *
         * @return object Object representation of JSON string
         */
        public function jsonDecode($input)
        {
            $obj = json_decode($input);
            if (function_exists('json_last_error') && $errno = json_last_error()) {
                $this->handleJsonError($errno);
            }
            else if ($obj === null && $input !== 'null') {
                throw new DomainException('Null result with non-null input');
            }
            return $obj;
        }

        /**
         * @param object|array $input A PHP object or array
         *
         * @return string JSON representation of the PHP object or array
         */
        public function jsonEncode($input)
        {
            $json = json_encode($input);
            if (function_exists('json_last_error') && $errno = json_last_error()) {
                $this->handleJsonError($errno);
            }
            else if ($json === 'null' && $input !== null) {
                throw new DomainException('Null result with non-null input');
            }
            return $json;
        }

        /**
         * @param string $input A base64 encoded string
         *
         * @return string A decoded string
         */
        public function urlsafeB64Decode($input)
        {
            $remainder = strlen($input) % 4;
            if ($remainder) {
                $padlen = 4 - $remainder;
                $input .= str_repeat('=', $padlen);
            }
            return base64_decode(strtr($input, '-_', '+/'));
        }

        /**
         * @param string $input Anything really
         *
         * @return string The base64 encode of what you passed in
         */
        public function urlsafeB64Encode($input)
        {
            return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
        }

        /**
         * @param int $errno An error number from json_last_error()
         *
         * @return void
         */
        private function handleJsonError($errno)
        {
            $messages = array(
                JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
                JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
                JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON'
            );
            throw new DomainException(isset($messages[$errno])
                ? $messages[$errno]
                : 'Unknown JSON error: ' . $errno
            );
        }

        public function check() {
            include APP_PATH.'config/config.php';


            $token = null;
            $user_id = null;

              //Get headers
              $headers = apache_request_headers();
              if(isset($headers['Authorization'])){
                $unstrippedToken = $headers['Authorization'];
                $authToken = str_replace("Bearer ", '', $unstrippedToken);

                //Authorize
                try {
                  $decodedToken =  $this->decode($authToken, $config['JWT_SECRET_KEY']);
                  $encoded = json_encode($decodedToken);
                  $dec = json_decode($encoded, true);
                  $dec['authorization'] = "authorized";
                  return json_encode($dec);
                }catch(Exception $e){
                  return json_encode(array(
                                        'id' => null,
                                        'user_type' => null,
                                        'username' => null,
                                        'authorization' => 'unauthorized - failed decode'
                                      ));
                }
              }else {
                return json_encode(array(
                                      'id' => null,
                                      'user_type' => null,
                                      'username' => null,
                                      'authorization' => 'unauthorized - no header found'
                                    ));
              }
            }

        public function generate_token($user_id, $arrayPayload)
        {
            include APP_PATH.'config/config.php';
            date_default_timezone_set('Asia/Manila');
            $currDate = date('m/d/Y H:i:s A');
            $CONSUMER_KEY = $config['JWT_CONSUMER_KEY'];
            $CONSUMER_SECRET = $config['JWT_SECRET_KEY'];

            return $this->encode(array(
            'consumerKey' => $CONSUMER_KEY,
            'userId' => $user_id,
            'issuedAt' => date($currDate),
            'data' => $arrayPayload,
          ), $CONSUMER_SECRET);
        }
}
