/**
 * Filemanager plugin to organize the Geecktec Filemanager 
 */
(function($){

	/**
	 * Filemanager plugin
	 */
	$.fn.filemanager = function(options, messages){
		var messages = $.extend($.fn.filemanager.messages, messages);
		var options = $.extend($.fn.filemanager.options, options);
//		console.debug(options);
//		console.debug(messages);
		_initConfig(messages, options);
	};
	
	/**
	 * Default config of filemanager
	 */
	$.fn.filemanager.options = {
		imageRoot: "",	// Link of root icon
		imageFile: "", // Link of file icon
		imageFolder: "", // Link of folder icon
			
		// Folder links
		folderGetChildren: "", // Link of list of children of id
		folderAdd: "", // Link to add a folder
		folderRename: "", // Link to rename a folder
		folderRemove: "", // Link to remove a folder
		folderMove: "", // Link to move a folder
		
		// Filemanager links
		refreshScreen: "", // Link to refresh inside screen
		ajaxSearch: "", // Link to search
		
		// Files links
		fileRemove: ""	// Link to remove a file
	};	
	
	/**
	 * Default messages and texts of filemanager
	 */
	$.fn.filemanager.messages = {
		// Tabs
		removeTab: "Remove Tab",
		
		// Filemanager
		deleteFile: "Are you sure to delete this file?",
		notAvaliable: "Attachements folders is not avaliable. Use the Filemanager",
		cannotRename: "You cannot rename this. Sorry.",
		cannotRemove: "You cannot remove this. Sorry.",
		notCkeditor: "This function is only used for integration with FCKEditor or Ckeditor.",
		closeWindow: "Are you sure to close this window?",
		
		// Uploader
		uploaderArea: "Drop files here to upload",
		uploaderButton: "Upload a file",
		fail: "Fail"			
	};
	
	/**
	 * PRIVATE FUNCTIONS
	 */
	
	/**
	 * Begins configurations
	 */
	function _initConfig(messages, options){
//		console.debug(options);
		// Icons
		$(".ui-state-default").live('mouseover mouseout', function(event){
			if(event.type == 'mouseover') {
				$(this).addClass('ui-state-hover');
			} else {
				$(this).removeClass('ui-state-hover');
			}
		});
		
		// Menu for jsTree
		var $menu = $("#gtview-file-view a").click(function(){
			$(this).find('span').toggleClass(function(){
				if($(this).is('.ui-icon-circle-triangle-s')){
					return 'ui-icon-circle-triangle-n';
				}else{
					return 'ui-icon-circle-triangle-s';
				}
			});
			$("#gtview-file-view .ui-gtgrid-bdiv").slideToggle("slow");
		});
		
		// Images
		$('.thumb').live('mouseover mouseout', function(event){
			if(event.type == 'mouseover'){
				$(this).addClass('ui-state-hover');
			}
			else{
				$(this).removeClass('ui-state-hover');
			}
		});
		
//		// Show delete file
//		$('ul.imagens li').live('mouseover mouseout', function(event){
//			var icone = $(this).find('a[class*="ui-delete"]');
//			if(event.type == "mouseover"){
//				icone.show();
//				$(this).find('.file_options').show();
//			}else{
//				icone.hide();
//				$(this).find('.file_options').hide();
//			}
//		});	
		
//		/**
//		 * TABS IMAGES
//		 */
//		// Hover and Click images (border and delete action)
//		$(".ui-delete").live('click', function(){
//			var answer = confirm(messages.deleteFile);
//			if(answer){
//				var thumb = $(this).parent();
//				var id = thumb.attr('id').replace('thumb-', '');
//				$.getJSON(options.fileRemove + id, function(data){
//					if(data.success){
//						thumb.effect('blind', {}, 500, function(){
//							thumb.remove();
//						});
//					} else {
//						alert(messages.fail);
//					}
//				});
//			}
//		});
		

		
		// Tabs
		var $tabs = $('#tabs').tabs({
			tabTemplate: '<li><a href="#{href}">#{label}</a> <span class="ui-icon ui-icon-close">' + messages.removeTab + '</span></li>',
			add: function(event, ui) {
				var id = $(ui.panel).attr('id').replace('tabs-', '');
				$(ui.panel).load(options.refreshScreen + id, function(){
				});
			}
		});
		
		// close icon: removing the tab on click
		// note: closable tabs gonna be an option in the future - see http://dev.jqueryui.com/ticket/3924
		$('#tabs a span.ui-icon-close').live('click', function() {
			var index = $('li',$tabs).index($(this).parent());
			$tabs.tabs('remove', index);
		});
		
		// Tabs sortable
		$tabs.find(".ui-tabs-nav").sortable({axis: 'x'});
		
		
		 /**
		  * addTab, select the table selected, close the tree menu
		  */
		function addTab(id, title){
			if(!$('#tabs-' + id).attr('id')){
				$tabs.tabs('add', '#tabs-' + id, title);
			}
			$tabs.tabs('option', 'selected', id);
			$menu.click();
		}
		
		// Tabs update Screen
		function updateScreen(id){
			$("#tabs-" + id).load(options.refreshScreen + id);
			$tabs.tabs('option', 'selected', id);
		}
		
		/**
		 * Config for ajax-upload (valums)
		 */
		var uploader = new qq.FileUploader({
	        element: document.getElementById('file-uploader'),
	        action: 'geecktec_filemanager_files/ajaxAdd',
	        template:  '<div class="qq-uploader">' + 
	        	'<div class="qq-upload-drop-area"><span>' + messages.uploaderArea + '</span></div>' +
            	'<div class="qq-upload-button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">' + messages.uploaderButton + '</span></div>' +
            	'<ul class="qq-upload-list"></ul>' + 
            	'</div>',
	        params: {
	        	'name' : '',
	        	'parent_id': '',
	        	'geecktec_filemanager_folder_id': function(){
	    			var selected = $tabs.find("ul li[class*='ui-state-active'] a:first").attr("href").replace("#tabs-","");
	    			return selected;
	    		}
	    	},
	    	onComplete: function(id, filename, responseJSON){
	    		if(responseJSON['success']) {
		    		updateScreen(responseJSON['geecktec_filemanager_folder_id']);
	    		}
	    	}
	    });
		
		/**
		 * Config for jstree
		 */

		// Settings up the tree - using $(selector).jstree(options);
		// All those configuration options are documented in the _docs folder
		filemangerJsTree = $("#folder-tree")
			.jstree({ 
				// the list of plugins to include
				"plugins" : [ "themes", "json_data", "ui", "crrm", "cookies", "dnd", "search", "types", "hotkeys", "contextmenu" ],
				// Plugin configuration

				// I usually configure the plugin that handles the data first - in this case JSON as it is most common
				"json_data" : { 
					// I chose an ajax enabled tree - again - as this is most common, and maybe a bit more complex
					// All the options are the same as jQuery's except for `data` which CAN (not should) be a function
					"ajax" : {
						// the URL to fetch the data
						"url" : options.folderGetChildren,
						// this function is executed in the instance's scope (this refers to the tree instance)
						// the parameter is the node being loaded (may be -1, 0, or undefined when loading the root nodes)
						"data" : function (n) { 
							// the result is fed to the AJAX request `data` option
							return {  
								"id" : n.attr ? n.attr("id").replace("node_","") : 0
							}; 
						}
					}
				},
				// Configuring the search plugin
				"search" : {
					// As this has been a common question - async search
					// Same as above - the `ajax` config option is actually jQuery's object (only `data` can be a function)
					"ajax" : {
						"url" : options.ajaxSearch,
						// You get the search string as a parameter
						"data" : function (str) {
							return { 
								"search" : str 
							}; 
						}
					}
				},
				// Using types - most of the time this is an overkill
				// Still meny people use them - here is how
				"types" : {
					// I set both options to -2, as I do not need depth and children count checking
					// Those two checks may slow jstree a lot, so use only when needed
					"max_depth" : -2,
					"max_children" : -2,
					// I want only `drive` nodes to be root nodes 
					// This will prevent moving or creating any other type as a root node
					"valid_children" : [ "drive" ],
					"types" : {
						// The default type
						"default" : {
							// I want this type to have no children (so only leaf nodes)
							// In my case - those are files
							"valid_children" : "none",
							// If we specify an icon for the default type it WILL OVERRIDE the theme icons
							"icon" : {
								"image" : options.imageFile
							}
						},
						// The `folder` type
						"folder" : {
							// can have files and other folders inside of it, but NOT `drive` nodes
							"valid_children" : [ "default", "folder" ],
							"icon" : {
								"image" : options.imageFolder
							}
						},
						// The `drive` nodes 
						"drive" : {
							// can have files and folders inside, but NOT other `drive` nodes
							"valid_children" : [ "default", "folder" ],
							"icon" : {
								"image" : options.imageRoot
							},
							// those options prevent the functions with the same name to be used on the `drive` type nodes
							// internally the `before` event is used
							"start_drag" : false,
							"move_node" : false,
							"delete_node" : false,
							"remove" : false
						}
					}
				},
				// For UI & core - the nodes to initially select and open will be overwritten by the cookie plugin

				// the UI plugin - it handles selecting/deselecting/hovering nodes
				"ui" : {
					// this makes the node with ID node_4 selected onload
					"initially_select" : [ "node_1" ],
					"select_limit":	1
				},
				// the core plugin - not many options here
				"core" : { 
					// just open those two nodes up
					// as this is an AJAX enabled tree, both will be downloaded from the server
					"initially_open" : [ "node_1" , "node_2" ] 
				},
				"contextmenu" : {
					"items" : {
						"create" : false,
						"rename" : false,
						"remove" : false,
						"ccp" : false,
						"create_folder" : {
							"separator_before"	: false,
							"separator_after"	: true,
							"label"				: "Create Folder",
							"action"			: function (obj) {
								if(obj.attr('id').replace("node_","") == 2){
									alert(messages.notAvaliable);
								}else{
									this.create(obj).attr('rel', 'folder');
								} 
							}
						},
						"rename_folder" : {
							"separator_before"	: false,
							"separator_after"	: false,
							"label"				: "Rename",
							"action"			: function (obj) {
								if(obj.attr('id').replace("node_","") == 2 || obj.attr('id').replace("node_","") == 1){
									alert(messages.cannotRename);
								}else{
									this.rename(obj);
								}	 
							 }
						},
						"remove_folder" : {
							"separator_before"	: false,
							"icon"				: false,
							"separator_after"	: false,
							"label"				: "Delete",
							"action"			: function (obj) { 
								if(obj.attr('id').replace("node_","") == 2 || obj.attr('id').replace("node_","") == 1){
									alert(messages.cannotRemove);
								}else{
									this.remove(obj);
								}
							}
						},
						"ccp_folder" : {
							"separator_before"	: true,
							"icon"				: false,
							"separator_after"	: false,
							"label"				: "Edit",
							"action"			: false,
							"submenu" : { 
								"cut" : {
									"separator_before"	: false,
									"separator_after"	: false,
									"label"				: "Cut",
									"action"			: function (obj) { this.cut(obj); }
								},
								"paste" : {
									"separator_before"	: false,
									"icon"				: false,
									"separator_after"	: false,
									"label"				: "Paste",
									"action"			: function (obj) { this.paste(obj); }
								}
							}
						}														
					}
				}
			})
			.bind("create.jstree", function (e, data) {
				id = data.rslt.parent.attr("id").replace("node_","");
				$.post(
					options.folderAdd, 
					{  
						"parent_id" : data.rslt.parent.attr("id").replace("node_",""), 
						"position" : data.rslt.position,
						"name" : data.rslt.name,
						"type" : data.rslt.obj.attr("rel")
					}, 
					function (r) {
						if(r.status) {
							$(data.rslt.obj).attr("id", "node_" + r.id);
						}
						else {
							$.jstree.rollback(data.rlbk);
						}
					}
				);
			})
			.bind("remove.jstree", function (e, data) {
				data.rslt.obj.each(function () {
					$.ajax({
						async : false,
						type: 'POST',
						url: options.folderRemove,
						data : { 
							"id" : this.id.replace("node_","")
						}, 
						success : function (r) {
							if(!r.status) {
								data.inst.refresh();
							}
						}
					});
				});
			})
			.bind("rename.jstree", function (e, data) {	
				$.post(
					options.folderRename, 
					{ 
						"id" : data.rslt.obj.attr("id").replace("node_",""),
						"name" : data.rslt.new_name
					}, 
					function (r) {
						if(!r.status) {
							$.jstree.rollback(data.rlbk);
						}else{
//							console.debug(data);
							var id = data.rslt.obj.attr('id').replace('node_','');
							$("a[href='#tabs-" + id + "']").html('&nbsp;' + data.rslt.new_name);
						}
					}
				);
			})
			.bind("move_node.jstree", function (e, data) {
				data.rslt.o.each(function (i) {
					$.ajax({
						async : false,
						type: 'POST',
						url: options.folderMove,
						data : {
							"id" : $(this).attr("id").replace("node_",""), 
							"ref" : data.rslt.np.attr("id").replace("node_",""), 
							"position" : data.rslt.cp + i,
							"title" : data.rslt.name,
							"copy" : data.rslt.cy ? 1 : 0
						},
						success : function (r) {
							if(!r.status) {
								$.jstree.rollback(data.rlbk);
							}
							else {
								$(data.rslt.oc).attr("id", "node_" + r.id);
								if(data.rslt.cy && $(data.rslt.oc).children("UL").length) {
									data.inst.refresh(data.inst._get_parent(data.rslt.oc));
								}
							}
							$("#analyze").click();
						}
					});
				});
			}).bind("select_node.jstree", function(e, data) {
				var id = data.rslt.obj.attr("id").replace("node_","");
				var title = data.rslt.obj.find('a:first').text();
				addTab(id, title);
			}).droppable({
//					accept: '#imagens thumb'
			});
		
			// Create a Overlay for Image
			$.imgOverlay = $("#see").overlay({
				target: "#overlayImage"
			});
	}
	
	/*---------------------------------------------------------
	Item Actions
	---------------------------------------------------------*/

	//Calls the SetUrl function for FCKEditor compatibility,
	//passes file path, dimensions, and alt text back to the
	//opening window. Triggered by clicking the "Select" 
	//button in detail views or choosing the "Select"
	//contextual menu option in list views. 
	//NOTE: closes the window when finished.
	$.selectItem = function(src){
		var messages = $($.fn.filemanager.messages);
		if(window.opener){
			if($.urlParam('CKEditor')){
				// use CKEditor 3.0 integration method
				window.opener.CKEDITOR.tools.callFunction($.urlParam('CKEditorFuncNum'), src);
			} else {
				// use FCKEditor 2.0 integration method
				window.opener.SetUrl(src);
			}
			window.close();	
		} else {
			alert($.fn.filemanager.messages.notCkeditor);
		}
	};

	$.urlParam = function(name){
		var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
		return results != null ? results[1] : false;
	};
	
	// Close the opened window
	$.closeWindow = function(){
		if(window.opener){
			var answer =  confirm($.fn.filemanager.messages.closeWindow);
			if(answer){
				window.close();
			}
		}else{
			alert($.fn.filemanager.messages.notCkeditor);
		}
	};
	
	/**
	 * Config contextMenu for thumbs inside the tabs
	 */
	$.fn.configContextMenu = function(){
		var contextMenu = $(this).contextMenu({
			menu: 'myMenu'
		}, function(action, el, pos) {
			var thumb = el.parent();
			var id = thumb.attr("id").replace("thumb-", "");
			var img = el.find("span img:first").attr("rel");
			switch(action){
				case "see":
					$("<img />")
						.attr("src", img)
						.load(function(){
//							console.debug($(this));
							$("#overlayImage > .image").html($(this));
							$.imgOverlay.overlay().load();
						});
				break;
				case "download":
					url = $.fn.filemanager.options.fileDownload + id;
					$(location).attr('href', url);
				break;
				case "edit":
					alert("edit");
				break;
				case "select":
					$.selectItem(img);
				break;
				case "edit":
//					overlay.load();
				break;
				case "delete":alert("primeiro");
				alert($.urlParam("CKEditor"));
				teste = $.urlParam("CKEditor");
				alert(teste);alert("primeiro");
				alert($.urlParam("CKEditor"));
				teste = $.urlParam("CKEditor");
				alert(teste);
				console.debug(teste);
				if($.urlParam('CKEditor')){
					alert("correto");
					contextMenu.disableContextMenuItems(".quit,.select");
				}
//				console.debug(teste);
				if($.urlParam('CKEditor')){
					alert("correto");
					contextMenu.disableContextMenuItems(".quit,.select");
				}
					var answer = confirm($.fn.filemanager.messages.deleteFile);
					if(answer){
						$.getJSON($.fn.filemanager.options.fileRemove + id, function(data){
							if(data.success){
								thumb.effect('blind', {}, 500, function(){
									thumb.remove();
								});
							} else {
								alert($.fn.filemanager.messages.fail);
							}
						});
					}
				break;
				case "quit":
					$.closeWindow();
				break;
			}			
		});
		if(!$.urlParam("CKEditor")){
			$("#myMenu").disableContextMenuItems("#quit,#select");
		};
	};	

})(jQuery);