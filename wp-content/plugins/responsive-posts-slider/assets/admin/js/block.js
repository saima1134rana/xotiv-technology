/**
 * Posts Carousel block for Gutenberg 
 *
 */
( function( blocks, i18n, element ) {
	var el = element.createElement;
	var __ = i18n.__;
	var Editable = blocks.Editable;
	var AlignmentToolbar = wp.blocks.AlignmentToolbar;
	var BlockControls = wp.blocks.BlockControls;
	var InspectorControls = wp.blocks.InspectorControls;
	var TextControl = wp.components.TextControl;
	var ServerSideRender = wp.components.ServerSideRender;
	var RangeControl = wp.components.RangeControl;
	var PanelColorSettings = wp.editor.PanelColorSettings;
	var ColorPalette = wp.components.ColorPalette;
	var SelectControl = wp.components.SelectControl;
	var TextareaControl = wp.components.TextareaControl;
	var RichText = wp.editor.RichText;

	/**
	 * Agent Login Block
	 * @param  heading, redirect
	 * @return {null}       Rendered through PHP
	 */
	blocks.registerBlockType( 'responsive-posts-carousel/carousel', {
		title: __( 'Responsive Posts Carousel' ),
		icon: 'editor-insertmore',
		category: 'common',
	    keywords: [
            __('slider'),
            __('posts'),
            __('gallery')
	    ],
		attributes: {
			id: {
				type: 'string',
				default: ''
			},
		},	    
	    edit: function(props) {
	        return [!!props.isSelected && el(
	                wp.editor.InspectorControls, {
	                    key: 'inspector'
	                },
					el(
	                    SelectControl, {
	                        options: rpc_carousel_list,
	                        label: __('Choose Carousel'),
	                        value: props.attributes.id,
	                        onChange: function(value) {
	                            props.setAttributes({
	                                id: value,
	                            });
	                        },
	                    }
                    ),
	            ),
		        el(ServerSideRender, {
		        	key: "editable",
		        	block: "responsive-posts-carousel/carousel",
		        	attributes:  props.attributes
		        })
	        ];
	    },
		save: function(props) {
	        return null;
		},
	});

} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element
);