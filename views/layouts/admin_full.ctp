<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $title_for_layout; ?> - <?php __('Croogo'); ?></title>
    <?php
        echo $html->script(array('jquery/jquery.min'));
        echo $layout->js();
        echo $html->css(array(
            'reset',
            '960',
            '/ui-themes/smoothness/jquery-ui.css',
            'admin',
            'thickbox',
        ));       
        echo $html->script(array(
            'jquery/jquery-ui.min',
            'jquery/jquery.uuid',
            'jquery/jquery.cookie',
            'jquery/jquery.hoverIntent.minified',
            'jquery/superfish',
            'jquery/supersubs',
            'jquery/jquery.tipsy',
            'jquery/jquery.elastic-1.6.1.js',
            'jquery/thickbox-compressed',
        	'/geecktec_filemanager/js/jquery.tools',
            'admin',
        ));
        echo $scripts_for_layout;
    ?>
    
<style type="text/css">
	#container {
		background:	#999;
		height:		100%;
		margin:		0 auto;
		width:		100%;
		min-width:	700px;
		min-height: 400px;
		_width:		700px; /* min-width for IE6 */
	}
	.pane {
		display:	none; /* will appear when layout inits */
	}
</style>
    
</head>

<body>

    <div id="wrapper">
        <div id="header">
            <div class="container_16">
                <div class="grid_8">
                    <div id="logo">
                        <?php echo $this->element('admin/logo'); ?>
                    </div>
                </div>
                <div class="grid_8">
                    <?php echo $this->element('admin/quick'); ?>
                </div>
                <div class="clear">&nbsp;</div>
            </div>
        </div>

        <div id="main" class="container_16">
            <div class="grid_16">
                <div id="content">
                    <?php
                        $layout->sessionFlash();
                        echo $content_for_layout;
                    ?>
                </div>
            </div>
            <div class="clear">&nbsp;</div>
        </div>

        <div class="push"></div>
    </div>

    <?php echo $this->element('admin/footer'); ?>

    </body>
</html>