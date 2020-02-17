<?
require_once(__INCLUDE_CLASS_PATH."/class.Main.php");
require_once(__INCLUDE_CLASS_PATH."/class.SingeltonStorage.php");
require_once(__INCLUDE_CLASS_PATH."/class.Options.php");
require_once(__INCLUDE_CLASS_PATH."/class.Template.php");

class ItemManager extends Main{
    var $aCache = array();

    function Get($name,$row=null){}
    function GetList($pager){}
    /**
     * Return value of option
     *
     * @param string $name
     * @return mixed value
     * @access public
     */
    function GetValue($name){
        $item = $this->Get($name);
        if ($item === null) {
            return $this->CriticalError("Can't create option $name");
        }
        return $item->value;
    }

    /**
     * Remove option from pull
     *
     * @param string $name
     * @return bollean
     * @access public
     */
    function Clear($name){
        if (isset($this->aCache[$name])) {
            unset($this->aCache[$name]);
        }
        return true;
    }

    /**
     * Clear list of options
     *
     * @access public
     */
    function ClearAll(){
        $this->aCache = array();
	}
}

class OptionManager extends ItemManager{

    /**
     * Return static link to object
     *
     * @return static link
     * @access public
     */
    function getInstance(){
        $manager =  SingeltonStorage::get("instance", "OptionManager", null);
        if ($manager === null){
            $manager = new OptionManager;
        }
        return $manager;
    }

    /**
     * Return option object from pull if it is not in pull create option and add to pull
     *
     * @param string $name
     * @return object
     * @access public
     */
    function Get($name,$row=null){
        $db = db::getInstance();
        if (isset($this->aCache[$name]))
        {
            return $this->aCache[$name];
        } else {
            if ($row === null){
                $eName = $db->escape($name);
                $option = $db->get_row("SELECT * FROM options WHERE Name='$eName'");
                if ($option == 0)
                    return $this->CriticalError("Unknown option name [$name]");
            } else {
                $option = $row;
            }
            switch ($option->Type) {
                case 'numeric'	    :$this->aCache[$name] = new NumericOption();break;
                case 'skin'		    :$this->aCache[$name] = new SkinOption();break;
                case 'string'	    :$this->aCache[$name] = new StringOption();break;
                case 'list'	        :$this->aCache[$name] = new ListOption();break;
                case 'email'        :$this->aCache[$name] = new EmailOption();break;
                case 'url'	        :$this->aCache[$name] =  new UrlOption();break;
                case 'dateformat'	:$this->aCache[$name] =  new DateFormatOption();break;
                case 'timeformat'	:$this->aCache[$name] =  new TimeFormatOption();break;
                case 'language'		:$this->aCache[$name] =  new LangOption();break;
                default:
                        return $this->CriticalError("Unknown option Type [$option->Type]");
            }
            $data = ($row === null) ? $name : $row;
            $this->aCache[$name]->Init($data);
            return $this->aCache[$name];
        }
    }

    /**
     * Return list of options
     *
     * @param string $name
     * @return array
     * @access public
     */
    function GetList($pager){
        $db = db::getInstance();
        $aOptions = $db->get_results("
	        					SELECT 	A.Name,
										A.Value,
										A.Label,
										A.Config,
										A.Type,
										A.Comments,
										B.Name AS GroupName,
										B.Title AS GroupTitle
								FROM options A
									INNER JOIN optionsgroup B
									ON (A.OptionsGroup_ID = B.OptionsGroup_ID)
								ORDER BY `Order`
        ");
        foreach ($aOptions as $row){
            $this->Get($row->Name,$row);
        }
        return $this->aCache;
	}

    /**
     * Return list of options by group
     *
     * @param string $name
     * @return array
     * @access public
     */
    function GetListByGroup($name){
        $aResult = array();
        $db = db::getInstance();
        $eName = $db->escape($name);

        $oGroup = $db->get_row("
				        	SELECT OptionsGroup_ID
							FROM optionsgroup
							WHERE
								Name = '$eName'
							");
        if (!$oGroup)
            return $this->CriticalError("Incorrect group name $name");

        $aGroups = $db->get_results("
									SELECT Name
									FROM options
									WHERE OptionsGroup_ID = '$oGroup->OptionsGroup_ID'
									ORDER BY `Order`
       								");
        if (is_array($aGroups))
            foreach ($aGroups as $row){
                $aResult[] = $this->Get($row->Name);
            }
        return $aResult;
	}

    /**
     * Return list of groups
     *
     * @return array List of objects
     * @access public
     */
    function GetGroupList(){
        $db = db::getInstance();
        $aGroups = $db->get_results("SELECT * FROM optionsgroup");
        foreach ($aGroups as $row) {
            $row->Title = (defined($row->Title)) ? constant($row->Title) : $row->Title;
            $aResult[] = $row;
        }
        return $aResult;
	}

    /**
     * Return group by name
     *
     * @return object
     * @access public
     */
    function GetGroup($name){
        $db = db::getInstance();
        $eName = $db->escape($name);
        $oGroup = $db->get_row("SELECT * FROM optionsgroup WHERE Name='$eName'");
        if (!$oGroup)
            return $this->Error("Can't find group $name");
        return $oGroup;
	}

}
class MTemplateManager extends ItemManager{

    /**
     * Return static link to object
     *
     * @return static link
     * @access public
     */
    function getInstance(){
        $manager = SingeltonStorage::get("instance", "MTemplateManager", null);
        if ($manager === null){
            $manager = new MTemplateManager;
        }
        return $manager;
    }

    /**
     * Return template object from pull if it is not in pull create template and add to pull
     *
     * @param string $name
     * @return object
     * @access public
     */
    function Get($name , $row = NULL){
        if (isset($this->aCache[$name]))
        {
            return $this->aCache[$name];
        } else {
            $parser = new StrictParser();
            $this->aCache[$name] = new MTemplate($name,$parser);
        }
        return $this->aCache[$name];
    }

    /**
     * Return list of options
     *
     * @param string $name
     * @return array
     * @access public
     */
    function GetList($pager){
        $db = db::getInstance();
        $count = $db->get_var("SELECT count(*) AS cnt FROM mailtemplate");

        $limit = $pager->GetLimit($count);
        $aTemplates = $db->get_results("SELECT * FROM mailtemplate $limit");
        foreach ($aTemplates as $row){
            $this->Get($row->Name);
        }
        return $this->aCache;
	}
}
class PTemplateManager extends ItemManager{

    /**
     * Return static link to object
     *
     * @return static link
     * @access public
     */
    function  getInstance(){
        $manager = SingeltonStorage::get("instance", "PTemplateManager", null);
        if ($manager === null){
            $manager = new PTemplateManager;
        }
        return $manager;
    }

    /**
     * Return template object from pull if it is not in pull create template and add to pull
     *
     * @param string $name
     * @return object
     * @access public
     */
    function Get($name , $row = NULL){
        $db =  db::getInstance();
        if (isset($this->aCache[$name]))
        {
            return $this->aCache[$name];
        } else {
            $parser = new EvalParser();
            $this->aCache[$name] = new PTemplate($name, $type='', $parser);
        }
        return $this->aCache[$name];
    }

    /**
     * Return list of options
     *
     * @param string $name
     * @return array
     * @access public
     */
    function GetList($pager){
        $db = db::getInstance();
        $aTemplates = $db->get_results("SELECT * FROM pagetemplate");
        foreach ($aTemplates as $row){
            $this->Get($row->Name);
        }
        return $this->aCache;
	}
}

?>