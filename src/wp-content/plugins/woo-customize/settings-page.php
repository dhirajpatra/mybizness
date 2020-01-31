<?php
function woo_customize_settings_page() {
 // check user capabilities
 if ( ! current_user_can( 'manage_options' ) ) {
 	die(esc_html('Sorry, you do not have access to this page.','woo-customize'));
 }

?>

<div class="wrap">
<h1><?php esc_html_e('Woocommerce Customize','woo-customize'); ?></h1>
         
<?php $active_tab = isset( $_GET[ 'tab' ] ) ? esc_html( wp_unslash($_GET[ 'tab' ])) : 'billing_options'; ?>

<h2 class="nav-tab-wrapper">
	<a href="?page=woo-customize/woo-customize.php&tab=billing_options" class="nav-tab <?php echo $active_tab == 'billing_options' ? 'nav-tab-active' : ''; ?>">Billing Options</a>
	<a href="?page=woo-customize/woo-customize.php&tab=free_options" class="nav-tab <?php echo $active_tab == 'free_options' ? 'nav-tab-active' : ''; ?>">Free Options</a>
	<a href="?page=woo-customize/woo-customize.php&tab=color_options" class="nav-tab <?php echo $active_tab == 'color_options' ? 'nav-tab-active' : ''; ?>">Colour</a>
</h2>

<?php if( $active_tab == 'billing_options' ): ?>
<h2><?php esc_html_e('Disable billing fields','woo-customize'); ?></h2>
<p><?php esc_html_e('You can disable unwanted billing fields by checking the relevent field.','woo-customize'); ?></p>
<?php endif; ?>

<?php if( $active_tab == 'free_options' ): ?>
<h2><?php esc_html_e('Disable fields for free products','woo-customize'); ?></h2>
<p><?php esc_html_e('You can disable all billing fields for free products by checking the relevent field.','woo-customize'); ?></p>
<?php endif; ?>

<?php if( $active_tab == 'color_options' ): ?>
<h2><?php esc_html_e('Change Colour Scheme','woo-customize'); ?></h2>
<?php endif; ?>

<form method="post" action="options.php">
    <?php if( $active_tab == 'billing_options' ) settings_fields( 'woo-customize-billing-group' ); ?>
    <?php if( $active_tab == 'billing_options' ) do_settings_sections( 'woo-customize-billing-group' ); ?>
    <?php if( $active_tab == 'free_options' ) settings_fields( 'woo-customize-free-group' ); ?>
    <?php if( $active_tab == 'free_options' ) do_settings_sections( 'woo-customize-free-group' ); ?>	
    <?php if( $active_tab == 'color_options' ) settings_fields( 'woo-customize-color-group' ); ?>
    <?php if( $active_tab == 'color_options' ) do_settings_sections( 'woo-customize-color-group' ); ?>		
    <table class="form-table">
	
		<?php if( $active_tab == 'billing_options' ): ?>
        <tr valign="top">        
        <td><input type="checkbox" name="billing_first_name" <?php if(get_option('billing_first_name')) echo ' checked'; ?> /><label><?php esc_html_e('First Name','woo-customize'); ?></label></td>		
        </tr>         
        <tr valign="top">        
        <td><input type="checkbox" name="billing_last_name" <?php if(get_option('billing_last_name')) echo ' checked'; ?> /><label><?php esc_html_e('Last Name','woo-customize'); ?></label></td>		
        </tr>        
        <tr valign="top">        
        <td><input type="checkbox" name="billing_company" <?php if(get_option('billing_company')) echo ' checked'; ?> /><label><?php esc_html_e('Company','woo-customize'); ?></label></td>		
        </tr>		
        <tr valign="top">        
        <td><input type="checkbox" name="billing_address_1" <?php if(get_option('billing_address_1')) echo ' checked'; ?> /><label><?php esc_html_e('Address 1','woo-customize'); ?></label></td>		
        </tr>		
        <tr valign="top">        
        <td><input type="checkbox" name="billing_address_2" <?php if(get_option('billing_address_2')) echo ' checked'; ?> /><label><?php esc_html_e('Address 2','woo-customize'); ?></label></td>		
        </tr>		
        <tr valign="top">        
        <td><input type="checkbox" name="billing_city" <?php if(get_option('billing_city')) echo ' checked'; ?> /><label><?php esc_html_e('City','woo-customize'); ?></label></td>		
        </tr>		
        <tr valign="top">        
        <td><input type="checkbox" name="billing_postcode" <?php if(get_option('billing_postcode')) echo ' checked'; ?> /><label><?php esc_html_e('Postcode','woo-customize'); ?></label></td>		
        </tr>		
        <tr valign="top">        
        <td><input type="checkbox" name="billing_country" <?php if(get_option('billing_country')) echo ' checked'; ?> /><label><?php esc_html_e('Country','woo-customize'); ?></label></td>		
        </tr>        
		<tr valign="top">        
        <td><input type="checkbox" name="billing_state" <?php if(get_option('billing_state')) echo ' checked'; ?> /><label><?php esc_html_e('State','woo-customize'); ?></label></td>		
        </tr>        
		<tr valign="top">        
        <td><input type="checkbox" name="billing_phone" <?php if(get_option('billing_phone')) echo ' checked'; ?> /><label><?php esc_html_e('Phone','woo-customize'); ?></label></td>		
        </tr>		
		<tr valign="top">        
        <td><input type="checkbox" name="billing_email" <?php if(get_option('billing_email')) echo ' checked'; ?> /><label><?php esc_html_e('Email','woo-customize'); ?></label></td>		
        </tr>
		<?php endif; ?>
		
		<?php if( $active_tab == 'free_options' ): ?>		
		<tr valign="top">        
        <td><input type="checkbox" name="billing_free_checkout" <?php if(get_option('billing_free_checkout')) echo ' checked'; ?> /><label><?php esc_html_e('Remove Checkout fields for Free products', 'woo-customize'); ?></label></td>		
        </tr>
		
		<tr valign="top">        
        <td><input type="checkbox" name="billing_virtual_checkout" <?php if(get_option('billing_virtual_checkout')) echo ' checked'; ?> /><label><?php esc_html_e('Remove Checkout fields for Virtual products', 'woo-customize'); ?></label></td>		
        </tr>				
		<?php endif; ?>
		
		<?php if( $active_tab == 'color_options' ): ?>
		<tr valign="top">
		<td style="padding-top:0px"><label><?php esc_html_e('Default colour', 'woo-customize'); ?></label></td>        
        <td><input type="text"  name="woo_customize_default_color" value="<?php esc_attr(get_option('woo_customize_default_color', '#ddd')); ?>" class="woo-customize-default-color-field" data-default-color="#ddd" /></td>
		</tr>
		<tr valign="top">
		<td style="padding-top:0px"><label><?php esc_html_e('Add to Cart text', 'woo-customize'); ?></label></td>        
        <td><input type="text"  name="woo_customize_add_to_cart_text" value="<?php esc_attr(get_option('woo_customize_add_to_cart_text', 'Add to Cart')); ?>" /></td>
		</tr>		
		<?php endif; ?>		
													
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php 
}