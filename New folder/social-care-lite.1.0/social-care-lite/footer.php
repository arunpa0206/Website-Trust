<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Social Care Lite
 */
?>

<div class="footer-wrapper"> 
      <div class="container">           
       <?php if ( is_active_sidebar( 'footer-widget-1' ) ) : ?>
                <div class="widget-column-1">  
                    <?php dynamic_sidebar( 'footer-widget-1' ); ?>
                </div>
           <?php endif; ?>
          
          <?php if ( is_active_sidebar( 'footer-widget-2' ) ) : ?>
                <div class="widget-column-2">  
                    <?php dynamic_sidebar( 'footer-widget-2' ); ?>
                </div>
           <?php endif; ?>
           
           <?php if ( is_active_sidebar( 'footer-widget-3' ) ) : ?>
                <div class="widget-column-3">  
                    <?php dynamic_sidebar( 'footer-widget-3' ); ?>
                </div>
           <?php endif; ?>
           
           <?php if ( is_active_sidebar( 'footer-widget-4' ) ) : ?>
                <div class="widget-column-4">  
                    <?php dynamic_sidebar( 'footer-widget-4' ); ?>
                </div>
           <?php endif; ?>
           
           <div class="clear"></div>
      </div><!--end .container-->

        <div class="footer-copyright"> 
            <div class="container">
                <div class="powerby">
				  <?php bloginfo('name'); ?> | 
                  <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'social-care-lite' ) ); ?>">
				     <?php
				         /* translators: %s: WordPress. */
				          printf( __( 'Proudly Powered by %s.', 'social-care-lite' ), 'WordPress' );
				     ?>
			       </a>                 
                </div>
                        	
                <div class="design-by">				
                  <a href="<?php echo esc_url( __( 'https://gracethemes.com/themes/free-charitable-organizations-wordpress-theme/', 'social-care-lite' ) ); ?>" target="_blank">
				    <?php printf( __( 'Theme by %s', 'social-care-lite' ), 'Grace Themes' ); ?>
                  </a>
                </div>
                <div class="clear"></div>
             </div><!--end .container-->             
        </div><!--end .footer-copyright-->  
                     
     </div><!--end #footer-wrapper-->
</div><!--#end sitelayout_type-->

<?php wp_footer(); ?>
</body>
</html>