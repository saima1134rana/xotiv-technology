<div class="rpc-wrap">
	<nav class="rpc-tabs">
		<div class="selector"></div>
		<?php foreach ($tabs as $tab) { ?>
    			<a href="#rpc-tab-<?php echo esc_attr( $tab['name'] ); ?>"> <span class="dashicons <?php echo esc_attr( $tab['icon'] ); ?>"></span> <?php echo esc_html( $tab['label'] ); ?></a>
		<?php } ?>
	</nav>
	<div class="rpc-contents">
		<?php foreach ($tabs as $tab) { ?>
			<div class="rpc-tabcontent" id="rpc-tab-<?php echo esc_attr( $tab['name'] ); ?>">
				<table class="rpc-list-table widefat fixed">
					<?php foreach ($fields as $field) {
						if ($field['tab'] == $tab['name']) { ?>
							<tr id="wrap_<?php echo (is_array($field['key'])) ? $field['key'][0].'_'.esc_attr( $field['key'][1] ) : esc_attr( $field['key'] ) ; ?>">
								<td><label><?php echo esc_html( $field['title'] ); ?></label></td>
								<td class="td_<?php echo (is_array($field['key'])) ? $field['key'][0].'_'.esc_attr( $field['key'][1] ) : esc_attr( $field['key'] ) ; ?>">
									<?php $this->render_input_field($field, $carousel_meta); ?>
									<p class="description">
										<?php
											$data_filters = array(
												'code' => array(),
												'b' => array(),
												'a' => array(
											        'href' => array(),
											        'target' => array(),
											        'title' => array()
											    ),
											);
											echo wp_kses( $field['help'], $data_filters ); 
										?>
									</p>
								</td>
							</tr>
						<?php }
					} ?>		
				</table>
			</div>
		<?php } ?>
	</div>
</div>