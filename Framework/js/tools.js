function ejecutaPeticionHTML(URL,Parametros,IdElemento) {
	$.get(URL,Parametros)
	.done(function(data){
		//alert(IdElemento);
		$('#' + IdElemento).html(data);
	});
}