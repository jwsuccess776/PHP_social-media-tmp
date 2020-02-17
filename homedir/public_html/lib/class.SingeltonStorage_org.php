<?
class SingeltonStorage{
    /**
     * Return static link for this class with this name
     *
     * @param string $name
     * @param string $className
     * @param mixed $defaultValue
     * @return mixed
     * @access public
     */

    function & get($name, $className, $defaultValue = null)
    {
        global $___PaStaticVar_ARRAY___;

        if (!isset($___PaStaticVar_ARRAY___)) {
            $___PaStaticVar_ARRAY___ = array ();
        }

        $key = $name."/".$className;
        if (!array_key_exists($key, $___PaStaticVar_ARRAY___)) {
            $___PaStaticVar_ARRAY___[$key] = $defaultValue;
        }

        return $___PaStaticVar_ARRAY___[$key];
    }
}
?>