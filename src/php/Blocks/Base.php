<?php
/**
 * Base block functionality.
 *
 * @package LetMeHelp
 */
namespace LetMeHelp\Blocks;

class Base {

	/**
	 * The variable name of the query variable for receptionist.
	 *
	 * @var string
	 */
	const VARIABLE_NAME_RECEPTIONIST = 'lmh_receptionist';

	/**
	 * Registers actions and filters for the custom block.
	 */
	public function register() {
		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'query_vars', array( $this, 'query_vars' ) );
	}

	/**
	 * Registers the block using the metadata loaded from the `block.json` file.
	 * Behind the scenes, it registers also all assets so they can be enqueued
	 * through the block editor in the corresponding context.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	public function init() {
		register_block_type(
			LETMEHELP_PATH . '/build/blocks/base',
			array(
				'render_callback' => array( $this, 'render_block_base' ),
			)
		);
	}

	/**
	 * Adds a query variable for the custom block.
	 *
	 * @param array $query_vars Existing query variables.
	 * @return array Query variables with the custom variable added.
	 */
	public function query_vars( $query_vars ) {
		// Add plugin-specific query variable.
		$query_vars[] = self::VARIABLE_NAME_RECEPTIONIST;

		return $query_vars;
	}

	/**
	 * Render block output on front-end via PHP.
	 *
	 * @param array $attributes The block attributes.
	 * @param string $content The block content.
	 * @return string The rendered block output.
	 */
	public function render_block_base( $attributes, $content ) {
		$is_receptionist_verification = get_query_var( self::VARIABLE_NAME_RECEPTIONIST );

		if ( $is_receptionist_verification && 'checked' === $is_receptionist_verification ) {
			return $this->render_final_view( $content );
		}

		$wrapper_attributes = get_block_wrapper_attributes();

		return $this->render_initial_view( $wrapper_attributes, $attributes );
	}

	/**
	 * Renders the initial view of the search form.
	 *
	 * @param array $wrapper_attributes An array of attributes to apply to the wrapper element.
	 * @param array $attributes An array of attributes to configure the search form.
	 *
	 * @return string The HTML output for the initial view of the search form.
	 */
	private function render_initial_view( $wrapper_attributes, $attributes ) {
		// Intro section.
		$intro = $attributes['intro'];
		// Subject section.
		$subject = $attributes['subject'];
		// Result section.
		$result = $attributes['result'];
		// Nothing Found section.
		$nothing_found = $attributes['nothingFound'];
		// Destionation section.
		$destination = $attributes['destination'];

		$intro_view         = $this->get_intro_view( $intro );
		$form_view          = $this->get_form_view( $subject );
		$result_view        = $this->get_result_view( $result );
		$nothing_found_view = $this->get_nothing_found_view( $nothing_found );
		$action_view        = $this->get_action_view( $destination );

		return sprintf(
			'<div %1$s>%2$s%3$s%4$s%5$s%6$s</div>',
			$wrapper_attributes,
			$intro_view,
			$form_view,
			$result_view,
			$nothing_found_view,
			$action_view
		);
	}

	/**
	 * Renders the final view with the provided content wrapped inside a container div.
	 *
	 * @param string $content The content to be displayed in the final view.
	 *
	 * @return string The final view HTML markup.
	 */
	private function render_final_view( $content ) {
		return sprintf(
			'<div class="lmh-request-destination">%s</div>',
			$content
		);
	}

	/**
	 * Get the HTML form intro.
	 *
	 * @param array $intro An array containing the intro details.
	 *
	 * @return string The HTML string for the form view.
	 */
	private function get_intro_view( $intro ) {
		$text = '';

		if ( ! is_array( $intro ) || ! isset( $intro['text'] ) || '' === $intro['text'] ) {
			return $text;
		}

		$text = $intro['text'];

		return sprintf(
			'<div class="request-intro">%s</div>',
			$text
		);
	}

	/**
	 * Get the HTML form view.
	 *
	 * @param array $subject An array containing the subject details, including input label, placeholder text, and button text.
	 *
	 * @return string The HTML string for the form view.
	 */
	private function get_form_view( $subject ) {
		$screen_reader_class = $subject['input']['label']['isVisible'] ? '' : 'screen-reader-text';
		$label_class         = $screen_reader_class ? sprintf( 'class="%s"', $screen_reader_class ) : '';
		$subject_input_label = sprintf(
			'<label for="request-input" %1$s>%2$s</label>',
			$label_class,
			$subject['input']['label']['text']
		);

		$description = '';
		if ( isset( $subject['input']['description']['text'] ) && '' !== $subject['input']['description']['text'] ) {
			$description = sprintf(
				'<div class="has-small-font-size">%s</div>',
				esc_html( $subject['input']['description']['text'] )
			);
		}

		$button_settings = array(
			'id'    => 'request-submit',
			'text'  => $subject['button']['text'],
			'color' => array(),
			'type'  => 'button'
		);

		// Set custom background color if needed.
		if ( isset( $subject['button']['color']['background'] ) ) {
			$button_settings['color']['background'] = $subject['button']['color']['background'];
		}

		// Set custom text color if needed.
		if ( isset( $subject['button']['color']['text'] ) ) {
			$button_settings['color']['text'] = $subject['button']['color']['text'];
		}

		$subject_button = $this->get_button( $button_settings );

		return sprintf(
			'<form id="request-form" class="lmh-request-form" enctype="multipart/form-data">
                %1$s
                <div class="form-wrapper">
					<input name="website" id="request-website" class="input-text" type="text" value="" />
                    <input name="keyword_text" id="request-input" class="input-text" type="text" placeholder="%2$s" value="" required />
                    %3$s
                </div>
				%5$s
                <input type="hidden" id="post-id" name="post_id" value="%4$s" />
            </form>',
			$subject_input_label,
			$subject['input']['placeholderText'],
			$subject_button,
			esc_attr( get_the_ID() ),
			$description
		);
	}

	/**
	 * Get the result view HTML for a given result.
	 *
	 * @param array $result An array containing the result text.
	 *
	 * @return string The HTML string for the result view.
	 */
	private function get_result_view( $result ) {
		return sprintf(
			'<div id="request-results" class="hidden request-results">
                <p>%s</p>
            </div>',
			$result['text']
		);
	}

	/**
	 * Get the "nothing found" view HTML for a given message.
	 *
	 * @param array $nothing_found An array containing the "nothing found" message.
	 *
	 * @return string The HTML string for the "nothing found" view.
	 */
	private function get_nothing_found_view( $nothing_found ) {
		return sprintf(
			'<div id="request-404" class="hidden">
                <span>%s</span>
            </div>',
			$nothing_found['text']
		);
	}

	/**
	 * Get the action view HTML for a given destination.
	 *
	 * @param array $destination An array containing the destination details, including the button text.
	 *
	 * @return string The HTML string for the action view.
	 */
	private function get_action_view( $destination ) {
		$button_settings = array(
			'id'    => 'request-next',
			'text'  => $destination['button']['text'],
			'color' => array(),
			'style' => 'outline',
			'type'  => 'link',
		);

		// Set custom background color if needed.
		if ( isset( $destination['button']['color']['background'] ) ) {
			$button_settings['color']['background'] = $destination['button']['color']['background'];
		}

		// Set custom text color if needed.
		if ( isset( $destination['button']['color']['text'] ) ) {
			$button_settings['color']['text'] = $destination['button']['color']['text'];
		}

		$button_markup = $this->get_button( $button_settings );

		return sprintf(
			'<div id="request-action" class="hidden">%s</div>',
			$button_markup
		);
	}

	/**
	 * Get the markup for a button with the given settings.
	 *
	 * @param array $settings An array of button settings, including 'id', 'text', and 'color'.
	 * @return string|bool The button markup or false if the settings are empty.
	 */
	private function get_button( $settings = array() ) {
		// Return false if settings are empty.
		if ( empty( $settings ) ) {
			return false;
		}

		// Create a new block editor context and get the editor settings.
		$editor_context  = new \WP_Block_Editor_Context();
		$editor_settings = get_block_editor_settings( array(), $editor_context );

		// Initialize classes and styles arrays.
		$main_classes  = array( 'wp-block-button' );
		$inner_classes = array( 'wp-block-button__link', 'wp-element-button' );
		$styles        = array();
		$attrs         = array();

		if ( isset( $settings['style'] ) && 'outline' === $settings['style'] ) {
			$main_classes[]     = 'is-style-outline';
			$attrs['className'] = 'is-style-outline';
		}

		// Set text color if needed.
		if ( isset( $settings['color']['text'] ) ) {
			$text_color_object = $this->get_color_object_by_color_value(
				$editor_settings['colors'],
				$settings['color']['text']
			);

			if ( isset( $text_color_object['slug'] ) && '' !== $text_color_object['slug'] ) {
				 // Get the text color class based on the color slug.
				$text_color_class = $this->get_color_class_name( 'color', $text_color_object['slug'] );

				if ( '' !== $text_color_class ) {
					// Add text color class and 'has-text-color' class.
					$inner_classes[]    = $text_color_class;
					$inner_classes[]    = 'has-text-color';
					$attrs['textColor'] = $text_color_object['slug'];
				}
			} else {
				if ( '' !== $settings['color']['text'] ) {
					// Add inline style for text color and 'has-text-color' class.
					$styles[]                        = 'color:' . $settings['color']['text'] . ';';
					$inner_classes[]                 = 'has-text-color';
					$attrs['style']['color']['text'] = $settings['color']['text'];
				}
			}
		}

		// Set background color if needed.
		if ( isset( $settings['color']['background'] ) ) {
			$background_color_object = $this->get_color_object_by_color_value(
				$editor_settings['colors'],
				$settings['color']['background']
			);

			if ( isset( $background_color_object['slug'] ) && '' !== $background_color_object['slug'] ) {
				// Get the background color class based on the color slug.
				$background_color_class = $this->get_color_class_name( 'background-color', $background_color_object['slug'] );

				if ( '' !== $background_color_class ) {
					// Add background color class and 'has-background' class.
					$inner_classes[]          = $background_color_class;
					$inner_classes[]          = 'has-background';
					$attrs['backgroundColor'] = $background_color_object['slug'];
				}
			} else {
				if ( '' !== $settings['color']['background'] ) {
					// Add inline style for background color and 'has-background' class.
					$styles[]                              = 'background-color:' . $settings['color']['background'] . ';';
					$inner_classes[]                       = 'has-background';
					$attrs['style']['color']['background'] = $settings['color']['background'];
				}
			}
		}

		// Set the HTML tag for the action based on the 'type' setting.
		$html_tag = 'link' === $settings['type'] ? 'a' : 'button';

		// Prepare the button markup in the WordPress block format.
		$button_wp_markup = sprintf(
			'<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button %1$s -->
				<div class="%2$s">
					<%3$s id="%4$s" class="%5$s" style="%6$s">%7$s</%3$s>
				</div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->',
			json_encode($attrs),
			implode( ' ', $main_classes ),
			$html_tag,
			esc_attr( $settings['id'] ),
			implode( ' ', $inner_classes ),
			implode( ' ', $styles ),
			esc_html( $settings['text'] )
		);

		// Return the rendered button markup using do_blocks().
		return do_blocks( $button_wp_markup );
	}

	/**
	 * Provided an array of color objects as set by the theme or by the editor defaults, and a color value returns the color object matching that value or undefined.
	 *
	 * @param {Array}   colors     Array of color objects as set by the theme or by the editor defaults.
	 * @param {?string} colorValue A string containing the color value.
	 *
	 * @return {?Object} Color object included in the colors array whose color property equals colorValue.
	 *                   Returns undefined if no color object matches this requirement.
	 */
	private function get_color_object_by_color_value( $colors, $color_value ) {
		if ( is_array( $colors ) ) {
			foreach ($colors as $color) {
				if ( isset( $color['color'] ) && $color['color'] === $color_value ) {
					return $color;
				}
			}
		}

		return null;
	}

	/**
	 * Returns a class based on the context a color is being used and its slug.
	 *
	 * @param string|null $context_name Context/place where color is being used e.g: background, text etc...
	 * @param string|null $slug       Slug of the color.
	 *
	 * @return string|null String with the class corresponding to the color in the provided context.
	 *                     Returns null if either $context_name or $slug are not provided.
	 */
	private function get_color_class_name( $context_name, $slug ) {
		if ( ! $context_name || ! $slug ) {
			return null;
		}

		$slug = strtolower( preg_replace( '/([a-z])([A-Z])/', '$1-$2', $slug ) );

		return 'has-' . $slug . '-' . $context_name;
	}
}
