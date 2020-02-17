var rating_timer;
var rating = new Array();

function sendRatingRequest(url,id,scale){
    displayRatingProgress(id);
    new Ajax.Request(
            url,
            {
                method: 'post',
                onComplete: function (transport) {
                    if (transport.responseText != '') {
                        try {
                            var response = transport.responseText;
                           try{
                                  eval("ajaxRes = " + response + ";");
                              }
                              catch(e) {
                                  a=1;
                              }
                            putStar(id,ajaxRes.rating,scale);
                            rating[id] = ajaxRes.rating;
                        } catch(E) {
                            a=1;
                        }
                    displayRating(id, ajaxRes.voted);
                    }
                }
            }
        );
}


function setupStar(id,rating,scale){
    for (i=1;i<=scale;i++) {
        if (i-rating <= 0){
            document.getElementById('rating_line_'+id+'_'+i).className = 'star'; 
        } else if (i-rating >= 1){
            document.getElementById('rating_line_'+id+'_'+i).className = 'emptystar'; 
        } else if (i-rating > 0 && i-rating >= 0.8){
            document.getElementById('rating_line_'+id+'_'+i).className = 'emptystar'; 
        } else if (i-rating > 0 && i-rating <= 0.2){
            document.getElementById('rating_line_'+id+'_'+i).className = 'star'; 
        } else {
            document.getElementById('rating_line_'+id+'_'+i).className = 'halfstar'; 
        }  
    }
}

function putStar(id,rating, scale){
    clearTimeout(rating_timer);
    setupStar(id, rating, scale);
}

function clearStar(id, rating, scale){
    rating_timer = setTimeout("setupStar('"+id+"', "+rating+","+scale+")", 1000);
}

function displayRating(id, voted){
    document.getElementById('rate_block_'+id).style.display = ""; 
    document.getElementById('rate_progress_'+id).style.display = "none"; 
    document.getElementById('rate_voted_'+id).innerHTML = voted; 
}

function displayRatingProgress(id){
    document.getElementById('rate_block_'+id).style.display = "none"; 
    document.getElementById('rate_progress_'+id).style.display = ""; 
}
