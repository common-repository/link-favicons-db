<?php
/*
Plugin Name: link favicons db
Description: Fill empty link_image and link_description field in table wp_links where link_image is retrieved via Google S2 Converter and link_description is the same as link_name
Author: yun77op
Version: 1.0
Author URI: http://devilalbum.com
*/


register_activation_hook( __FILE__, 'link_favicons_init' );
register_deactivation_hook( __FILE__, 'link_favicons_deact' );

function link_favicons_deact(){

$options = get_options();
if($options['deact_clean']=='1'){
global $wpdb;
	if($options['init_des']=='1'){
	$wpdb->query( $wpdb->prepare( "
		UPDATE $wpdb->links SET link_image = %s, link_description = %s", 
		'', '') );
	}else {
	$wpdb->query( $wpdb->prepare( "
		UPDATE $wpdb->links SET link_image = %s", 
		'') );
	}
}
delete_option('lf_options');

}
function get_options() {
		$options = get_option('lf_options');
		if (!is_array($options)) {
			$options['deact_clean']='-1';
			$options['init_des']='-1';
			update_option('lf_options', $options);
		}
		return $options;
	}


function link_favicons_init(){

$options=get_options();

global $wpdb;
$links=$wpdb->get_results("SELECT link_id, link_name, link_url FROM $wpdb->links");

	foreach($links as $link){
	
	$link_image = preg_replace('/^http:\/\//', '', $link->link_url);
	$pos=strpos($link_image,'/');
	if($pos!==false){
	$link_image=substr($link_image,0,$pos);
	}
$link_image="http://www.google.com/s2/favicons?domain=" . $link_image;
	
	$wpdb->query( $wpdb->prepare( "
		UPDATE $wpdb->links SET link_image = %s
		WHERE link_id = %d AND link_image = '' ", 
		$link_image, $link->link_id ) );
		
	if($options['init_des']=='-1') {
	$wpdb->query( $wpdb->prepare( "
		UPDATE $wpdb->links SET link_description = %s
		WHERE link_id = %d AND link_description = '' ", 
		$link->link_name, $link->link_id ) );
		}
	}
}



add_action('admin_menu', 'link_favicons_opt_menu');

function link_favicons_opt_menu() {
	add_options_page('Link Favicons DB Plugin Settings', 'link favicons db', 'administrator',__FILE__, 'link_favicons_page');

}


function link_favicons_page(){
if(!current_user_can('manage_options') ){
    print "<div class='error'>Permission denied.</div>\n";
}

if( $_POST[ 'lf_submitted' ]=='Y' ) 
{	
	check_admin_referer('lf_update_options');
	$deact_clean = ($_POST['deact_clean']=='1') ? '1':'-1';
	$init_des = ($_POST['init_des']=='1') ? '1':'-1';

$options=array(
'deact_clean' => $deact_clean,
'init_des' => $init_des
);
update_option('lf_options',$options);
link_favicons_init();
?>
<div class="updated"><p><strong><?php _e('Settings saved.', 'lf-domain' ); ?></strong></p></div>
<?php
}
$options=get_options();
?>
<div class="wrap">
<h2><?php _e( 'Link Favicons Db Plugin Options', 'lf-domain' ); ?></h2>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<?php wp_nonce_field('lf_update_options'); ?>
<input type="hidden" name="lf_submitted" value="Y">

 <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes','lf-domain') ?>" />
</p>

<table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e("Restore the <em>link_image</em> and <em>link_description</em> field in table <em>wp_links</em> to null on deactivation?", 'lf-domain' ); ?></th>
        <td><input name="deact_clean" type="checkbox" value="1" <?php if($options['deact_clean']=='1') echo "checked='checked'"; ?> /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><?php _e("Set the <em>link_description</em> the same as <em>link_name</em> in table <em>wp_links</em> on activation?", 'link_favicons?' ); ?></th>
        <td><input name="init_des" type="checkbox" value="1" <?php if($options['init_des']=='1') echo "checked='checked'"; ?> /></td>
        </tr>
    </table>

 <p class="submit">
    <input type="submit" class="button-primary"  value="<?php _e('Save Changes','lf-domain') ?>" />
 </p>


</form>
</div>

<?php
}

?>
