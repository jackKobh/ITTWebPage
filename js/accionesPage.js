$(document).ready(function(){
	ejecutaPeticionHTML('Framework/Acciones/muestraBotones.php',{}, 'navbar-principal');
	ejecutaPeticionHTML('Framework/Acciones/muestraSliderBotones.php',{}, 'btn-slider-principal');
	ejecutaPeticionHTML('Framework/Acciones/muestraSliderImagenes.php',{}, 'img-slider-principal');

	$('#btn-show-pdf').on('click',function(){
        var pdf_link = 'files/prueba2.pdf';
        //alert(pdf_link);
        //var iframe = '<div class="iframe-container"><iframe src="'+pdf_link+'"></iframe></div>'
        //var iframe = '<object data="'+pdf_link+'" type="application/pdf"><embed src="'+pdf_link+'" type="application/pdf" /></object>'        
        var iframe = '<object type="application/pdf" data="'+pdf_link+'" width="100%" height="500">No Support</object>'
        //alert(iframe);    
        $('.modal-body').html(iframe);

        $('#myModal').modal('toggle');
    });
});

