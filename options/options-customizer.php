<?php
/**
 * Title: Options customizer
 *
 * Description: Defines option fields for theme customizer.
 *
 * Please do not edit this file. This file is part of the Cyber Chimps Framework and all modifications
 * should be made in a child theme.
 *
 * @category Cyber Chimps Framework
 * @package  Framework
 * @since    1.0
 * @author   CyberChimps
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     http://www.cyberchimps.com/
 */

// create the admin menu for the theme options page
add_action('admin_menu', 'cyberchimps_admin_add_customizer_page');
function cyberchimps_admin_add_customizer_page() {
	// add the Customize link to the admin menu
	add_theme_page( __('Customize', 'cyberchimps'), __('Customize', 'cyberchimps'), 'edit_theme_options', 'customize.php' );
}

add_action('customize_register', 'cyberchimps_customize');
function cyberchimps_customize( $wp_customize ) {
	
	//set up defaults if they don't exist. Useful if theme is set up through live preview
	$option_defaults = cyberchimps_get_default_values();
	if( ! get_option( 'cyberchimps_options' ) ) {
		update_option( 'cyberchimps_options', $option_defaults );
	}
	
	class Cyberchimps_Typography_Size extends WP_Customize_Control {
		public $type = 'select';

    public function render_content() {?>
    	<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<select <?php $this->link(); ?>>
						<?php
						foreach ( $this->choices as $value => $label )
							echo '<option value="' . esc_attr( $label ) . 'px"' . selected( $this->value(), $value, false ) . '>' . $label . 'px</option>';
						?>
					</select>
				</label>
    <?php
		}
	}
	class Cyberchimps_Background_Image extends WP_Customize_Control {
		public $type = 'radio';

    public function render_content() {?>
    	<style>
      	.images-radio-subcontainer img {
					margin-top: 5px;
					padding: 2px;
          border: 5px solid #eee;
        }
        .images-radio-subcontainer img.of-radio-img-selected {
          border: 5px solid #5DA7F2;
        }
        .images-radio-subcontainer img:hover {
          cursor: pointer;
          border: 5px solid #5DA7F2;
        }
      </style>
      <script>jQuery( function($) {							
							$('.of-radio-img-img').click(function(){
							$(this).parent().parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
							$(this).addClass('of-radio-img-selected');
							});
			});
			</script>
        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <em><small><?php _e( 'make sure you have removed the image above before selecting one of these', 'cyberchimps' ); ?></small></em>
				<?php
				foreach( $this->choices as $value => $label ) : 
				//if get theme mod background image has a value then we need to set cyberchimps bg to none
				$test_bg = $this->value();
				$test_bg = ( get_theme_mod( 'background_image' ) ) ? 'none' : $test_bg;
				$name = '_customize-radio-' . $this->id;
				$selected = ( $test_bg == $value ) ? 'of-radio-img-selected' : '';
				?>
        <div class="images-radio-subcontainer">
        <label>
						<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $test_bg, $value ); ?> style="display:none;" />
						<img src="<?php echo esc_html( $label ); ?>" class="of-radio-img-img <?php echo esc_attr( $selected ); ?>" /><br>
				</label> 
        </div>
        <?php
				endforeach;
    }

		
	}

	$wp_customize->add_section( 'cyberchimps_design_section', array(
		'title'          => 'Design',
		'priority'       => 35,
	) );

// website width
	$wp_customize->add_setting( 'cyberchimps_options[max_width]', array(
		'default'        => 1020,
		'type'           => 'option',
	) );

	$wp_customize->add_control( 'max_width', array(
		'label'   => __( 'Max Width', 'cyberchimps' ),
		'section' => 'cyberchimps_design_section',
		'type'    => 'text',
		'settings'   => 'cyberchimps_options[max_width]',
	) );
	
