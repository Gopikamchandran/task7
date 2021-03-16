
<?php
/**
 * Plugin Name: Job Plugin
 * Plugin URI: https://job_plugin.com/
 * Description: This is the third plugin that I'm developing. This plugin is developed to perform the task 6 that is to add job and to do functions related to it. I t must have to be able to add functions in backend and must be visible at the front end.
 * Version: 1.0
 * Author: GopikaChandran
 * Author URI: https://GopikaChandran.com/wordpress-plugins/
 *License: GPLv2 or later
 *Text Domain: job_plugin
 */
defined( 'ABSPATH' ) or die();

class job_plugin{
	
function __construct(){		
	add_action("add_meta_boxes",array($this,"custom_metabox"));
    add_action("save_post",array($this,"save_detail"));	
    add_filter('the_content',array($this,'display_fun'));
    add_action( 'init',array($this,'create_posttype' ));
    add_action( 'init',array($this,'custom_post_type'), 0);
    add_action('admin_menu',array($this,'add_setting'));
    add_action( 'admin_init',array($this,'my_settings_init') );
    //add_filter('the_content',array($this,'display_settings'));
    add_action( 'admin_enqueue_scripts', array($this,'wpse_enqueue_datepicker') );
    add_action( 'admin_enqueue_scripts', array($this,'mw_enqueue_color_picker') );
     //add_filter('the_content',array($this,'display_date'));
}

function custom_metabox(){
    add_meta_box("custom_metabox_01","Custom Metabox",array($this,"custom_metabox_field"),"jobs","side","high");
     add_meta_box("custom_metabox_02","Date Metabox",array($this,"display_cookie_meta_box"),"jobs","side","low");
}//function to add a meta box

function custom_metabox_field(){
    global $post;
    wp_nonce_field( basename( __FILE__ ), 'meta_box_nonce' );
    $data = get_post_custom($post->ID);
   /* $title_val = isset($data['jtitle'])? esc_attr($data['jtitle'][0]): 'job title';*/
    $email = isset($data['email'])? esc_attr($data['email'][0]): 'email';

    ?>

   <table>
    <tr> 
        <td>  <label for="name">email &nbsp; &nbsp;</label></td>
        <?php
        echo'<td><input type="email" name="email" id="email" value="'.$email.'"/></td>';
       ?><br>
    </tr>
  </table>

    <?php
        
}   // function to work in backend where we accept input    
function display_fun($content){
    global $post;
    $slug = "jobs";
    if($slug != $post->post_type){
        return $content;
    }
    $title_val = get_the_title();
        $email = esc_attr(get_post_meta($post->ID,'email',true));
        $cookie_date = esc_attr(get_post_meta( $post->ID, 'cookie_date', true ) );
      //  $post1 = "<div class='display_meta'>email:$email</div>";
        $post2 =  "<div class='display_meta'>expiry date:$cookie_date</div>";
         $org = esc_attr(get_option( 'my_textfield') ); 
        $options = esc_attr(get_option( 'checkbox_field' ));
        
        $radio_option = esc_attr(get_option( 'radio_field' ));
        $text_area_option = esc_attr(get_option( 'textarea_field') );
       $update_date = esc_attr(get_option( 'date_area_field') );
        $update_color = esc_attr(get_option( 'color_area_field') );

        $post3 = "<div class='display_meta'>$org</div>";
        $post4 =  "<div class='display_meta'>$options</div>";
        $post5 = "<div class='display_meta'>$radio_option</div>";
        $post6 =  "<div class='display_meta'>$text_area_option</div>";
        $post7 =  "<div class='display_meta'>$update_date</div>";
        $post8 = "<div class='display_meta'>$update_color</div>";
        $text = "<div class='display_meta'>Time Expired</div>";
        
        if($options == "true"){
           $post1 = "<div class='display_meta'>email:$email</div>"; 
            // return $post1 . $post2 . $post3 . $post4 . $post5 . $post6 . $post7. $content;
        }
        else{
           $post1="";
        }
        
        if($radio_option == "show_title"){
         
         if($cookie_date<$update_date ){
          return $text;
          /*write_log("text"); */
        }
        else{
        return $title_val;
        }
    }
    else{
        if($cookie_date<$update_date){
            return $text; 
        }
        else{
            return $title_val . $post1 . $post2 . $post3  . $post6 . $content;
        }
    }
       // return $post1 .$post2. $content;   
}

function display_cookie_meta_box( $cookie ) {
            global $post;
        // Enqueue Datepicker + jQuery UI CSS
        wp_enqueue_script( 'jquery-ui-datepicker','http://localhost/wordpress_task6/wp-content/plugins/job_plugin/myscript.js' );
        wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css', true);


        // Retrieve current date for cookie
        $cookie_date = get_post_meta( $post->ID, 'cookie_date', true  );
        ?>
        <!-- <script>
        jQuery(document).ready(function(){
        jQuery('#cookie_date').datepicker({
        dateFormat : 'dd-mm-yy'
        });
        });
        </script>
 -->
        <table>
        <tr>
        <td>Expiry Date</td>
        <td>
        <input type="text" class="date_pick" name="cookie_date" id="cookie_date" value = <?php echo esc_attr($cookie_date); ?> ></td>
        </tr>
        </table>
<?php
}
/*function display_date($post){?>
    <form action ="" method="post">
    <?php
     $cookie_date = get_post_meta( $post->ID, 'cookie_date', true  );?>
      <table>
        <tr>
        <td>Expiry Date</td>
        <td>
        <input type="text" class="date_pick" name="cookie_date" id="cookie_date" value = <?php echo esc_attr($cookie_date); ?> ></td>
        </tr>
        </table><?php
}*/
function save_detail(){
    global $post;
    if(defined('DOING_AUTOSAVE')&& DOING_AUTOSAVE){
        return $post->ID;
    }
    
    $email = sanitize_text_field($_POST['email']);
    update_post_meta($post->ID,'email',$email);
    if(isset($_POST['cookie_date'])){
         $cookie_date = ($_POST['cookie_date']);
        update_post_meta($post->ID,'cookie_date',$cookie_date);
    }
   
}


//===================================================================
/*class setting_page extends job_plugin{*/
   
