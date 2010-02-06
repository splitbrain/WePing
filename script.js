
window.onload = function() {
    countChars();
    var ta = document.getElementById('p');
    ta.onkeypress = countChars;
    ta.focus();

    // link headline with popup code
    var h1 = document.getElementById('popup');
    var js = "javascript:void(window.open('"+
             document.URL.replace(/\?.*/,'')+
            "?popup&p='+encodeURIComponent(document.title+' '+document.URL),'weping','width=325,height=300'));";

    h1.innerHTML = '<a href="'+js+'" title="drag to bookmark bar">'+h1.innerHTML+'</a>';

}

function countChars(){
    var ta  = document.getElementById('p');
    var lgn = document.getElementById('login');

    var avail = 140;
    avail -= (lgn.innerHTML.length + 1);
    avail -= ta.value.length;

    // ping.fm shorturls are 21 chars, see if it will shorten and count the shortened url
    var matches = ta.value.match(/\b((https?):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gi);
    if(matches) for(var i=0; i<matches.length; i++){
        if(matches[i].length > 21){
            // we counted too much, the url will be shortened
            avail = avail+ (matches[i].length - 21);
        }
    }

    var out = document.getElementById('chars');

    if(avail > 0){
        out.innerHTML = avail+' chars left';
    }else{
        out.innerHTML = '<span>'+avail+'</span> chars left';
    }
}