// theme skin
	$wp_customize->add_setting( 'cyberchimps_options[cyberchimps_skin_color]', array(
			'default'        => 'default',
			'type'           => 'option',
		) );
	
	$wp_customize->add_control( 'skin_color', array(
		'label'   => __( 'Skin Color', 'cyberchimps' ),
    'section' => 'cyberchimps_design_section',
    'type'    => 'select',
		'settings'   => 'cyberchimps_options[cyberchimps_skin_color]',
    'choices'    => apply_filters( 'cyberchimps_skin_color', '' )
	) );
	
// text color
	$wp_customize->add_setting( 'cyberchimps_options[text_colorpicker]', array(
			'default'        => '',
			'type'           => 'option',
		) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_colorpicker', array(
    'label'   => __( 'Text Color', 'cyberchimps' ),
    'section' => 'cyberchimps_design_section',
    'settings'   => 'cyberchimps_options[text_colorpicker]',
	) ) );
	
// link color
	$wp_customize->add_setting( 'cyberchimps_options[link_colorpicker]', array(
			'default'        => '',
			'type'           => 'option',
		) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_colorpicker', array(
    'label'   => __( 'Link Color', 'cyberchimps' ),
    'section' => 'cyberchimps_design_section',
    'settings'   => 'cyberchimps_options[link_colorpicker]',
	) ) );
	
// link hover color
	$wp_customize->add_setting( 'cyberchimps_options[link_hover_colorpicker]', array(
			'default'        => '',
			'type'           => 'option',
		) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_hover_colorpicker', array(
    'label'   => __( 'Link Hover Color', 'cyberchimps' ),
    'section' => 'cyberchimps_design_section',
    'settings'   => 'cyberchimps_options[link_hover_colorpicker]',
	) ) );
	
// new typography section
	$wp_customize->add_section( 'cyberchimps_typography_section', array(
		'title'          => 'Typography',
		'priority'       => 40,
	) );
	
	// typography sizes
	$wp_customize->add_setting( 'cyberchimps_options[typography_options][size]', array(
			'default'        => '14px',
			'type'           => 'option',
		) );
	
	$wp_customize->add_control( new Cyberchimps_Typography_Size( $wp_customize, 'typography_size', array(
		'label'   => __( 'Typography Size', 'cyberchimps' ),
    'section' => 'cyberchimps_typography_section',
    'type'    => 'select',
		'settings'   => 'cyberchimps_options[typography_options][size]',
    'choices'    => apply_filters( 'cyberchimps_typography_sizes', '' )
  ) ) );
	
 	// typography face
	$wp_customize->add_setting( 'cyberchimps_options[typography_options][face]', array(
			'default'        => 'Arial',
			'type'           => 'option',
		) );
	
	$wp_customize->add_control( 'typography_face', array(
		'label'   => __( 'Typography Face', 'cyberchimps' ),
    'section' => 'cyberchimps_typography_section',
    'type'    => 'select',
		'settings'   => 'cyberchimps_options[typography_options][face]',
    'choices'    => apply_filters( 'cyberchimps_typography_faces', '' )
  ) );
	// typography style
	$wp_customize->add_setting( 'cyberchimps_options[typography_options][style]', array(
			'default'        => 'normal',
			'type'           => 'option',
		) );
	
	$wp_customize->add_control( 'typography_style', array(
		'label'   => __( 'Typography Style', 'cyberchimps' ),
    'section' => 'cyberchimps_typography_section',
    'type'    => 'select',
		'settings'   => 'cyberchimps_options[typography_options][style]',
    'choices'    => apply_filters( 'cyberchimps_typography_styles', '' )
  ) );
	
	// background image
	$wp_customize->add_setting( 'cyberchimps_background', array(
			'default'        => 'none',
			'type'           => 'theme_mod',
		) );
	
	$wp_customize->add_control( new Cyberchimps_Background_Image( $wp_customize, 'cyberchimps_background', array(
    'label'   => 'CyberChimps '. __( 'Background Image', 'cyberchimps' ),
    'section' => 'background_image',
    'settings'   => 'cyberchimps_background',
		'choices' => apply_filters( 'cyberchimps_background_image', '' ),
	) ) );
}