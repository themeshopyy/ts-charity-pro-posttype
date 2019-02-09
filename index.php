<?php 
/*
 Plugin Name: TS Charity pro Post Types
 Plugin URI:https://www.themeshopy.com/
 Description: Creating new post type for TS Charity Pro Theme
 Author: Themeshopy
 Version: 1.1
 Author URI: https://www.themeshopy.com/
*/

define( 'TS_CHARITY_PRO_POSTTYPE_VERSION', '1.0' );

add_action( 'init', 'ts_charity_pro_posttype_create_post_type' );

function ts_charity_pro_posttype_create_post_type() {
  //upcoming events
	register_post_type( 'events',
    array(
        'labels' => array(
            'name' => __( 'Events','ts-charity-pro-posttype' ),
            'singular_name' => __( 'Events','ts-charity-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
	);
  //our causes
  register_post_type( 'causes',
    array(
        'labels' => array(
            'name' => __( 'Causes','ts-charity-pro-posttype' ),
            'singular_name' => __( 'Causes','ts-charity-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-welcome-learn-more',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
  //testimonils
  register_post_type( 'testimonials',
	array(
		'labels' => array(
			'name' => __( 'Testimonials','ts-charity-pro-posttype-pro' ),
			'singular_name' => __( 'Testimonials','ts-charity-pro-posttype-pro' )
			),
		'capability_type' => 'post',
		'menu_icon'  => 'dashicons-businessman',
		'public' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail'
			)
		)
	);
  //volunteers
  register_post_type( 'volunteer',
	array(
		'labels' => array(
			'name' => __( 'Volunteer','ts-charity-pro-posttype-pro' ),
			'singular_name' => __( 'Volunteer','ts-charity-pro-posttype-pro' )
			),
		'capability_type' => 'post',
		'menu_icon'  => 'dashicons-media-spreadsheet',
		'public' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail'
			)
		)
	);
   //Happy Donors
  register_post_type( 'happy_donors',
    array(
        'labels' => array(
            'name' => __( 'Happy Donors','ts-charity-pro-posttype' ),
            'singular_name' => __( 'Happy Donors','ts-charity-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
}
// Serives section
function ts_charity_pro_posttype_images_metabox_enqueue($hook) {
	if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
		wp_enqueue_script('ts-charity-pro-posttype-images-metabox', plugin_dir_url( __FILE__ ) . '/js/img-metabox.js', array('jquery', 'jquery-ui-sortable'));

		global $post;
		if ( $post ) {
			wp_enqueue_media( array(
					'post' => $post->ID,
				)
			);
		}

	}
}
add_action('admin_enqueue_scripts', 'ts_charity_pro_posttype_images_metabox_enqueue');

// Upcoming Events Meta
function ts_charity_pro_posttype_bn_custom_meta_event() {

    add_meta_box( 'bn_meta', __( 'Events Meta', 'ts-charity-pro-posttype' ), 'ts_charity_pro_posttype_bn_meta_callback_event', 'events', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
	add_action('admin_menu', 'ts_charity_pro_posttype_bn_custom_meta_event');
}

function ts_charity_pro_posttype_bn_meta_callback_event( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    ?>
	<div id="property_stuff">
		<table id="list-table">			
			<tbody id="the-list" data-wp-lists="list:meta">
				<tr id="meta-1">
					<p>
            <label for="meta-location"><?php echo esc_html('Location'); ?></label><br>
            <input type="text" name="meta-location" id="meta-location" class="meta-location regular-text" value="<?php echo  $bn_stored_meta['meta-location'][0]; ?>">
          </p>
				</tr>
        <tr id="meta-2">
          <p>
            <label for="meta-url"><?php echo esc_html('Want to link with custom URL'); ?></label><br>
            <input type="url" name="meta-url" id="meta-url" class="meta-url regular-text" value="<?php echo $bn_stored_meta['meta-url'][0]; ?>">
          </p>
        </tr>
			</tbody>
		</table>
	</div>
	<?php
}
function ts_charity_pro_posttype_bn_meta_save_event( $post_id ) {

	if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	// Save Image
	if( isset( $_POST[ 'meta-image' ] ) ) {
	    update_post_meta( $post_id, 'meta-image', esc_url_raw($_POST[ 'meta-image' ]) );
	}
  if( isset( $_POST[ 'meta-url' ] ) ) {
      update_post_meta( $post_id, 'meta-url', esc_url_raw($_POST[ 'meta-url' ]) );
  }
  if( isset( $_POST[ 'meta-location' ] ) ) {
      update_post_meta( $post_id, 'meta-location', esc_html($_POST[ 'meta-location' ]) );
  }
}
add_action( 'save_post', 'ts_charity_pro_posttype_bn_meta_save_event' );

/* causes */
function ts_charity_pro_posttype_bn_designation_meta() {
    add_meta_box( 'ts_charity_pro_posttype_bn_meta', __( 'Enter Meta Setting','ts-charity-pro-posttype' ), 'ts_charity_pro_posttype_bn_meta_callback', 'causes', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'ts_charity_pro_posttype_bn_designation_meta');
}
/* Adds a meta box for custom post */
function ts_charity_pro_posttype_bn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ts_charity_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    ?>
    <div id="causes_custom_stuff">
        <table id="list-table">         
            <tbody id="the-list" data-wp-lists="list:meta">
                <tr id="meta-1">
                    <td class="left">
                        <?php esc_html_e( 'Goal', 'ts-charity-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-goal" id="meta-goal" value="<?php echo esc_html($bn_stored_meta['meta-goal'][0]); ?>" />
                    </td>
                </tr>
                <tr id="meta-2">
                    <td class="left">
                        <?php esc_html_e( 'Collected', 'ts-charity-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-collect" id="meta-collect" value="<?php echo esc_html($bn_stored_meta['meta-collect'][0]); ?>" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom fields meta input */
function ts_charity_pro_posttype_bn_metadesig_causes_save( $post_id ) {
    if( isset( $_POST[ 'meta-goal' ] ) ) {
        update_post_meta( $post_id, 'meta-goal', sanitize_text_field($_POST[ 'meta-goal' ]) );
    }
    if( isset( $_POST[ 'meta-collect' ] ) ) {
        update_post_meta( $post_id, 'meta-collect', sanitize_text_field($_POST[ 'meta-collect' ]) );
    } 
}
add_action( 'save_post', 'ts_charity_pro_posttype_bn_metadesig_causes_save' );

/* causes shorthcode */
function ts_charity_pro_posttype_causes_func( $atts ) {
    $causes = ''; 
    $custom_url ='';
    $causes = '<div class="row">';
    $query = new WP_Query( array( 'post_type' => 'causes' ) );
    if ( $query->have_posts() ) :
    $k=1;
    $new = new WP_Query('post_type=causes'); 
    while ($new->have_posts()) : $new->the_post();
    	$post_id = get_the_ID();
    	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
      if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
		  $url = $thumb['0'];
      $excerpt = ts_charity_pro_string_limit_words(get_the_excerpt(),20);
      $goal= get_post_meta($post_id,'meta-goal',true);
      $goal_title = esc_html('Goal: ');
      $Collected_title = esc_html('Collected: ');
      $collect= get_post_meta($post_id,'meta-collect',true);
      $call= get_post_meta($post_id,'meta-call',true);
      if(get_post_meta($post_id,'meta-causes-url',true !='')){$custom_url =get_post_meta($post_id,'meta-causes-url',true); } else{ $custom_url = get_permalink(); }
      if(get_post_meta(get_the_ID(), 'meta-causes-url', true !='')){  $custom_url = get_post_meta(get_the_ID(), 'meta-causes-url', true); } else {
        $custom_url = get_the_permalink();
      }
      $causes .= '<div class="causes_box col-lg-4 col-md-4 col-sm-6 pb-4">
                    <div class="image-box ">
                      <img src="'.esc_url($url).'">
                      <div class="causes-box w-100 float-left">
                        <h4 class="causes_name"><a href="'.esc_url($custom_url).'">'.get_the_title().'</a></h4>
                      </div>
                    </div>
                    <div class="content_box w-100 float-left">
                      <div class="short_text pt-3">'.esc_html($excerpt).'</div>
                      <div class="about-socialbox pt-3">';
                        if($goal != ''){
                         $causes .=  '<span class="goal">'.esc_html($goal_title . $goal).'</span>';
                        } if($collect != ''){
                        $causes .= '<span class="collected" >'.esc_html($Collected_title . $collect).'</span>';
                        }
                      $causes .= '</div>
                    </div>
                  </div>';
      if($k%2 == 0){
          $causes.= '<div class="clearfix"></div>'; 
      } 
      $k++;         
  endwhile; 
  wp_reset_postdata();
  $causes.= '</div>';
  else :
    $causes = '<h2 class="center">'.esc_html_e('Not Found','ts-charity-pro-posttype').'</h2>';
  endif;
  return $causes;
}
add_shortcode( 'causes', 'ts_charity_pro_posttype_causes_func' );

/* Testimonial section */
/* Adds a meta box to the Testimonial editing screen */
function ts_charity_pro_posttype_bn_testimonial_meta_box() {
	add_meta_box( 'ts-charity-pro-posttype-pro-testimonial-meta', __( 'Enter Designation', 'ts-charity-pro-posttype-pro' ), 'ts_charity_pro_posttype_bn_testimonial_meta_callback', 'testimonials', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'ts_charity_pro_posttype_bn_testimonial_meta_box');
}

/* Adds a meta box for custom post */
function ts_charity_pro_posttype_bn_testimonial_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'ts_charity_pro_posttype_posttype_testimonial_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
	$desigstory = get_post_meta( $post->ID, 'ts_charity_pro_posttype_posttype_testimonial_desigstory', true );
	?>
	<div id="testimonials_custom_stuff">
		<table id="list">
			<tbody id="the-list" data-wp-lists="list:meta">
				<tr id="meta-1">
					<td class="left">
						<?php esc_html_e( 'Designation', 'ts-charity-pro-posttype-pro' )?>
					</td>
					<td class="left" >
						<input type="text" name="ts_charity_pro_posttype_posttype_testimonial_desigstory" id="ts_charity_pro_posttype_posttype_testimonial_desigstory" value="<?php echo esc_attr( $desigstory ); ?>" />
					</td>
				</tr>
        <tr id="meta-2">
          <td class="left">
            <?php esc_html_e( 'Want to link with custom URL', 'ts-charity-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-testimonial-url" id="meta-testimonial-url" class="meta-testimonial-url regular-text" value="<?php echo $bn_stored_meta['meta-testimonial-url'][0]; ?>">
          </td>
        </tr>
			</tbody>
		</table>
	</div>
	<?php
}

/* Saves the custom meta input */
function ts_charity_pro_posttype_bn_metadesig_save( $post_id ) {
	if (!isset($_POST['ts_charity_pro_posttype_posttype_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['ts_charity_pro_posttype_posttype_testimonial_meta_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	// Save desig.
	if( isset( $_POST[ 'ts_charity_pro_posttype_posttype_testimonial_desigstory' ] ) ) {
		update_post_meta( $post_id, 'ts_charity_pro_posttype_posttype_testimonial_desigstory', sanitize_text_field($_POST[ 'ts_charity_pro_posttype_posttype_testimonial_desigstory']) );
	}
  if( isset( $_POST[ 'meta-testimonial-url' ] ) ) {
    update_post_meta( $post_id, 'meta-testimonial-url', esc_url($_POST[ 'meta-testimonial-url']) );
  }
}
add_action( 'save_post', 'ts_charity_pro_posttype_bn_metadesig_save' );

/* Testimonials shortcode */
function ts_charity_pro_posttype_testimonial_func( $atts ) {
	$testimonial = '';
	$testimonial = '<div class="row">';
	$query = new WP_Query( array( 'post_type' => 'testimonials') );

    if ( $query->have_posts() ) :

	$k=1;
	$new = new WP_Query('post_type=testimonials');

	while ($new->have_posts()) : $new->the_post();
        $custom_url = '';
      	$post_id = get_the_ID();
      	$excerpt = wp_trim_words(get_the_excerpt(),25);
      	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
		    if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
      	$desigstory= get_post_meta($post_id,'ts_charity_pro_posttype_posttype_testimonial_desigstory',true);
        if(get_post_meta($post_id,'meta-testimonial-url',true !='')){$custom_url =get_post_meta($post_id,'meta-testimonial-url',true); } else{ $custom_url = get_permalink(); }
        $testimonial .= '
                  <div class="col-lg-4 col-md-6 col-sm-6 w-100">
                    <div class="text-center image-box media">
                      <div class="image-box img-responsive center-block">
                        <img class="d-flex align-self-center" src="'.esc_url($thumb_url).'">
                      </div>
                    </div>  
                    <div class="content_box w-100">
                      <div class="short_text">'.$excerpt.'</div>
                    </div>
                    <div class="testimonial-box media-body">
                      <h4 class="testimonial_name mt-0"><a href="'.esc_url($custom_url).'">'.get_the_title().'</a></h4>
                    </div> 
                  </div>
                ';
		if($k%3 == 0){
			$testimonial.= '<div class="clearfix"></div>';
		}
      $k++;
  endwhile;
  else :
  	$testimonial = '<h2 class="center">'.esc_html__('Post Not Found','ts-charity-pro-posttype-pro').'</h2>';
  endif;
  $testimonial .= '</div>';
  return $testimonial;
}

add_shortcode( 'testimonials', 'ts_charity_pro_posttype_testimonial_func' );

/* events shortcode */
function ts_charity_pro_posttype_event_func( $atts ) {
  $event = '';
  $event = '<div class="row">';
  $query = new WP_Query( array( 'post_type' => 'events') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=events');

  while ($new->have_posts()) : $new->the_post();
        
        $custom_url ='';
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);

        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        
        $location= get_post_meta($post_id,'meta-location',true);
        $detail= get_post_meta($post_id,'meta-detail',true);
        if(get_post_meta($post_id,'meta-causes-url',true !='')){$custom_url =get_post_meta($post_id,'meta-causes-url',true); } else{ $custom_url = get_permalink(); }
        if(get_post_meta($post_id,'meta-event-url',true !='')){$custom_url =get_post_meta($post_id,'meta-event-url',true); } else{ $custom_url = get_permalink(); }
        $event .= '
            <div class="col-lg-6 col-md-12 col-sm-12">
              <div class="event-image">
                <div class="img">
                  <img src="'.esc_url($thumb_url).'">
                </div>
                <div class="textcontent-box">
                  <div class="textcontent">
                    <h4><a href="'.get_the_permalink().'">'.get_the_title().'</a></h4>
                    <div class="col-lg-12 col-md-12 p-0">
                      <p class="event-detail"><i class="fa fa-calendar pl-2 pr-2" aria-hidden="true"></i> '.esc_html(get_the_date()).'
                      
                        <i class="fas fa-clock pl-2 pr-2" aria-hidden="true"></i> 
                        '.esc_html(get_the_time()).'
                        
                        <i class="fa fa-map-marker pl-2 pr-2" aria-hidden="true"></i>
                        '.esc_html($location).'
                      </p>
                    </div>
                    <p>'.esc_html($excerpt).'</p>
                    <div class="row">
                      <div class="col-md-6">
                        <a class="continue-reading" href="'.get_the_permalink().'">'.esc_url($detail).'
                        </a>
                      </div>                            
                    </div>                        
                  </div>                                              
                </div>                    
              </div>
            </div>
          ';
    if($k%2 == 0){
      $event.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $event = '<h2 class="center">'.esc_html__('Post Not Found','ts-charity-pro-posttype-pro').'</h2>';
  endif;
  $event .= '</div>';
  return $event;
}

add_shortcode( 'list-event', 'ts_charity_pro_posttype_event_func' );

/* Volunteer shortcode */
 function ts_charity_pro_posttype_bn_volunteer_meta() {
    add_meta_box( 'ts_charity_pro_posttype_bn_meta', __( 'Enter Meta','ts-charity-pro-posttype' ), 'ts_charity_pro_posttype_bn_meta_callback_vol', 'volunteer', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'ts_charity_pro_posttype_bn_volunteer_meta');
}
/* Adds a meta box for custom post */
function ts_charity_pro_posttype_bn_meta_callback_vol( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ts_charity_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    ?>
    <div id="volunteer_custom_stuff">
        <table id="list-table">         
            <tbody id="the-list" data-wp-lists="list:meta">
                <tr id="meta-3">
                  <td class="left">
                    <?php esc_html_e( 'Facebook Url', 'ts-charity-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-facebookurl" id="meta-facebookurl" value="<?php echo esc_url($bn_stored_meta['meta-facebookurl'][0]); ?>" />
                  </td>
                </tr>
                <tr id="meta-4">
                  <td class="left">
                    <?php esc_html_e( 'Linkedin URL', 'ts-charity-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-linkdenurl" id="meta-linkdenurl" value="<?php echo esc_url($bn_stored_meta['meta-linkdenurl'][0]); ?>" />
                  </td>
                </tr>
                <tr id="meta-5">
                  <td class="left">
                    <?php esc_html_e( 'Twitter Url', 'ts-charity-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-twitterurl" id="meta-twitterurl" value="<?php echo esc_url( $bn_stored_meta['meta-twitterurl'][0]); ?>" />
                  </td>
                </tr>
                <tr id="meta-6">
                  <td class="left">
                    <?php esc_html_e( 'GooglePlus URL', 'ts-charity-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-googleplusurl" id="meta-googleplusurl" value="<?php echo esc_url($bn_stored_meta['meta-googleplusurl'][0]); ?>" />
                  </td>
                </tr>
                <tr id="meta-8">
                  <td class="left">
                    <?php esc_html_e( 'Want to link with custom URL', 'ts-charity-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-volunteer-url" id="meta-volunteer-url" class="meta-volunteer-url regular-text" value="<?php echo $bn_stored_meta['meta-volunteer-url'][0]; ?>">
                  </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom fields meta input */
function ts_charity_pro_posttype_bn_metadesig_volunteer_save( $post_id ) {
    if( isset( $_POST[ 'meta-desig' ] ) ) {
        update_post_meta( $post_id, 'meta-desig', sanitize_text_field($_POST[ 'meta-desig' ]) );
    }
    if( isset( $_POST[ 'meta-call' ] ) ) {
        update_post_meta( $post_id, 'meta-call', sanitize_text_field($_POST[ 'meta-call' ]) );
    }
    // Save facebookurl
    if( isset( $_POST[ 'meta-facebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-facebookurl', esc_url_raw($_POST[ 'meta-facebookurl' ]) );
    }
    // Save linkdenurl
    if( isset( $_POST[ 'meta-linkdenurl' ] ) ) {
        update_post_meta( $post_id, 'meta-linkdenurl', esc_url_raw($_POST[ 'meta-linkdenurl' ]) );
    }
    if( isset( $_POST[ 'meta-twitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-twitterurl', esc_url_raw($_POST[ 'meta-twitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-googleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-googleplusurl', esc_url_raw($_POST[ 'meta-googleplusurl' ]) );
    }
    
    if( isset( $_POST[ 'meta-volunteer-url' ] ) ) {
        update_post_meta( $post_id, 'meta-volunteer-url', esc_url_raw($_POST[ 'meta-volunteer-url' ]) );
    }
}
add_action( 'save_post', 'ts_charity_pro_posttype_bn_metadesig_volunteer_save' );

/* volunteer shorthcode */
function ts_charity_pro_posttype_volunteer_func( $atts ) {
    $volunteer = ''; 
    $custom_url ='';
    $volunteer = '<div class="row">';
    $query = new WP_Query( array( 'post_type' => 'volunteer' ) );
    if ( $query->have_posts() ) :
    $k=1;
    $new = new WP_Query('post_type=volunteer'); 
    while ($new->have_posts()) : $new->the_post();
      $post_id = get_the_ID();
      $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
      if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
      $url = $thumb['0'];
      $excerpt =ts_charity_pro_string_limit_words(get_the_excerpt(),20);
      $facebookurl= get_post_meta($post_id,'meta-facebookurl',true);
      $linkedin=get_post_meta($post_id,'meta-linkdenurl',true);
      $twitter=get_post_meta($post_id,'meta-twitterurl',true);
      $googleplus=get_post_meta($post_id,'meta-googleplusurl',true);
      if(get_post_meta($post_id,'meta-volunteer-url',true !='')){$custom_url =get_post_meta($post_id,'meta-volunteer-url',true); } else{ $custom_url = get_permalink(); }
      $volunteer .= '
                <div class="volunteers_box col-lg-4 col-md-6 col-sm-6">
                  <?php if (has_post_thumbnail()){ ?>
                    <div class="image-box ">
                      <img class="client-img" src="'.esc_url($thumb_url).'" alt="volunteer-thumbnail" />
                      <div class="volunteers-box w-100 float-left">
                        <h4 class="volunteer_name"><a href="'.esc_url($custom_url).'">'.get_the_title().'</a></h4>
                      </div>
                    </div>
                  <?php } ?>
                  <div class="content_box w-100 float-left">
                    <div class="short_text pt-3">'.$excerpt.'</div>
                    <div class="about-socialbox pt-3">
                      <div class="att_socialbox">';
                        if($facebookurl != ''){
                          $volunteer .= '<a class="ml-2" href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                        } if($twitter != ''){
                          $volunteer .= '<a class="ml-2" href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';
                        } if($googleplus != ''){
                          $volunteer .= '<a class="ml-2" href="'.esc_url($googleplus).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
                        } if($linkedin != ''){
                          $volunteer .= '<a class="ml-2" href="'.esc_url($linkedin).'" target="_blank"><i class="fab fa-linkedin-in"></i></a>';
                        }
                      $volunteer .= '</div>
                    </div>
                  </div>
                </div>';
      if($k%2 == 0){
          $volunteer.= '<div class="clearfix"></div>'; 
      } 
      $k++;         
  endwhile; 
  wp_reset_postdata();
  $volunteer.= '</div>';
  else :
    $volunteer = '<h2 class="center">'.esc_html_e('Not Found','ts-charity-pro-posttype').'</h2>';
  endif;
  return $volunteer;
}
add_shortcode( 'volunteer', 'ts_charity_pro_posttype_volunteer_func' );

/*happy donors shortcode*/
function ts_charity_pro_posttype_bn_happy_donors_meta() {
    add_meta_box( 'ts_charity_pro_posttype_bn_meta_donor', __( 'Enter Meta','ts-charity-pro-posttype' ), 'ts_charity_pro_posttype_bn_meta_callback_donor', 'donors', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'ts_charity_pro_posttype_bn_happy_donors_meta');
}
/* Adds a meta box for custom post */
function ts_charity_pro_posttype_bn_meta_callback_donor( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ts_charity_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    ?>
    <div id="happy_donors_custom_stuff">
        <table id="list-table">         
            <tbody id="the-list" data-wp-lists="list:meta">
                <tr id="meta-7">
                  <td class="left">
                    <?php esc_html_e( 'Profile', 'ts-charity-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="text" name="meta-profile" id="meta-profile" value="<?php echo esc_html($bn_stored_meta['meta-profile'][0]); ?>" />
                  </td>
                </tr>
                <tr id="meta-8">
                  <td class="left">
                    <?php esc_html_e( 'Want to link with custom URL', 'ts-charity-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-happy_donors-url" id="meta-happy_donors-url" class="meta-happy_donors-url regular-text" value="<?php echo $bn_stored_meta['meta-happy_donors-url'][0]; ?>">
                  </td>
                </tr>
            </tbody>
        </table>
    </div>
  <?php
}
/* Saves the custom fields meta input */
function ts_charity_pro_posttype_bn_metadesig_happy_donors_save( $post_id ) {
  
    // Save designation
    if( isset( $_POST[ 'meta-profile' ] ) ) {
        update_post_meta( $post_id, 'meta-profile', sanitize_text_field($_POST[ 'meta-profile' ]) );
    }
    if( isset( $_POST[ 'meta-happy_donors-url' ] ) ) {
        update_post_meta( $post_id, 'meta-happy_donors-url', esc_url_raw($_POST[ 'meta-happy_donors-url' ]) );
    }
}
add_action( 'save_post', 'ts_charity_pro_posttype_bn_metadesig_happy_donors_save' );

/* happy_donors shortcode */
function ts_charity_pro_posttype_happy_donors_func( $atts ) {
    $happy_donors = ''; 
    $custom_url ='';
    $happy_donors = '<div class="row">';
    $query = new WP_Query( array( 'post_type' => 'happy_donors' ) );
    if ( $query->have_posts() ) :
    $k=1;
    $new = new WP_Query('post_type=happy_donors'); 
    while ($new->have_posts()) : $new->the_post();
      $post_id = get_the_ID();
      $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
      if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
      $url = $thumb['0'];
      $excerpt =ts_charity_pro_string_limit_words(get_the_excerpt(),20);
      $profile= get_post_meta($post_id,'meta-profile',true);
      if(get_post_meta($post_id,'meta-happy_donors-url',true !='')){$custom_url =get_post_meta($post_id,'meta-happy_donors-url',true); } else{ $custom_url = get_permalink(); }
      $happy_donors .= '
                <div class="happy_donors_box col-lg-4 col-md-4 col-sm-4">
                  <?php if (has_post_thumbnail()){ ?>
                    <div class="image-box ">
                      <img class="client-img" src="'.esc_url($thumb_url).'" alt="happy_donors-thumbnail" />
                      <div class="happy_donors-box w-100 float-left">
                        <h4 class="happy_donors_name"><a href="'.esc_url($custom_url).'">'.get_the_title().'</a></h4>
                        <p>'.esc_html($profile).'</p>
                      </div>
                    </div>
                  <?php } ?>
                  <div class="content_box w-100 float-left">
                    <div class="short_text pt-3">'.$excerpt.'</div>
                  </div>
                </div>  ';
      if($k%2 == 0){
          $happy_donors.= '<div class="clearfix"></div>'; 
      } 
      $k++;         
  endwhile; 
  wp_reset_postdata();
  $happy_donors.= '</div>';
  else :
    $happy_donors = '<h2 class="center">'.esc_html_e('Not Found','ts-charity-pro-posttype').'</h2>';
  endif;
  return $happy_donors;
}
add_shortcode( 'happy_donors', 'ts_charity_pro_posttype_happy_donors_func' );