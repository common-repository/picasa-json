<?php
/*
Plugin Name: Picasa Web Albums sidebar widget
Plugin URI: http://www.sparkos.com/downloads/wordpress/picasawebalbumssidebarwidget/
Description: Sidebar widget which displays photos/videos as a list or thumbnails from public & unlisted Picasa Web Albums.
Author: James Peek
Version: 0.2
Author URI: http://www.sparkos.com/
*/

function widget_picasa_json_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;


	function widget_picasa_json($args, $widget_args = 1) {
		extract($args, EXTR_SKIP);
		if (is_numeric($widget_args)) $widget_args = array( 'number' => $widget_args);
		$widget_args = wp_parse_args($widget_args, array('number' => -1));
		extract($widget_args, EXTR_SKIP);

		$options = get_option('widget_picasa_json');

		if (!isset($options[$number]))
			return;

		$title = $options[$number]['title'];
		$viewAs = $options[$number]['viewAs'];

		// hack to make list appear like widget_links (for fusion theme)
		if ($viewAs == "list") $before_widget = str_replace('widget_picasa_json', 'widget_picasa_json widget_links', $before_widget);

		echo $before_widget . $before_title . $title . $after_title;
		echo '<ul class="' . $viewAs . '"></ul>';
		echo $after_widget;
	}

	function widget_picasa_json_control($widget_args) {
		global $wp_registered_widgets;
		static $updated = false;

		if (is_numeric($widget_args)) $widget_args = array('number' => $widget_args);
		$widget_args = wp_parse_args($widget_args, array('number' => -1));
		extract($widget_args, EXTR_SKIP);

		$options = get_option('widget_picasa_json');
		if (!is_array($options)) $options = array();

		if ( !$updated && 'POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['sidebar']) ) {

			$sidebar = (string) $_POST['sidebar'];

			$sidebars_widgets = wp_get_sidebars_widgets();
			if (isset($sidebars_widgets[$sidebar]) )
				$this_sidebar =& $sidebars_widgets[$sidebar];
			else
				$this_sidebar = array();

			foreach ((array) $this_sidebar as $_widget_id) {
				if ('widget_picasa_json' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number'])) {
					$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
					if ( !in_array( "widget_picasa_json_$widget_number", $_POST['widget-id'] ) ) // the widget has been removed.
						unset($options[$widget_number]);
				}
			}

			foreach ((array) $_POST['widget_picasa_json'] as $widget_number => $widget_obj) {
				if ( !isset($widget_obj['userid']) && isset($options[$widget_number]) ) // user clicked cancel
					continue;				

				$widget_obj = stripslashes_deep($widget_obj);

				$title = strip_tags($widget_obj['title']);
				$userid = strip_tags($widget_obj['userid']);
				$albumid = strip_tags($widget_obj['albumid']);
				$authkey = strip_tags($widget_obj['authkey']);
				$maxPhotos = strip_tags($widget_obj['maxPhotos']);
				$thumbsize = strip_tags($widget_obj['thumbsize']);
				$largesize = strip_tags($widget_obj['largesize']);
				$viewAs = strip_tags($widget_obj['viewAs']);
				if (strip_tags($widget_obj['showTitles'])) {
					$showTitles = strip_tags($widget_obj['showTitles']);
				} else {
					$showTitles = 0;
				}

				$options[$widget_number] = compact('title', 'userid', 'albumid', 'authkey', 'maxPhotos', 'thumbsize', 'largesize', 'viewAs', 'showTitles');
			}
	
			update_option('widget_picasa_json', $options);
			$updated = true;
		}
	
		if (-1 == $number) {
			$title = '';
			$userid = '';
			$albumid = '';
			$authkey = '';
			$maxPhotos = '16';
			$thumbsize = '48';
			$largesize = '720';
			$viewAs = 'thumbs';
			$showTitles = '';
			$number = '%i%';
		} else {
			extract((array) $options[$number]);

			if ($showTitles) $showTitles = 'checked="true"'; 
		}

	
		echo '<input type="hidden" name="widget_picasa_json[' . $number . '][submit]" value="1" />';
		echo '<p><label for="widget_picasa_json_title-' . $number . '">' . __('Title:') . ' <input class="widefat" id="widget_picasa_json_title-' . $number . '" name="widget_picasa_json[' . $number . '][title]" type="text" value="'.$title.'" /></label></p>';

		echo '<p><label for="widget_picasa_json_rssurl-' . $number . '">' . __('RSS URL:') . ' <input class="widefat" id="widget_picasa_json_rssurl-' . $number . '" type="text" onblur="if (!this.value.length) return; a=this.value.split(\'/\'); document.getElementById(\'widget_picasa_json_userid-' . $number . '\').value=a[a.length-3]; document.getElementById(\'widget_picasa_json_albumid-' . $number . '\').value=a[a.length-1].split(\'?\')[0]; document.getElementById(\'widget_picasa_json_authkey-' . $number . '\').value=a[a.length-1].split(\'?\')[1].split(\'&\')[2].split(\'=\')[1]; this.value=\'\'" /></label></p>';

		echo '<p><label for="widget_picasa_json_userid-' . $number . '">' . __('User ID:', 'widgets') . ' <input class="widefat" id="widget_picasa_json_userid-' . $number . '" name="widget_picasa_json[' . $number . '][userid]" type="text" value="'.$userid.'" /></label></p>';
		echo '<p><label for="widget_picasa_json_albumid-' . $number . '">' . __('Album ID:', 'widgets') . ' <input class="widefat" id="widget_picasa_json_albumid-' . $number . '" name="widget_picasa_json[' . $number . '][albumid]" type="text" value="'.$albumid.'" /></label></p>';
		echo '<p><label for="widget_picasa_json_authkey-' . $number . '">' . __('AuthKey:', 'widgets') . ' <input class="widefat" id="widget_picasa_json_authkey-' . $number . '" name="widget_picasa_json[' . $number . '][authkey]" type="text" value="'.$authkey.'" /></label></p>';
		echo '<p><label for="widget_picasa_json_maxPhotos-' . $number . '">' . __('Max items:', 'widgets') . ' <input class="widefat" id="widget_picasa_json_maxPhotos-' . $number . '" name="widget_picasa_json[' . $number . '][maxPhotos]" type="text" value="'.$maxPhotos.'" /></label></p>';

		echo '<p><label for="widget_picasa_json_viewAs-' . $number . '">' . __('View as:', 'widgets') . ' <select id="widget_picasa_json_viewAs-' . $number . '" name="widget_picasa_json[' . $number . '][viewAs]" onchange="document.getElementById(\'widget_picasa_json-thumbsize-' . $number . '-box\').style.display = document.getElementById(\'widget_picasa_json-thumbsize-' . $number . '-box\').style.display = (this.selectedIndex == 1) ? \'block\' : \'none\'">';
		$views = array("list","thumbs");
		foreach ($views as $view) {
			if ($view == $viewAs) {
				echo '<option selected="true">' . $view . '</option>';
			} else {
				echo '<option>' . $view . '</option>';
			}
		}
		echo '</select></label></p>';

		if ($viewAs != 'thumbs') {
			echo '<p id="widget_picasa_json_thumbsize-' . $number . '-box" style="display:none">';
		} else {
			echo '<p id="widget_picasa_json_thumbsize-' . $number . '-box">';
		}
		echo '<label for="widget_picasa_json_thumbsize-' . $number . '">' . __('Thumb size:', 'widgets') . ' <select id="widget_picasa_json_thumbsize-' . $number . '" name="widget_picasa_json[' . $number . '][thumbsize]">';
		$sizes = array("32","48","64","72","144","160");
		foreach ($sizes as $size) {
			if ($size == $thumbsize) {
				echo '<option selected="true">' . $size . '</option>';
			} else {
				echo '<option>' . $size . '</option>';
			}
		}
		echo '</select></label></p>';

		echo '<p><label for="widget_picasa_json_largesize-' . $number . '">' . __('Large size:', 'widgets') . ' <select id="widget_picasa_json_largesize-' . $number . '" name="widget_picasa_json[' . $number . '][largesize]">';
		$sizes = array(200, 288, 320, 400, 512, 576, 640, 720, 800);
		foreach ($sizes as $size) {
			if ($size == $largesize) {
				echo '<option selected="true">' . $size . '</option>';
			} else {
				echo '<option>' . $size . '</option>';
			}
		}
		echo '</select></label></p>';

		echo '<p><input class="checkbox" id="widget_picasa_json_showTitles-' . $number . '" name="widget_picasa_json[' . $number . '][showTitles]" type="checkbox" value="1" ' . $showTitles . ' /> <label for="widget_picasa_json_showTitles-' . $number . '">' . __('Show image titles in popup') . '</label></p>';
	}


	if (!$options = get_option('widget_picasa_json')) $options = array();

	$widget_ops = array('classname' => 'widget_picasa_json', 'description' => __('Displays content from public and unlisted Picasa web albums via JSON'));
	$control_ops = array('id_base' => 'picasa_json');
	$name = __('Picasa Web Album');

	$id = false;
	foreach ((array) array_keys($options) as $o) {
		$id = "picasa_json-" . $o;
		wp_register_sidebar_widget($id, $name, 'widget_picasa_json', $widget_ops, array('number' => $o));
		wp_register_widget_control($id, $name, 'widget_picasa_json_control', $control_ops, array('number' => $o));
	}
	
	// If there are none, we register the widget's existance with a generic template
	if (!$id) {
		wp_register_sidebar_widget( 'picasa_json-1', $name, 'widget_picasa_json', $widget_ops, array('number' => -1));
		wp_register_widget_control( 'picasa_json-1', $name, 'widget_picasa_json_control', $control_ops, array('number' => -1));
	}
}


