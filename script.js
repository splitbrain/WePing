
window.onload = function() {
    countChars();
    var ta = document.getElementById('p');
    ta.onkeypress = countChars;
    ta.focus();

    // link headline with popup code
    var h1 = document.getElementById('popup');
    var js = "javascript:void(window.open('"+
             document.URL.replace(/\?.*/,'')+
            "?popup&p='+document.URL,'weping','width=325,height=300'));";

    h1.innerHTML = '<a href="'+js+'" title="drag to bookmark bar">'+h1.innerHTML+'</a>';

}

function countChars(){
    var ta  = document.getElementById('p');
    var lgn = document.getElementById('login');

    var avail = 140;
    avail -= (lgn.innerHTML.length + 1);
    avail -= ta.value.length;

    var out = document.getElementById('chars');

    if(avail > 0){
        out.innerHTML = avail+' chars left';
    }else{
        out.innerHTML = '<span>'+avail+'</span> chars left';
    }
}
