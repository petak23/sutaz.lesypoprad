/*
Text clanku alebo oznamu medzi {reg1} {/reg1} sa zobrazi len registrovanym.
(c) anigraph.eu 30.08.2012
*/

CKEDITOR.plugins.add( 'anigraph_reg1',
{
	init: function( editor )
	{
		editor.addCommand( 'anigraph_reg1',
		{
			modes : { wysiwyg:1, source:0 },
			canUndo : true,
			exec : function( editor )
			{
				editor.insertHtml("{reg1} {/reg1}")
			},
			editorFocus : true
		});
		editor.ui.addButton( 'Registrovany',
		{
			label: 'Pre registrovaných',
			command: 'anigraph_reg1',
			icon: this.path + 'images/icon.png'
		} );
			}

} );