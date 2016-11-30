<?php

/**
 * Route class
 * used for redirecting to functions
 *
 * @author Vince Urag
 */
class Routes
{

    private $_uriList = array();
    private $_callList = array();

    public function add($uri, $method = null) {
        if($uri == '/') {
            $this->_uriList[] = $uri;
        } else {
            $this->_uriList[] = trim($uri, '/\^$');
        }
        $this->_callList[] = $method;
    }

    public function exec() {
        if(isset($_REQUEST['uri'])) {
            $uriGet = rtrim($_REQUEST['uri'], '/');

        } else {
            $uriGet = '/';
        }

        $replacementValues = array();
        $http_method = $_SERVER['REQUEST_METHOD'];
        $isMatch = false;
        $isMatchKey = null;

        foreach ($this->_uriList as $listKey => $listUri) {
            if(strpos($listUri, ':param')) {
                $listUri = str_replace(":param", ".+", $listUri);
            }
            if (preg_match("#^$listUri$#", $uriGet)) {
                $ctr_list = count(explode("/", rtrim($listUri, '/')));
                $ctr_getUri = count(explode("/", rtrim($uriGet, '/')));
                if($ctr_list == $ctr_getUri) {
                    $isMatch = true;
                    $isMatchKey = $listKey;
                }
                $realUri = explode('/', $uriGet);
                $fakeUri = explode('/', $listUri);
            }
        }

        if($isMatch == true) {
            foreach ($fakeUri as $key => $value)
            {
                if ($value == '.+')
                {
                    $replacementValues[] = $realUri[$key];
                }
            }

            if(count($fakeUri) == 1) {
                $class_name = $this->_callList[$isMatchKey];
                $obj = new $class_name();
                $methodname = $http_method."_index";
                if(method_exists($obj, $methodname)){
                    $obj->$methodname();
                } else {
                    header('Content-Type: application/json');
                    http_response_code(404);
                    echo json_encode(array("error" => "no routes matched", "details" => "check if routes has been configured properly"));
                }
            } else if (count($fakeUri) > 1){
                $method_exec = explode("/", $this->_callList[$isMatchKey]);
                $class_name = $method_exec[0];
                $obj = new $class_name();
                if(count($method_exec) < 2) {
                    $method_exec = $http_method."_".$method_exec[0];
                } else {
                    $method_exec = $http_method."_".$method_exec[1];
                }
                if(method_exists($obj, $method_exec)){
                    call_user_func_array(array($obj, $method_exec), $replacementValues);
                } else {
                    header('Content-Type: application/json');
                    http_response_code(404);
                    echo json_encode(array("error" => "no routes matched", "details" => "check if routes has been configured properly"));
                }

            } else {
                echo "No routes found";
            }
        } else {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(array("error" => "no routes matched", "details" => "check if routes has been configured properly"));
        }
    }
}
