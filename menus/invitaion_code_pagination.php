<?php
if( !class_exists( 'WP_List_Table' ) )
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class B5F_WP_Table extends WP_List_Table
{
    private $order;
    private $orderby;
    private $posts_per_page = 20;

    public function __construct()
    {
        parent :: __construct( array(
            'singular' => 'table example',
            'plural'   => 'table examples',
            'ajax'     => true
        ) );
        $this->set_order();
        $this->set_orderby();
        $this->prepare_items();
        $this->display();
    }

    private function get_sql_results()
    {
        global $wpdb;
        //$args = array( 'ID', 'post_title', 'post_date', 'post_content', 'post_type' );
        $args = array( "`id`", "`name`", "`usage`", "`count`","`status`" );
        $sql_select = implode( ', ', $args );
		$prefix=$wpdb->prefix."pieregister_";
		$codetable=$prefix."code";
        $sql_results = $wpdb->get_results("
                SELECT $sql_select,'action' as `action`
                FROM `$codetable`
                ORDER BY `$this->orderby` $this->order "
        );
        return $sql_results;
    }

    public function set_order()
    {
        $order = 'DESC';
        if ( isset( $_GET['order'] ) AND $_GET['order'] )
            $order = $_GET['order'];
        $this->order = esc_sql( $order );
    }

    public function set_orderby()
    {
        $orderby = 'usage';
        if ( isset( $_GET['orderby'] ) AND $_GET['orderby'] )
            $orderby = $_GET['orderby'];
        $this->orderby = esc_sql( $orderby );
    }

    /**
     * @see WP_List_Table::ajax_user_can()
     */
    public function ajax_user_can() 
    {
        return current_user_can( 'edit_posts' );
    }

    /**
     * @see WP_List_Table::no_items()
     */
    public function no_items() 
    {
        _e( 'No posts found.' );
    }

    /**
     * @see WP_List_Table::get_views()
     */
    public function get_views()
    {
        return array();
    } 

    /**
     * @see WP_List_Table::get_columns()
     */
    public function get_columns()
    {
        $columns = array(
            'id'         => __( '#' ),
            'name' => __( 'Code Name' ),
            'usage'  => __( 'Usage' ),
            'count'  => __( 'Used' ),
            'action'  => __( 'Action' )
        );
        return $columns;        
    }

    /**
     * @see WP_List_Table::get_sortable_columns()
     */
    public function get_sortable_columns()
    {
        $sortable = array(
            'id'         => array( 'id', true ),
            'name' => array( 'name', true ),
            'usage'  => array( 'usage', true ),
            'count'  => __( 'used' )
        );
        return $sortable;
    }

    /**
     * Prepare data for display
     * @see WP_List_Table::prepare_items()
     */
    public function prepare_items()
    {
        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( 
            $columns,
            $hidden,
            $sortable 
        );

        // SQL results
        $posts = $this->get_sql_results();
        empty( $posts ) AND $posts = array();
        # >>>> Pagination
        $per_page     = $this->posts_per_page;
        $current_page = $this->get_pagenum();
        $total_items  = count( $posts );
        $this->set_pagination_args( array (
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total_items / $per_page )
        ) );
        $last_post = $current_page * $per_page;
        $first_post = $last_post - $per_page + 1;
        $last_post > $total_items AND $last_post = $total_items;

        // Setup the range of keys/indizes that contain 
        // the posts on the currently displayed page(d).
        // Flip keys with values as the range outputs the range in the values.
        $range = array_flip( range( $first_post - 1, $last_post - 1, 1 ) );
        // Filter out the posts we're not displaying on the current page.
        $posts_array = array_intersect_key( $posts, $range );
        # <<<< Pagination

        // Prepare the data
        $permalink = __( 'Edit:' );
		
/*
[id] => 69
[name] => jhgjkiutyu
[usage] => 5
[count] => 0
[action] => action
*/
        foreach ( $posts_array as $key => $post )
        {
            $link     = "#";
            $no_title = __( 'No title set' );
            $title    = ! $post->name ? "<em>{$no_title}</em>" : $post->name;
            //$posts[ $key ]->name = "<a title='{$permalink} {$title}' href='{$link}'>{$title}</a>";
			$class = ($post->status==1) ? "active"  : "inactive";
			$title = ($class == "active")? "Active Code" : "Unactive Code";
			$posts[ $key ]->action = '<a onclick="changeStatus('.$post->id.');" href="javascript:;" title="'.$title.'" class="'.$class.'"></a> <a class="delete" href="javascript:;" onclick="confirmDel('.$post->id.');" title="Delete"></a>';
        }
        $this->items = $posts_array;
		
    }

    /**
     * A single column
     */
    public function column_default( $item, $column_name )
    {
        return $item->$column_name;
    }

    /**
     * Override of table nav to avoid breaking with bulk actions & according nonce field
     */
    public function display_tablenav( $which ) {
        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">
            <!-- 
            <div class="alignleft actions">
                <?php # $this->bulk_actions( $which ); ?>
            </div>
             -->
            <?php
            $this->extra_tablenav( $which );
            $this->pagination( $which );
            ?>
            <br class="clear" />
        </div>
        <?php
    }

    /**
     * Disables the views for 'side' context as there's not enough free space in the UI
     * Only displays them on screen/browser refresh. Else we'd have to do this via an AJAX DB update.
     * 
     * @see WP_List_Table::extra_tablenav()
     */
    public function extra_tablenav( $which )
    {
        global $wp_meta_boxes;
        $views = $this->get_views();
        if ( empty( $views ) )
            return;

        $this->views();
    }
}