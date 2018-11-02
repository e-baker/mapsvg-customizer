<?php

if ( ! defined('ABSPATH') ) {
    /** Set up WordPress environment */
    require_once( '../../../../wp-load.php' ); 
    // ^ That is a terrible way to do this, but I couldn't think of a better way
    require_once( 'lib/class-mapsvg-customizer-article.php');
    require_once( 'lib/class-mapsvg-customizer-finder.php' );
} 

class MapSVG_Customizer_Data {

    /**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
     * @param   string $tax The taxonomy used to search for data.
	 */
    public function __construct ( $tax = null ) {
        $this->taxonomy = $tax;
        $this->export_data();
    }

    /**
     * export_data function
     * @access public
     * @since 1.0.0
     * @return string JSON
     */
    public function export_data () {
        $finder = new MapSVG_Customizer_Finder( $this->taxonomy );
        $posts = $finder->find_posts();
        
        if ( count($posts) > 0 ) {
            $results = Array();
            foreach( $posts as $post ) {
                $county = wp_get_post_terms( $post->ID, 'county' );
                $county_slug = $county[0]->slug;

                $result = new MapSVG_Customizer_Article;
                $result->title = $post->post_title;
                $result->link = get_permalink( $post->ID );
                $result->county = $county_slug;
                array_push( $results, $result );
            }
        } else {
            $results = "No data found.";
        }
        $encoded = json_encode($results);
        
        //header('Content-Type: application/json');
        //echo $encoded;

        return $output;
    }

}

