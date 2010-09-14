/**
 * Tabs and menu
 */
$(function(){
	var $filemanager = $("#filemanager");
	$(".ui-state-default").hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	);
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
	
	// Tabs
	var $tabs = $('#tabs').tabs({
		tabTemplate: '<li><a href="#{href}">#{label}</a> <span class="ui-icon ui-icon-close">Remove Tab</span></li>',
		add: function(event, ui) {
			var id = $(ui.panel).attr('id').replace('tabs-', '');
//			alert(ajaxRefreshScreen + id);
			$(ui.panel).load(ajaxRefreshScreen + id, function(){
			});
		}
	});
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
	
	// close icon: removing the tab on click
	// note: closable tabs gonna be an option in the future - see http://dev.jqueryui.com/ticket/3924
	$('#tabs span.ui-icon-close').live('click', function() {
		var index = $('li',$tabs).index($(this).parent());
		$tabs.tabs('remove', index);
	});
	// Tabs sortable
	$tabs.find(".ui-tabs-nav").sortable({axis: 'x'});
	
	$(".imagens").sortable();

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
			var title = data.rslt.obj.find('a:first').text();
			addTab(id, title);
		}).droppable({
//			accept: '#imagens thumb'
		});
	$("#folder-tree > ul:first").addClass('ui-widget-content');
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
//	checkOptions();
});