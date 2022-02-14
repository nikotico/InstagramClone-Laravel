var url = 'http://pinstagram.com.devel';
window.addEventListener("load", function(){
	
	$('.btn-like').css('cursor', 'pointer');
	$('.btn-dislike').css('cursor', 'pointer');
	
	// Botón de like
	function like(){//Para volver a cargar el DOM
		//Como llamo una funcion dentro de otra tengo que usar el unblind
		$('.btn-like').unbind('click').click(function(){//Para que los blinds  no se ejecuten varias veces
			
			console.log('like');
			$(this).addClass('btn-dislike').removeClass('btn-like');//Cambia la clase del corazon
			$(this).attr('src', url+'/img/heart-red.png');//Cambia la imagen del corazon y se le pone el dominio(URL) 
			//limpio para que arranque desde ahi a buscar la imagen
			
			$.ajax({//Peticion ajax
				url: url+'/like/'+$(this).data('id'),//Construyo el utl
				type: 'GET',
				success: function(response){//Callback
					if(response.like){
						console.log('Has dado like a la publicacion');
					}else{
						console.log('Error al dar like');
					}
				}
			});
			
			dislike();//Para que vuelva a cargar el DOM
		});
	}
	like();//Carga una de una vez en la pagina
	
	// Botón de dislike
	function dislike(){
		$('.btn-dislike').unbind('click').click(function(){
			console.log('dislike');
			$(this).addClass('btn-like').removeClass('btn-dislike');
			$(this).attr('src', url+'/img/heart-black.png');
			
			$.ajax({
				url: url+'/dislike/'+$(this).data('id'),
				type: 'GET',
				success: function(response){
					if(response.like){
						console.log('Has dado dislike a la publicacion');
					}else{
						console.log('Error al dar dislike');
					}
				}
			});
			
			like();
		});
	}
	dislike();
	
	// BUSCADOR
	$('#buscador').submit(function(e){
		$(this).attr('action',url+'/personas/'+$('#buscador #search').val());
	});
	
});