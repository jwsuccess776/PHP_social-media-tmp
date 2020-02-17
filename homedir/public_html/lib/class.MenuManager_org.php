<?php

include_once __INCLUDE_CLASS_PATH."/class.Main.php";

class MenuManager extends Main {
    var $menu = array();
    var $defaultSeparator = '';

    function MenuManager($area) {

        $menus = array(

            'home' => array(
			    'MENU_SEARCH' => CONST_LINK_ROOT."/search.php",
				'MENU_MAIL' => CONST_LINK_ROOT."/myemail.php",
				'MENU_PROFILE' => CONST_LINK_ROOT."/view_profile.php",
				'MENU_CONTROL' => CONST_LINK_ROOT."/myinfo.php",
				'MENU_MEDIA' => CONST_LINK_ROOT."/prgpicadmin.php",
				'MENU_HOTLIST' => CONST_LINK_ROOT."/prghotlist.php",
				'MENU_VIDEO' => CONST_LINK_ROOT."/video_list.php",
                'MENU_BLOGS' => CONST_BLOG_LINK_ROOT."/blogs.php",
                'MENU_GROUPS' => CONST_GROUPS_LINK_ROOT."/groups.php",
                'MENU_BB' => CONST_LINK_ROOT."/forum/forums.php"
            ),
			


            'guest' => array(
			    'MENU_SEARCH' => CONST_LINK_ROOT."/search.php",
				'MENU_MAIL' => CONST_LINK_ROOT."/myemail.php",
				'MENU_PROFILE' => CONST_LINK_ROOT."/view_profile.php",
				'MENU_CONTROL' => CONST_LINK_ROOT."/myinfo.php",
				'MENU_MEDIA' => CONST_LINK_ROOT."/prgpicadmin.php",
				'MENU_HOTLIST' => CONST_LINK_ROOT."/prghotlist.php",
				'MENU_VIDEO' => CONST_LINK_ROOT."/video_list.php",
                'MENU_BLOGS' => CONST_BLOG_LINK_ROOT."/blogs.php",
                'MENU_GROUPS' => CONST_GROUPS_LINK_ROOT."/groups.php",
                'MENU_BB' => CONST_LINK_ROOT."/forum/forums.php"

            ),

            'member' => array(
			    'MENU_SEARCH' => CONST_LINK_ROOT."/search.php",
				'MENU_MAIL' => CONST_LINK_ROOT."/myemail.php",
				'MENU_PROFILE' => CONST_LINK_ROOT."/view_profile.php",
				'MENU_CONTROL' => CONST_LINK_ROOT."/myinfo.php",
				'MENU_MEDIA' => CONST_LINK_ROOT."/prgpicadmin.php",
				'MENU_HOTLIST' => CONST_LINK_ROOT."/prghotlist.php",
				'MENU_VIDEO' => CONST_LINK_ROOT."/video_list.php",
                'MENU_BLOGS' => CONST_BLOG_LINK_ROOT."/blogs.php",
                'MENU_GROUPS' => CONST_GROUPS_LINK_ROOT."/groups.php",
                'MENU_BB' => CONST_LINK_ROOT."/forum/forums.php"            
			),

            'affiliate' => 'guest'

        );

        
        if (!empty($menus[$area])) {
            if (is_array($menus[$area]))
                $this->menu = $menus[$area];
            else
                $this->menu = $menus[$menus[$area]];
        }

        $options =& OptionManager::GetInstance();
        if (!$options->getValue('blogs'))
            unset($this->menu['MENU_BLOGS']);
        if (!$options->getValue('forums'))
            unset($this->menu['MENU_BB']);
        if (!$options->getValue('groups'))
            unset($this->menu['MENU_GROUPS']);
        if (!$options->getValue('video_conversion'))
            unset($this->menu['MENU_VIDEO']);

    }



    function getMenu($separator = null) {

        if ($separator === null)

            $separator = $this->defaultSeparator;

        $links = array();

        foreach ($this->menu as $label => $url) 

            $links[] = "<a href=\"$url\">{$GLOBALS[$label]}</a>";

        $links = implode($separator, $links);

        return $links;

    }



    function outputMenu($separator = null) {

        if ($separator === null)

            $separator = $this->defaultSeparator;

        echo $this->getMenu($separator);

    }

}

?>