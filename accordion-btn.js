jQuery(document).ready(function($) {

    tinymce.create('tinymce.plugins.wpse72395_plugin', {
		init : function(ed, url) {
		
			// Register command for when button is clicked
			ed.addCommand('wpse72395_insert_accordion', function() {
			
				jQuery( "#insert-media-button" ).trigger( "click" );
				alert("Copie o URL");
				
				function opa() {
				
					jQuery( ".media-modal button.media-modal-close" ).trigger( "click" );
					
					ed.windowManager.open( {
						title: 'Cole o URL e informações',
						body: [{
							type: 'textbox',
							name: 'url',
							label: 'Cole o url'
						},
						{
							type: 'textbox',
							name: 'titulo',
							label: 'Insira o título'
						},
						{
							type: 'textbox',
							name: 'qdo',
							label: 'Insira a data'
						}
						],
						onsubmit: function( e ) {
							jQuery( ".mce-reset .mce-close" ).trigger( "click" );
							jQuery( ".mce-reset .mce-close" ).trigger( "click" );
					
							if (e.data.url.length > 3) {
									ed.insertContent('[anexo nome="' + e.data.url + '" titulo="' + e.data.titulo + '" data ="' + e.data.qdo + '"]');	
									
							} else {
								ed.insertContent('[anexo]');
							}	
							jQuery( ".mce-reset .mce-close" ).trigger( "click" );
						}
					});
					
				}
				
				document.addEventListener('copy', function(e) {
					setTimeout(function () {
						opa();
					}, 100);
				});
				document.removeEventListener('copy',opa);
				
			});
			ed.addButton('wpse72395_button', {
				title : 'Inserir anexo com estilo', cmd : 'wpse72395_insert_accordion', image: url + '/accordion-btn.png' 
			});	
			
        },   
    });
    tinymce.PluginManager.add('wpse72395_button', tinymce.plugins.wpse72395_plugin);
	return false;	
});