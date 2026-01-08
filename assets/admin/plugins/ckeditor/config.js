/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	config.uiColor = '#EEEEEE';
	config.width = '750px';
	config.height = '300px';
	config.language = 'en';
	//config.filebrowserUploadUrl = 'http://webwingtechnologies.com/gtop100/ckeditor/ckupload.php';
	var host=document.location.hostname;
	console.log("test");
	console.log(host);
	config.filebrowserUploadUrl = 'http://'+host+'/~devp/iibf/assets/admin/plugins/ckeditor/ckupload.php';
	console.log( config.filebrowserUploadUrl );
	//config.filebrowserUploadUrl = 'http://server-13/gtop/ckeditor/ckupload.php'; 
	config.allowedContent = true;
	config.resize_enabled = false;
	config.removeFormatTags = 'iframe,big,code,del,dfn,em,ins,kbd';
	//CKEDITOR.instances.yourInstance.filter.check( 'iframe' );
	config.toolbar = 'MyToolbar';
	config.toolbar_MyToolbar =
	[
		{ name: 'document', items : ['Preview'] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','Scayt' ] },
		//{ name: 'insert', items : [ 'Image','HorizontalRule','SpecialChar','PageBreak'] },
		{ name: 'paragraph', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
		{ name: 'styles', items: [ 'Styles', 'Format' ,'Font','FontSize','TextColor','BGColor'] },
		{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak'] },
		'/',
		{ name: 'basicstyles', items : [ 'Bold','Underline','Italic','Strike','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
		{ name: 'links', items : [ 'Link','Unlink' ] },
		{ name: 'tools', items : [ 'Maximize','-','Source' ] }
	];
};