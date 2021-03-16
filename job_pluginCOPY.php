
	// Our custom post type function
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
// Hooking up our function to theme setup
   add_action( 'init','create_posttype' );

function custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Jobs', 'Post Type General Name', 'twentytwenty' ),
        'singular_name'       => _x( 'Job', 'Post Type Singular Name', 'twentytwenty' ),
        'menu_name'           => __( 'Jobs', 'twentytwenty' ),
       // 'parent_item_colon'   => __( 'Parent Movie', 'twentytwenty' ),
        'all_items'           => __( 'All Jobs', 'twentytwenty' ),
        'view_item'           => __( 'View Jobs', 'twentytwenty' ),
        'add_new_item'        => __( 'Add New Job', 'twentytwenty' ),
        'add_new'             => __( 'Add New', 'twentytwenty' ),
        'edit_item'           => __( 'Edit Movie', 'twentytwenty' ),
        'update_item'         => __( 'Update Movie', 'twentytwenty' ),
        'search_items'        => __( 'Search Movie', 'twentytwenty' ),
        'not_found'           => __( 'Not Found', 'twentytwenty' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentytwenty' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'jobs', 'twentytwenty' ),
        'description'         => __( 'Job vacancies and Application', 'twentytwenty' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
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
 //  add_submenu_page( string $jobs, string $settings, string $jobs, callable $function = 'custom_post_type', int $position = null )
 
}add_action( 'init','custom_post_type', 0);



//admin_menu callback function

function add_tutorial_cpt_submenu_example(){

     add_submenu_page(
                     'edit.php?post_type=jobs', //$parent_slug
                     'Job Settings',  //$page_title
                     ' Settings',        //$menu_title
                     'manage_options',           //$capability
                     'job_settings',//$menu_slug
                     'job_settingpage'//$function
     );

}
add_action('admin_menu', 'add_tutorial_cpt_submenu_example');


//add_submenu_page callback function

function job_settingpage() {

     echo '<h2> Tutorial Subpage Example </h2>';

}
//}
//$job=new job_plugin();

?>