jQuery(document).ready(function($) {

    tinymce.create('tinymce.plugins.wpse72395_plugin', {
		init : function(ed, url) {
		
			// Register command for when button is clicked
			ed.addCommand('wpse72395_insert_accordion', function() {
				
				ed.windowManager.open( {
					title: 'Escolha se quer um colapsar novo ou já existente',
					width: 480, height: 200,
					//url: 'accordion-btn.htm',
					body: [{
							type: 'container', html: '<style>.btn-accordion { height: 22px; width: 96%; display: block; position: relative; padding: 4px 8px;  border: 1px solid #ccc; border-radius: 2px; text-align: center; margin-bottom: 10px; }</style><a href="#" class="btn-accordion" onclick="window.open(\'post-new.php?post_type=collapsed_content\', \'_blank\')"><span class="mce-txt">Novo colapsar</span></a><a href="#" class="btn-accordion" onclick="window.open(\'edit.php?post_type=collapsed_content\', \'_blank\')"><span class="mce-txt">Já existente</span></a><p style="font-size: 0.8em">Vá na outra abra aberta, edite o "Colapsar" desejado, copie o "Shortcode" (do lado<br /> direito, indicado em <span style="font-size: 1em; color: #119911">verde</span>) e cole abaixo.</span>'
						},
						{
						type: 'textbox', name: 'shortocde', label: 'Insira o shortcode'
					}],
					
					onsubmit: function( e ) {
						jQuery( ".mce-reset .mce-close" ).trigger( "click" );
						jQuery( ".mce-reset .mce-close" ).trigger( "click" );
				
						if (e.data.url.length > 3) {
								ed.insertContent( e.data.shortcode );	
						}
						jQuery( ".mce-reset .mce-close" ).trigger( "click" );
					}
				});
	
			});
			ed.addButton('wpse72395_button', {
				title : 'Inserir colapsar visualmente', cmd : 'wpse72395_insert_accordion', image: url + '/accordion-btn.png' 
			});	
			
        },   
    });
    tinymce.PluginManager.add('wpse72395_button', tinymce.plugins.wpse72395_plugin);
	return false;	
});
