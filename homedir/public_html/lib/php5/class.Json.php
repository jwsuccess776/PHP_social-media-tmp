<?php
/**
 * Утилитный класс для преобразования данных из PHP в Json формат.
 *
 * @see http://json.org/
 */
class Json {
    /**
     * Преобразовывает PHP массив, объект, строку, число boolean, null или рекурсивно состоящую из них структуру в JavaScript эквивалент (Json).
     *
     * @param $o mixed
     * @return string строку JavasScript кода
     * @access public
     * @static
     */
    function php2Javascript($o)
    {
        return Json::_createJavascriptObject($o);
    }

    /**
     * Convert PHP array, object or string into JavaScript ecvivalent and returnt the Javascript code.
     *
     * @param mixed $o
     * @return string
     * @access private
     * @static
     */
    function _createJavascriptObject($o)
    {
        switch (strtolower(gettype($o))) {
            case "string":
            case "integer":
            case "double":
                return Json::_createJavascriptString($o);
                break;

            case "boolean":
                return $o ? "true" : "false";
                break;

            case "null":
                return "null";
                break;

            case "array": {
                // check if the array is ordinary array and not a hash
                $ordinaryArray = true;
                $aRes = array ();
                for ($i = 0; $i < count($o); $i++) {
                    if (!array_key_exists($i, $o)) {
                        $ordinaryArray = false;
                        break;
                    }
                    $aRes[] = Json::_createJavascriptObject($o[$i]);
                }
                if (!$ordinaryArray) {
                    return Json::_createJavascriptObject((object)$o);
                }
                else {
                    return "[" . implode(", ", $aRes) . "]";
                }

                break;
            }

            case "object": {
                $a = (array)$o;
                $aRes = array ();
                foreach ($a as $k => $v) {
                    $aRes[] = $k . " : " . Json::_createJavascriptObject($v);
                }
                return "{" . implode(", ", $aRes) . "}";
                break;
            }

            default:
                return "";
                break;
        }
    }

    /**
     * @access private
     * @static
     */
    function _createJavascriptString($s)
    {
        return '"' . str_replace(
            array (
                "\\",
                "\n",
                "\r",
                '"',
                "<script>",
                "</script>",
            ),
            array (
                "\\\\",
                "\\".'n',
                "\\".'r',
                "\\".'"',
                '<scr"+"ipt>',
                '</scr"+"ipt>',
            ),
            $s
        ) . '"';
    }

    /**
     * Регистрирует в Smarty функцию для преобразования PHP данных в строку JavaScript.
     *
     * Пример использования в PHP пишем:
     * <pre>
     * require_once "Json.php";
     * Json::registerInTemplateEngine($smarty);
     * </pre>
     *
     * в шаблоне:
     * <pre>
     * {json o=$aProjects}
     * </pre>
     *
     * @param Smarty $templateEngine
     * @access public
     * @static
     */
    function registerInTemplateEngine(&$templateEngine)
    {
        return $templateEngine->register_function("json", array ("Json", "_smarty_php2Javascript"));
    }

    /**
     * Реализация плагина к Smarty.
     *
     * @access private
     * @static
     */
    function _smarty_php2Javascript($params, &$smarty)
    {
        if (!array_key_exists("o", $params)) {
            $smarty->trigger_error("json: 'o' parameter is missing.");
        }

        return Json::php2Javascript($params["o"]);
    }
}
?>