
        <div class="chbs-clear-fix chbs-booking-complete">   
            <div class="chbs-meta-icon-tick">
                <div></div>
                <div></div>
            </div>
            <h3><?php esc_html_e('Thank you for your order','chauffeur-booking-system'); ?></h3>
            <p class="chbs-booking-complete-payment-cash">
                <a href="<?php the_permalink(); ?>" class="chbs-button chbs-button-style-1"><?php esc_html_e('Back to home','chauffeur-booking-system'); ?></a>
            </p>
            <p class="chbs-booking-complete-payment-paypal">
                <?php _e('You will be redirected to the payment page within <span>5</span> second.','chauffeur-booking-system'); ?>
            </p>
            <p class="chbs-booking-complete-payment-stripe">
                <a href="#" class="chbs-button chbs-button-style-1"><?php esc_html_e('Pay via Stripe','chauffeur-booking-system'); ?></a>
            </p>
            <p class="chbs-booking-complete-payment-wire-transfer">
                <?php echo nl2br($this->data['meta']['payment_wire_transfer_info']); ?>
            </p>
            <p class="chbs-booking-complete-payment-woocommerce">
                <a href="#" class="chbs-button chbs-button-style-1"><?php esc_html_e('Pay for order','chauffeur-booking-system'); ?></a>
            </p>
        </div>