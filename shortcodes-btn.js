jQuery(document).ready(function($) {

    tinymce.create('tinymce.plugins.wpse72394_plugin', {
		init : function(ed, url) {
		
			// Register command for when button is clicked
			ed.addCommand('wpse72394_insert_accordion', function() {
			
				jQuery( "#insert-media-button" ).trigger( "click" );
				alert("Copie o URL");
				
				function opa() {
				
					jQuery( ".media-modal button.media-modal-close" ).trigger( "click" );
					
					ed.windowManager.open( {
						title: 'Cole o URL e informações',
						width: 480, height: 240,
						body: [{
							type: 'textbox', name: 'url', label: 'Cole o url'
						},
						{
							type: 'textbox', name: 'titulo', label: 'Insira o título'
						},
						{
							type: 'textbox', name: 'qdo', label: 'Insira a data'
						}],
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
						if (jQuery("#__wp-uploader-id-2").css('display') == 'block') {
							opa();
						} else {
							console.log("copy sem janela de mídia");
						}
					}, 100);
				});
				document.removeEventListener('copy',opa);
				
			});
			ed.addButton('wpse72394_button', {
				title : 'Inserir anexo com formatação de título e data', cmd : 'wpse72394_insert_accordion', image: url + '/shortcodes-btn.png' 
			});	
			
        },   
    });
    tinymce.PluginManager.add('wpse72394_button', tinymce.plugins.wpse72394_plugin);
	return false;	
});