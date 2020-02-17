<!--
//rollovers...
if(document.images) {
     
		
		link1off = new Image();
		link1off.src = "images/link1off.gif";
		link1over = new Image();
		link1over.src = "images/link1over.gif";
		
		
		link2off = new Image();
		link2off.src = "images/link2off.gif";
		link2over = new Image();
	        link2over.src = "images/link2over.gif";
		
		
		link3off = new Image();
		link3off.src = "images/link3off.gif";
		link3over = new Image();
		link3over.src = "images/link3over.gif";
		
                link4off = new Image();
		link4off.src = "images/link4off.gif";
		link4over = new Image();
		link4over.src = "images/link4over.gif";
		  		

}
function onoff(imgName,state) {
        if(document.images) {               
		document.images[imgName].src = eval(imgName+state+".src");
        }
}         
//-->