<?php
    /** Turn on all error messages */
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_errors',1);

    /** Create new error handler */
    function errorHandler($errNo, $errMsg, $errFile, $errLine) {
        // Check if the error occurs when '@' operator was used
        //PaDebug::dump(array ($errNo, $errMsg, $errFile, $errLine), "error");

        if (error_reporting() == 0) return;

        switch ($errNo) {
            case E_NOTICE: return;
            case E_STRICT: return;
        }

        $error = array (
            E_ERROR           => "Fatal Error",
            E_WARNING         => "Warning",
            E_NOTICE          => "Notice",
            E_PARSE           => "Parse Error",
            E_CORE_ERROR      => "Core Error",
            E_CORE_WARNING    => "Core Warning",
            E_COMPILE_ERROR   => "Compile Error",
            E_COMPILE_WARNING => "Compile Warning",
            E_USER_ERROR      => "Error",
            E_USER_WARNING    => "Warning",
            E_USER_NOTICE     => "Notice"
        );

        echo "<pre>\n";

        echo "[".$error[$errNo]."]: ".$errMsg."\n";
        echo 'in '.$errFile.':'.$errLine."\n";

        // stack dump
        if (function_exists('debug_backtrace')) {
            $backtrace = debug_backtrace();
            echo "\nStack Dump:\n\n";
            $call_stack = "";
            foreach ($backtrace as $call) {
                if ($call['function'] == 'errorhandler') continue;
                if ($call['function'] == 'trigger_error') continue;

                if (isset($call['class'])) {
                    if (isset($call['type']))
                        $call_stack .= $call['class'].$call['type'];
                    else
                        $call_stack .= $call['class']."::";
                }

                $call_stack .= "$call[function](";
                if (isset($call['args'])) {
                    $args = array ();
                    foreach ($call['args'] as $arg) {
                        $type = gettype($arg);
                        switch ($type) {
                            case 'boolean':
                                $arg = $arg ? 'true' : 'false';
                                break;
                            case 'null':
                                $arg = 'null';
                                break;
                            case 'integer':
                            case 'double':
                                break;
                            case 'string': {
                                $maxchars = 500;
                                $arg = strlen($arg) > $maxchars ? '"'.substr($arg, 0, $maxchars).'"'."..." : '"'.$arg.'"';
                                break;
                            }
                            case 'array':
                                $arg = 'array';
                                break;
                            case 'object':
                                $arg = 'object of class '.get_class($arg);
                                break;
                        }
                        $args[] = $arg !== null ? "$type($arg)" : $type;
                    }
                    $call_stack .= implode(', ', $args);
                }
                $call_stack .= ")\n";
                if (isset($call['file']))
                    $call_stack .= "    [$call[file]:$call[line]]\n\n";
            }
            echo $call_stack."\n";
        }

        echo "</pre>\n";

        die();
    }

    /** Set new error handler */
    set_error_handler("errorHandler");
?>