function widget_picasa_json_header() {
	$options = get_option('widget_picasa_json');

	echo '<script type="text/javascript" src="'.get_option('home').'/'.PLUGINDIR.'/picasa-json/'.'picasa-json.js"></script>';
	echo '<link rel="stylesheet" href="'.get_option('home').'/'.PLUGINDIR.'/picasa-json/'.'picasa-json.css" type="text/css" media="screen" />
';
}

function widget_picasa_json_footer() {
	$options = get_option('widget_picasa_json');

	if (is_home()) {
		$date = 'new Date()';
	} else {
		$date = the_date('Y,n-1,j', 'new Date(', ')', false);
	}

	foreach ((array) array_keys($options) as $o) {
		$userid = $options[$o]['userid'];
		$albumid = $options[$o]['albumid'];
		$authkey = $options[$o]['authkey'];
		$thumbsize = $options[$o]['thumbsize'];
		$largesize = $options[$o]['largesize'];
		$maxPhotos = $options[$o]['maxPhotos'];
		$viewAs = $options[$o]['viewAs'];
		$showTitles = $options[$o]['showTitles'];

		echo '<script type="text/javascript">function loadPhotoData'.$o.'(data) { loadPhotoData(data, {id:' . $o . ', thumbsize:' . $thumbsize . ', maxPhotos:' . $maxPhotos . ', viewAs:"' . $viewAs . '", showTitles:' . $showTitles . ', date:' . $date . '}); }</script>';
		echo '<script src="http://picasaweb.google.com/data/feed/api/user/' . $userid . '/albumid/' . $albumid . '?kind=photo&alt=json&callback=loadPhotoData'.$o.'&authkey=' . $authkey . '&imgmax=' . $largesize . '&thumbsize=' . $thumbsize . 'c"></script>';
	}

}

add_action('plugins_loaded', 'widget_picasa_json_init');
add_action('wp_head', 'widget_picasa_json_header');
add_action('wp_footer', 'widget_picasa_json_footer');

?>
