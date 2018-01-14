<?php
if (!class_exists('WP_EX_PAGE_ON_THE_FLY')){
    
    class WP_EX_PAGE_ON_THE_FLY 
    {

        public $slug ='';
        public $args = array();
      
        function __construct($arg){
            add_filter('the_posts',array($this,'fly_page'));
            $this->args = $args;
            $this->slug = $args['slug'];
        }

        public function fly_page($posts){
            global $wp,$wp_query;
            $page_slug = $this->slug;

            if(count($posts) == 0 && (strtolower($wp->request) == $page_slug || $wp->query_vars['page_id'] == $page_slug)){

                $post = new stdClass;
                $post->post_author = 1;
                $post->post_name = $page_slug;
                $post->guid = get_bloginfo('wpurl' . '/' . $page_slug);
                $post->post_title = 'page title';
                $post->post_content = "Fake Content";
                $post->ID = -42;
                $post->post_status = 'static';
                $post->comment_status = 'closed';
                $post->ping_status = 'closed';
                $post->comment_count = 0;
                $post->post_date = current_time('mysql');
                $post->post_date_gmt = current_time('mysql',1);

                $post = (object) array_merge((array) $post, (array) $this->args);
                $posts = NULL;
                $posts[] = $post;

                $wp_query->is_page = true;
                $wp_query->is_singular = true;
                $wp_query->is_home = false;
                $wp_query->is_archive = false;
                $wp_query->is_category = false;
                unset($wp_query->query["error"]);
                $wp_query->query_vars["error"]="";
                $wp_query->is_404 = false;
            }

            return $posts;
        }
    }
}