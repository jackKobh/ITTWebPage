$(document).ready(function(){
	ejecutaPeticionHTML('Framework/Acciones/muestraBotones.php',{}, 'navbar-principal');
	ejecutaPeticionHTML('Framework/Acciones/muestraSliderBotones.php',{}, 'btn-slider-principal');
	ejecutaPeticionHTML('Framework/Acciones/muestraSliderImagenes.php',{}, 'img-slider-principal');

	/*$('#btn-show-pdf').on('click',function(){
        var pdf_link = 'files/prueba2.pdf';
        //alert(pdf_link);
        //var iframe = '<div class="iframe-container"><iframe src="'+pdf_link+'"></iframe></div>'
        //var iframe = '<object data="'+pdf_link+'" type="application/pdf"><embed src="'+pdf_link+'" type="application/pdf" /></object>'        
        var iframe = '<object type="application/pdf" data="'+pdf_link+'" width="100%" height="500">No Support</object>'
        //alert(iframe);    
        $('.modal-body').html(iframe);

        $('#myModal').modal('toggle');
    });*/
});

function muestraPDF(url, titulo)
{
    //
    //var pdf_link = 'files/prueba2.pdf';
    var iframe = '<object type="application/pdf" data="'+url+'" width="100%" height="500">No Support</object>'
    //alert(iframe);    
    $('#itm-tittle-pdf').text(titulo);
    $('#modal-body-pdf').html(iframe);
    $('#myModal-pdf').modal('toggle');
}

(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

!function(d,s,id){
    var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
    if(!d.getElementById(id)){
        js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';
        fjs.parentNode.insertBefore(js,fjs);
    }
}(document, 'script', 'twitter-wjs');


function compartirFB(url)
{
    window.open('https://www.facebook.com/sharer/sharer.php?u='+
                encodeURIComponent(url),
                'facebookshare','width=540,height=480,resizable=yes'); 
    return false;
}


function compartirTW(url,text)
{
    window.open('https://twitter.com/intent/tweet?original_referer=' +
                encodeURIComponent(url)+
                '&ref_src=twsrc%5Etfw&text=' + text + '&tw_p=tweetbutton&url='+
                encodeURIComponent(url),
                'twittershare','width=540,height=480,resizable=yes');
    return false;
}

function compartirGP(url)
{
    window.open('https://plus.google.com/share?url='+encodeURIComponent(url),
        'googleshare','width=540,height=480,resizable=yes');
    return false;
}
