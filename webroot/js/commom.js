/**
 * @author Léo Haddad
 * @package GeecktecFilemanager Plugin for Croogo
 * @version 1.0
 */	

/**
 * Config for jquery-layout
 */
var outerLayout, middleLayout, innerLayout; 

$(document).ready(function () { 

	outerLayout = $('#filemanager').layout({ 
		center__paneSelector:	".outer-center" 
	,	west__paneSelector:		".outer-west" 
	,	west__size:				246 
	,	west__minSize:			150
	,	west__maxSize:			500	
	,	spacing_open:			8 // ALL panes
	,	spacing_closed:			8 // ALL panes
	,	north__resizable: 		false
	,	north__slideble:		false
	}); 

	middleLayout = $('div.outer-center').layout({ 
		center__paneSelector:	".inner-center" 
	,	spacing_open:			1  // ALL panes
	,	spacing_closed:			12 // ALL panes

	,	west__paneSelector:		".inner-west" 
	,	east__paneSelector:		".inner-east" 
	
	,	north__closable : 		false		
	}); 

}); 
var filemanagerJsTree;

/**
 * Config for ajax-upload (valums)
 */
function createUploader(){            
    var uploader = new qq.FileUploader({
        element: document.getElementById('file-uploader'),
        action: 'geecktec_filemanager_files/ajaxAdd',
        params: {
        	'name' : '',
        	'parent_id': '',
        	'geecktec_filemanager_folder_id': function(){
				return filemangerJsTree.jstree('_focused').jstree('get_selected').attr('id').replace('node_','');
    		}
    	}
    });           
}
$(function(){
	createUploader();
});

/**
 * Config for menu of jstree (top)
 */
$(function () { 
	$("#mmenu input").click(function () {
		switch(this.id) {
			case 'default': case "add_folder":
				if($('#folder-tree').jstree('is_selected', $("#node_2"))){
					alert('Attachements folders is not avaliable. Use the Filemanager');
				}else{
					$("#folder-tree").jstree("create", null, "last", { "attr" : { "rel" : this.id.toString().replace("add_", "") } });
				} 
				break;
			case "search":
				$("#folder-tree").jstree("search", document.getElementById("text").value);
				break;
			case "text": break;
			default:
				$("#folder-tree").jstree(this.id);
				break;
			case "remove":
				$("#folder-tree").jstree("remove", null, "last", { "attr" : { "rel" : this.id.toString() } });
				break;
			case "rename":
				if($('#folder-tree').jstree('is_selected', $("#node_2")) || $('#folder-tree').jstree('is_selected', $("#node_1"))){
					alert('You cannot rename this. Sorry.');
				}else{
					$("#folder-tree").jstree("rename", null, "last", { "attr" : { "rel" : this.id.toString().replace("add_", "") } });
				}				
				break;
		}
	});
});

/**
 * Config for jstree
 */
$(function () {
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
					"url" : getChildren,
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
					"url" : ajaxSearch,
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
							"image" : image_File
						}
					},
					// The `folder` type
					"folder" : {
						// can have files and other folders inside of it, but NOT `drive` nodes
						"valid_children" : [ "default", "folder" ],
						"icon" : {
							"image" : image_Folder
						}
					},
					// The `drive` nodes 
					"drive" : {
						// can have files and folders inside, but NOT other `drive` nodes
						"valid_children" : [ "default", "folder" ],
						"icon" : {
							"image" : image_Root
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
								alert('Attachements folders is not avaliable. Use the Filemanager');
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
								alert('You cannot rename this. Sorry.');
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
								alert('You cannot remove this. Sorry.');
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
				addNode, 
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
					url: removeNode,
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
				renameNode, 
				{ 
					"id" : data.rslt.obj.attr("id").replace("node_",""),
					"name" : data.rslt.new_name
				}, 
				function (r) {
					if(!r.status) {
						$.jstree.rollback(data.rlbk);
					}
				}
			);
		})
		.bind("move_node.jstree", function (e, data) {
			data.rslt.o.each(function (i) {
				$.ajax({
					async : false,
					type: 'POST',
					url: moveNode,
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
			$("#FolderId").attr('value', id);
			updateScreen(ajaxRefreshScreen, id);
		});
	/**
	 * Create the options
	 */
	$('.inner-center a').click(function(e){
		e.preventDefault();
		var rel = $(this).attr('rel');
		if(rel){
			$(rel).toggle();
		}else{
			var id = filemangerJsTree.jstree('_focused').jstree('get_selected').attr('id').replace('node_','');
			updateScreen(ajaxRefreshScreen, id);
		}
		checkOptions();
	});
	checkOptions();
});
/**
 * Organize the height of #file-view
 * @return
 */
function checkOptions(){
	var outerHeight = $('.inner-center').height();
	var innerHeight = 0;
	var divs = $('.inner-center > div:visible:not(#file-view)').each(function(index){
		innerHeight += $(this).innerHeight();
	});
	var left = outerHeight - innerHeight;
	$('#file-view').css('height', left + 'px');
}
/**
 * Update the screen of files
 */
function updateScreen(url, id){
	$("#FolderId").attr('value', id);
	$('ul[class*=.qq-upload-list]').children().remove();
	$("#geecktec_loading").show();
	$("#file-view").hide().load(
		url + id,
		function(){
			$("#geecktec_loading").hide();
			$("#file-view").show();
		}
	);
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
var selectItem = function(src){
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
//		$.prompt('The "Select" function is only used for integration with FCKEditor.');
		alert('The "Select" function is only used for integration with FCKEditor.');
	}
}

$.urlParam = function(name){
	var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
	return results[1] || 0;
}