/*
Zalomenie textu pri oznamoch, urci kolko textu sa zobrazi na titulke (pri prvom zobrazeni).
*/

CKEDITOR.plugins.add( 'zalomenie',
{
	init: function( editor )
	{
		editor.addCommand( 'zalomenie',
		{
			modes : { wysiwyg:1, source:0 },
			canUndo : true,
			exec : function( editor )
			{
				editor.insertHtml("{end}")
			},
			editorFocus : true
		});
		editor.ui.addButton( 'Zalomenie',
		{
			label: 'Zalomenie textu',
			command: 'zalomenie',
			icon: this.path + 'images/icon.png'
		} );
			}

} );