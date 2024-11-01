<?php
/*
Plugin Name: UMTS / HSDPA Verfügbarkeit-Widget
Description: Mit den Widgets von hsdpa-umts-verfuegbarkeit.de können Sie sich die UMTS + HSDPA Multi-Verfügbarkeitsabfrage aller Netzbetreiber direkt auf Ihr Blog holen! Das Beste daran: Sie bieten Ihren Besuchern einen inhaltlichen Mehrwert mit dem Sie gleichzeitig höchst attraktive Affiliate-Provisionen der Netzbetreiber verdienen können (alle Provisionen von Vodafone und E-Plus gehen komplett an Sie, das sind bis zu 100€ pro vermitteltem Kunden).
Author: hsdpa-umts-verfuegbarkeit.de
Version: 1.2
Plugin URI: http://www.hsdpa-umts-verfuegbarkeit.de/widgets/
Author URI: http://www.hsdpa-umts-verfuegbarkeit.de/
*/

/*
    Copyright 2009 hsdpa-umts-verfuegbarkeit.de

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

define('HUV_HOWMANY', 20);

function widget_hsdpaumtsverfuegbarkeit_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;

	if ( !function_exists('htmlspecialchars_decode') ){
	    function htmlspecialchars_decode($text){
	        return strtr($text, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
	    }
	}

	if (!function_exists('attribute_escape')){
		function attribute_escape($text) {
			$safe_text = wp_specialchars($text, true);
			return apply_filters('attribute_escape', $safe_text, $text);
		}
	}

	class hsdpaumtsverfuegbarkeitwidget
	{

		var $number;
		var $title;
		var $format;
		var $color;
		var $publisherId;
		var $trackingSubId;
		var $poweredByLink;

		function load_options( $args = false ){
			if (is_array($args)){
				$this->number = 0;
				$options[0]['title'] = $args[0];
				$options[0]['format'] = $args[1];
				$options[0]['color'] = $args[2];
				$options[0]['publisherId'] = $args[3];
				$options[0]['trackingSubId'] = $args[4];
				$options[0]['poweredByLink'] = $args[5];
			}else{
				$options = get_option('widget_hsdpaumtsverfuegbarkeit');
			}
			$this->title = $options[$this->number]['title'];
			$this->format = $options[$this->number]['format'];
			$this->color = $options[$this->number]['color'];
			$this->publisherId = $options[$this->number]['publisherId'];
			$this->trackingSubId = $options[$this->number]['trackingSubId'];
			$this->poweredByLink = $options[$this->number]['poweredByLink'];

			return true;
		}

		function prepare_widget(){
			if (!$this->load_options())
				return false;
			return true;
		}

		function display_widget( $args, $num = 1 ){
			$this->number = $num;
			if (!$this->prepare_widget()){
				echo '<!-- Das UMTS / HSDPA Verfügbarkeit-Widget konnte nicht vorbereitet werden. -->';
				return;
			}
			extract( $args );
			echo $before_widget;
			echo $before_title . $this->title . $after_title;

			$formatArray = explode('x', $this->format);
			$width = $formatArray[0];
			$height = $formatArray[1];
			//content hier rein <----
			echo '<br /><iframe style="width: '.$width.'px; height: '.$height.'px; margin: 0px; border: 0px none; overflow: hidden;" src="http://www.hsdpa-umts-verfuegbarkeit.de/widget.php?f='.urlencode($width).'&c='.urlencode($this->color).'&aid='.urlencode($this->publisherId).'&sid='.urlencode($this->trackingSubId).'" scrolling="no" frameborder="0" ></iframe><br />'.($this->poweredByLink == 1 ? '<div style="font-size: 9px;">by <a href="http://www.hsdpa-umts-verfuegbarkeit.de/">hsdpa-umts-verfuegbarkeit.de</a></div><br />' : '');
			//content ende <----

			echo $after_widget;
		}

	}

	function widget_hsdpaumtsverfuegbarkeit( $args, $number = 1 ){
		global $hsdpaumtsverfuegbarkeit;
		$hsdpaumtsverfuegbarkeit->display_widget( $args, $number );
	}

	function widget_hsdpaumtsverfuegbarkeit_control($number) {
		$options = get_option('widget_hsdpaumtsverfuegbarkeit');
		$newoptions = $options;

		if ( $_POST["huv-submit-$number"] ) {
			$newoptions[$number]['title'] = htmlspecialchars_decode( stripslashes($_POST["huv-title-$number"]) );
			$newoptions[$number]['format'] = htmlspecialchars_decode( stripslashes($_POST["huv-format-$number"]) );
			$newoptions[$number]['color'] = htmlspecialchars_decode( stripslashes($_POST["huv-color-$number"]) );
			$newoptions[$number]['publisherId'] = htmlspecialchars_decode( stripslashes($_POST["huv-publisherId-$number"]) );
			$newoptions[$number]['trackingSubId'] = htmlspecialchars_decode( stripslashes($_POST["huv-trackingSubId-$number"]) );
			$newoptions[$number]['poweredByLink'] = htmlspecialchars_decode( stripslashes($_POST["huv-poweredByLink-$number"]) );
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_hsdpaumtsverfuegbarkeit', $options);
		}

		$poweredByLink = htmlspecialchars($options[$number]['poweredByLink'], ENT_QUOTES);
		$title = htmlspecialchars($options[$number]['title'], ENT_QUOTES);
		if ( '' == $title ){
			$title = "UMTS / HSDPA Verfügbarkeit";
		}
		$format = htmlspecialchars($options[$number]['format'], ENT_QUOTES);
		if ( '' == $format ){
			$format = "200x200";
		}
		$color = htmlspecialchars($options[$number]['color'], ENT_QUOTES);
		if ( '' == $color ){
			$color = 1;
			$poweredByLink = 1;
		}
		$publisherId = htmlspecialchars($options[$number]['publisherId'], ENT_QUOTES);
		$trackingSubId = htmlspecialchars($options[$number]['trackingSubId'], ENT_QUOTES);
	?>
				<div id="huv_settings_<?php echo $number; ?>">
					<table>
					<tr>
						<td><?php _e('Titel:', 'huvwidgets'); ?> </td>
						<td><input style="width: 300px;" id="huv-title-<?php echo "$number"; ?>" name="huv-title-<?php echo "$number"; ?>" type="text" value="<?php echo $title; ?>" /></td>
					</tr>
					<tr>
            <td><label for="huv-format-<?php echo "$number"; ?>"><?php _e('Format:', 'huvwidgets'); ?></label></td>
  					<td><select id="huv-format-<?php echo "$number"; ?>" name="huv-format-<?php echo "$number"; ?>">
  					  <option value="200x200" <?php if($format == "200x200") { echo 'selected="selected"'; } ?> >200x200</option>
  					  <option value="180x200" <?php if($format == "180x200") { echo 'selected="selected"'; } ?> >180x200</option>
  					  <option value="150x200" <?php if($format == "150x200") { echo 'selected="selected"'; } ?> >150x200</option>
  					  <option value="145x200" <?php if($format == "145x200") { echo 'selected="selected"'; } ?> >145x200</option>
  					  <option value="140x200" <?php if($format == "140x200") { echo 'selected="selected"'; } ?> >140x200</option>
            </select></td>
					</tr>
					<tr>
            <td><label for="huv-color-<?php echo "$number"; ?>"><?php _e('Farbvorlage:', 'huvwidgets'); ?></label></td>
  					<td><select id="huv-color-<?php echo "$number"; ?>" name="huv-color-<?php echo "$number"; ?>">
  					  <option value="1" <?php if($color == 1) { echo 'selected="selected"'; } ?> >Orange</option>
  					  <option value="2" <?php if($color == 2) { echo 'selected="selected"'; } ?> >Blau</option>
  					  <option value="3" <?php if($color == 3) { echo 'selected="selected"'; } ?> >Grau</option>
            </select></td>
					</tr>
					<tr>
						<td><?php _e('Affilinet Publisher ID:', 'huvwidgets'); ?> </td>
						<td><input style="width: 100px;" id="huv-publisherId-<?php echo "$number"; ?>" name="huv-publisherId-<?php echo "$number"; ?>" type="text" value="<?php echo $publisherId; ?>" /></td>
					</tr>
					<tr>
						<td><?php _e('Affilinet Tracking SubID (optional):', 'huvwidgets'); ?> </td>
						<td><input style="width: 100px;" id="huv-trackingSubId-<?php echo "$number"; ?>" name="huv-trackingSubId-<?php echo "$number"; ?>" type="text" value="<?php echo $trackingSubId; ?>" /></td>
					</tr>
					<tr>
						<td><?php _e('\'by hsdpa-umts-verfuegbarkeit.de\' Link anzeigen:', 'huvwidgets'); ?> </td>
						<td><input style="width: 100px;" id="huv-poweredByLink-<?php echo "$number"; ?>" name="huv-poweredByLink-<?php echo "$number"; ?>" type="checkbox" value="1"<?php echo ($poweredByLink == 1 ? ' checked="checked"' : ''); ?> /></td>
					</tr>
					</table>
					<input type="hidden" id="huv-submit-<?php echo "$number"; ?>" name="huv-submit-<?php echo "$number"; ?>" value="1" />
				</div>
	<?php
	}

	function widget_hsdpaumtsverfuegbarkeit_setup() {
		$options = $newoptions = get_option('widget_hsdpaumtsverfuegbarkeit');
		if ( isset($_POST['huv-number-submit']) ) {
			$number = (int) $_POST['huv-number'];
			if ( $number > HUV_HOWMANY ) $number = HUV_HOWMANY;
			if ( $number < 1 ) $number = 1;
			$newoptions['number'] = $number;
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_hsdpaumtsverfuegbarkeit', $options);
			widget_hsdpaumtsverfuegbarkeit_register($options['number']);
		}
	}

	function widget_hsdpaumtsverfuegbarkeit_page() {
		$options = $newoptions = get_option('widget_hsdpaumtsverfuegbarkeit');
	?>
		<div class="wrap">
			<form method="POST">
				<h2>UMTS / HSDPA Verfügbarkeit-Widgets</h2>
				<p style="line-height: 30px;"><?php _e('Wieviele UMTS / HSDPA Verfügbarkeit-Widgets möchten Sie gleichzeitig verwenden?', 'huvwidgets'); ?>
				<select id="huv-number" name="huv-number" value="<?php echo $options['number']; ?>">
	<?php for ( $i = 1; $i <= HUV_HOWMANY; $i++ ) echo "<option value='$i' ".($options['number']==$i ? "selected='selected'" : '').">$i</option>"; ?>
				</select>
				<span class="submit"><input type="submit" name="huv-number-submit" id="huv-number-submit" value="<?php _e('Save'); ?>" /></span></p>
			</form>
		</div>
	<?php
	}

	function widget_hsdpaumtsverfuegbarkeit_register() {
		global $wp_version;
		$options = get_option('widget_hsdpaumtsverfuegbarkeit');
		$number = $options['number'];
		if ( $number < 1 ) $number = 1;
		if ( $number > HUV_HOWMANY ) $number = HUV_HOWMANY;
		for ($i = 1; $i <= HUV_HOWMANY; $i++) {
			$name = 'UMTS / HSDPA Verfügbarkeit';
			if ( '2.2' == $wp_version ){
				register_sidebar_widget($name, $i <= $number ? 'widget_hsdpaumtsverfuegbarkeit' : /* unregister */ '', '', $i);
				register_widget_control($name, $i <= $number ? 'widget_hsdpaumtsverfuegbarkeit_control' : /* unregister */ '', 700, 580, $i);
			}elseif ( function_exists( 'wp_register_sidebar_widget' ) ){
				$id = "hsdpaumtsverfuegbarkeit-$i";
				$dims = array('width' => 700, 'height' => 580);
				$class = array( 'classname' => 'widget_hsdpaumtsverfuegbarkeit' );
				$name = __('UMTS / HSDPA Verfügbarkeit');
				wp_register_sidebar_widget($id, $name, $i <= $number ? 'widget_hsdpaumtsverfuegbarkeit' : /* unregister */ '', $class, $i);
				wp_register_widget_control($id, $name, $i <= $number ? 'widget_hsdpaumtsverfuegbarkeit_control' : /* unregister */ '', $dims, $i);
			}else{
				register_sidebar_widget($name, $i <= $number ? 'widget_hsdpaumtsverfuegbarkeit' : /* unregister */ '', $i);
				register_widget_control($name, $i <= $number ? 'widget_hsdpaumtsverfuegbarkeit_control' : /* unregister */ '', 700, 580, $i);
			}
		}

		//add_action('sidebar_admin_setup', 'widget_hsdpaumtsverfuegbarkeit_setup');
		//add_action('sidebar_admin_page', 'widget_hsdpaumtsverfuegbarkeit_page');
	}

	$GLOBALS['hsdpaumtsverfuegbarkeit'] = new hsdpaumtsverfuegbarkeitwidget();
	widget_hsdpaumtsverfuegbarkeit_register();

}

add_action('widgets_init', 'widget_hsdpaumtsverfuegbarkeit_init');

?>