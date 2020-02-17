FCKConfig.EditorAreaCSS = FCKConfig.BasePath + 'css/fck_editorarea.css' ;
FCKConfig.EditorAreaCSS = FCKConfig.BasePath + '../skins/red/singles.css';

FCKConfig.ToolbarSets["Default"] = [
    ['Source','DocProps','-','Save','NewPage','Preview','-','Templates'],
    ['Cut','Copy','Paste','PasteText','PasteWord','-','Print','SpellCheck'],
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
    ['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
    ['OrderedList','UnorderedList','-','Outdent','Indent'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
    ['Link','Unlink','Anchor'],
    ['Image','Flash','Table','Rule','Smiley','SpecialChar','PageBreak','UniversalKey'],
    ['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
    '/',
    ['Style','FontFormat','FontName','FontSize'],
    ['TextColor','BGColor'],
    ['About']
] ;

FCKConfig.ToolbarSets["Basic"] = [
    ['Source'],
    ['Undo','Redo'],
    ['Bold','Italic','-','OrderedList','UnorderedList','-','Link','Unlink','Anchor'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
    ['About'],
    ['Image'],
    ['FontName','FontSize'],
    ['TextColor','BGColor']
] ;

FCKConfig.ToolbarSets["mailTempl"] = [
    ['Source'],
    ['Undo','Redo'],
    ['Bold','Italic','-','OrderedList','UnorderedList','-','Link','Unlink','Anchor'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
    ['About'],
    ['Image'],
    ['FontName','FontSize'],
    ['TextColor','BGColor']
] ;

FCKConfig.ToolbarSets["Images"] = [
    ['Source'],
    ['Undo','Redo'],
    ['Bold','Italic','-','OrderedList','UnorderedList','-','Link','Unlink','Anchor'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
    ['Image'],
    ['About'],
    ['FontName','FontSize'],
    ['TextColor','BGColor']
] ;

FCKConfig.ContextMenu = ['Generic','Link','Anchor','Image','Flash','Select','Textarea','Checkbox','Radio','TextField','HiddenField','ImageButton','Button','BulletedList','NumberedList','TableCell','Table','Form'] ;

FCKConfig.FontColors = '000000,993300,333300,003300,003366,000080,333399,333333,800000,FF6600,808000,808080,008080,0000FF,666699,808080,FF0000,FF9900,99CC00,339966,33CCCC,3366FF,800080,999999,FF00FF,FFCC00,FFFF00,00FF00,00FFFF,00CCFF,993366,C0C0C0,FF99CC,FFCC99,FFFF99,CCFFCC,CCFFFF,99CCFF,CC99FF,FFFFFF' ;



function getGetVar(nameVar) {
    var FCKTempConf=window.parent.document.getElementById(FCK.Name+'___Config').value;
    var aParams=FCKTempConf.split('&');
    var aParam='';
    var sParamName='';
    var sParamValue='';
    for (var i=0;i<aParams.length;i++)  {
        aParam=aParams[i].split('=');
        sParamName=aParam[0];
        sParamValue=aParam[1];
        if (sParamName==nameVar) return sParamValue;
    }
    return "";
}

FCKConfig.AutoDetectLanguage = false ;
FCKConfig.DefaultLanguage = 'en' ;

FCKConfig.CustomFullPathForUpload = getGetVar('CustomFullPathForUpload') ;
FCKConfig.CustomRelPathForUpload = getGetVar('CustomRelPathForUpload') ;
FCKConfig.CustomFromRootPathForUpload = getGetVar('CustomFromRootPathForUpload') ;

var tempAddedUrl='&CustomFullPathForUpload='+FCKConfig.CustomFullPathForUpload+'&CustomRelPathForUpload='+FCKConfig.CustomRelPathForUpload+'&CustomFromRootPathForUpload='+FCKConfig.CustomFromRootPathForUpload;
FCKConfig.LinkBrowserURL += tempAddedUrl ;
FCKConfig.ImageBrowserURL += tempAddedUrl ;
FCKConfig.FlashBrowserURL += tempAddedUrl ;
FCKConfig.LinkUploadURL += tempAddedUrl ;
FCKConfig.ImageUploadURL += tempAddedUrl ;
FCKConfig.FlashUploadURL += tempAddedUrl ;