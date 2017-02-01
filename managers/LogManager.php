<?php

/**
 * Class LogManager
 * @author Matei Moisoiu
 */

class LogManager {

    public static $REGISTRY_KEY = "LogManager";

    /**
     * Constructor.
     */
    function LogManager() {
    }

    /**
     * Get text content by specified key for current set language
     * @param string $key
     * @return string
     */
    public function logText($text, $prefix = "LOG"){
        if(!is_dir(BASE_DIR . "logs")){
            mkdir(BASE_DIR . "logs", 0755, true);
        }
        $crTime = date('Y-m-d h:i:s');
        $f = fopen(BASE_DIR . "logs/main_log.txt", "a+");
        fwrite($f, "{$prefix}($crTime): $text\n");
        fclose($f);
    }
}

?>