   /* function __construct(){
    add_action( 'init',array($this,'create_posttype' ));
    add_action( 'init',array($this,'custom_post_type'), 0);
    add_action('admin_menu',array($this,'add_setting'));
    add_action( 'admin_init',array($this,'my_settings_init') );
    add_filter('the_content',array($this,'display_settings'));
    add_action( 'admin_enqueue_scripts', array($this,'wpse_enqueue_datepicker') );
    add_action( 'admin_enqueue_scripts', array($this,'mw_enqueue_color_picker') );
    }*/
    function create_posttype() {
 
    register_post_type( 'jobs',
    // CPT Options
    array(
        'labels' => array(
            'name' => __( 'Jobs' ),
            'singular_name' => __( 'Job' )
            ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'jobs'),
        'show_in_rest' => true,
 
        )
    );
    }

    /*function use_style() {
    wp_register_style( 'namespace', 'http://localhost/wordpress_task6/wp-content/plugins/job_plugin/style.css' );
    wp_enqueue_style( 'namespace' );
  
    }*/
    function custom_post_type() {
 
    // Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Jobs', 'Post Type General Name', 'twentytwenty' ),
        'singular_name'       => _x( 'Job', 'Post Type Singular Name', 'twentytwenty' ),
        'menu_name'           => __( 'Jobs', 'twentytwenty' ),
        'parent_item_colon'   => __( 'Parent Movie', 'twentytwenty' ),
        'all_items'           => __( 'All Jobs', 'twentytwenty' ),
        'view_item'           => __( 'View Jobs', 'twentytwenty' ),
        'add_new_item'        => __( 'Add New Job', 'twentytwenty' ),
        'add_new'             => __( 'Add New', 'twentytwenty' ),
        'edit_item'           => __( 'Edit Jobs', 'twentytwenty' ),
        'update_item'         => __( 'Update Jobs', 'twentytwenty' ),
        'search_items'        => __( 'Search Jobs', 'twentytwenty' ),
        'not_found'           => __( 'Not Found', 'twentytwenty' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentytwenty' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'jobs', 'twentytwenty' ),
        'description'         => __( 'Job vacancies and Application', 'twentytwenty' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
      //  'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
      //  'taxonomies'          => array( 'genres' ),
       /*  A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
         */
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Custom Post Type
         register_post_type( 'jobs', $args );
 
 
    }//add_action( 'init','custom_post_type', 0);


    function add_setting(){

     add_submenu_page(
                     'edit.php?post_type=jobs', //$parent_slug
                     'Job Settings',  //$page_title
                     ' Settings',        //$menu_title
                     'manage_options',           //$capability
                     'job_settings',//$menu_slug
                   //  'job_settingpage'//$function
                     array($this,'my_display_section')
     );

    }


    function my_display_section($object) {
    echo '<div class="wrap"><h2>Settings</h2></div>';
    ?>
         <form method="POST" action="options.php">

    <?php
    settings_fields('sample-page');
    do_settings_sections('sample-page');
    submit_button();?>
    </form><?php
    }


    function my_settings_init() {

        add_settings_section(
        'sample_page_setting_section',
        __( 'Custom settings', 'my-textdomain' ),
        '','sample-page'
        );

    add_settings_field(
    'my_textfield',
      __( 'Organization', 'my-textdomain' ),
      array($this,'my_org_field'),
      'sample-page',
      'sample_page_setting_section'
    );

    register_setting(
    'sample-page',
    'my_textfield',
    array(
    'type' => 'string',
    'santize_callback' => 'sanitize_key',
    'default' => ''
    )
    );

    add_settings_field(
    'checkbox_field',
    __( 'Show Email', 'my-textdomain' ),
    array($this,'checkbox_callback'),
    'sample-page',
    'sample_page_setting_section'
    );

    register_setting(
    'sample-page',
    'checkbox_field',
    array(
    'type' => 'string',
    'santize_callback' => 'sanitize_key',
    'default' => ''
    )
    );

    add_settings_field(
    'radio_field',
    __( 'Show content', 'my-textdomain' ),
    array($this,'radio_callback'),
    'sample-page',
    'sample_page_setting_section'
    );

    register_setting(
    'sample-page',
    'radio_field',
    array(
    'type' => 'string',
    'santize_callback' => 'sanitize_key',
    'default' => ''
    )
    );


    add_settings_field(
    'textarea_field',
    __( 'Update Content', 'my-textdomain' ),
    array($this,'description_field'),
    'sample-page',
    'sample_page_setting_section'
    );

    register_setting(
    'sample-page',
    'textarea_field',
    array(
    'type' => 'string',
    'santize_callback' => 'sanitize_key',
    'default' => ''
    )
    );

    add_settings_field(
    'date_area_field',
    __( 'Expiry Date', 'my-textdomain' ),
    array($this,'expirydate_field'),
    'sample-page',
    'sample_page_setting_section'
    );

    register_setting(
    'sample-page',
    'date_area_field',
    array(
    'type' => 'string',
    'santize_callback' => 'sanitize_key',
    'default' => ''
    )
    );

    add_settings_field(
    'color_area_field',
    __( 'Color Picker', 'my-textdomain' ),
    array($this,'colorarea_field'),
    'sample-page',
    'sample_page_setting_section'
    );

    register_setting(
    'sample-page',
    'color_area_field',
    array(
    'type' => 'string',
    'santize_callback' => 'sanitize_key',
    'default' => ''
    )
    );
    }
    function wpse_enqueue_datepicker() {
    // Load the datepicker script (pre-registered in WordPress).

    wp_enqueue_script( 'jquery-ui-datepicker','http://localhost/wordpress_task6/wp-content/plugins/job_plugin/myscript.js');

    wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css',true);  ?>
       <?php 
}

function mw_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('myscript.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

        function my_org_field() {
        ?>

        <label for = "my_textfield"></label>
        <input type = "text" id = "my_textfield" name = "my_textfield"
        value = "<?php echo get_option( 'my_textfield' ); ?>"><br><br><br>

        <?php
        }

        function checkbox_callback() {
        $options = get_option( 'checkbox_field' );
        if($options == ""){
        ?>

        <input name = "checkbox_field" type = "checkbox" value = "true" ><br><br><br>
        <?php
        }else if($options == "true")
        {
        ?>

        <input name = "checkbox_field" type = "checkbox" value = "true" checked><br><br><br>

        <?php
        }
        }

        function radio_callback() {
        $radio_option = get_option( 'radio_field' );
        ?>

        <input type = "radio" id = "title" name = "radio_field" value = "show_title" <?php checked('show_title',$radio_option);?>>
        <label for = "title">Show Title Only</label>
        <input type = "radio" id = "title_content" name = "radio_field" value = "show_title_content" <?php checked('show_title_content',$radio_option);?>>
        <label for = "title_content">Show Title and Content</label><br><br><br>

        <?php
        }

//$title_val = get_the_title( int|WP_Post $post );
        function description_field() {
        $text_area_option = get_option( 'textarea_field' );
        ?>

        <textarea rows = "4" cols = "80" name = "textarea_field" ><?php echo isset($text_area_option) ? esc_textarea($text_area_option):'';?></textarea><br><br><br>

        <?php
        }

        function expirydate_field() {?>
            <!--  <script>
        jQuery(document).ready(function(){
        jQuery('#date_area_field').datepicker({
        dateFormat : 'dd-mm-yy'
        });
        });
         </script> --> <?php
        $update_date = get_option( 'date_area_field' );
        ?>
         
        <label for date_area_field></label>
        <input type = "text" id="date_area_field" class = "cookie_date" name = "date_area_field" value="<?php echo $update_date ?>"><br><br><br>
        <?php
        }

        function colorarea_field() {
             wp_enqueue_style( 'wp-color-picker','//ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css',true );
             wp_enqueue_script( 'my-script-handle', plugins_url('myscript.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
            ?>
             <script>
        jQuery(document).ready(function($){
    $('.my-color-field').wpColorPicker();
});
        </script>
            <?php
        $update_color = get_option( 'color_area_field' );
        ?>

        <input type = "text" id="my-color-field" class = "my-color-field" data-default-color = "#effeff" name = "color_area_field" value = "<?php echo esc_textarea($update_color) ?>"><br><br><br>

        <?php
        }

     /*   function display_settings($content){
                global $post;
        $org = esc_attr(get_option( 'my_textfield') ); 
        $options = esc_attr(get_option( 'checkbox_field' ));
        $radio_option = esc_attr(get_option( 'radio_field' ));
        $text_area_option = esc_attr(get_option( 'textarea_field') );
       $update_date = esc_attr(get_option( 'date_area_field') );
        $update_color = esc_attr(get_option( 'color_area_field') );

        $post1 = "<div class='display_meta'>$org</div>";
        $post2 =  "<div class='display_meta'>$options</div>";
        $post3 = "<div class='display_meta'>$radio_option</div>";
        $post4 =  "<div class='display_meta'>$text_area_option</div>";
        $post5 =  "<div class='display_meta'>$update_date</div>";
        $post6 = "<div class='display_meta'>$update_color</div>";
        
        return $post1 . $post2 . $post3 . $post4 . $post5  . $content;
        }*/


}

/*}
 $setting = new setting_page();*/
 $job = new job_plugin();
    if (!function_exists('write_log')) {
     function write_log ( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }
} ?>
