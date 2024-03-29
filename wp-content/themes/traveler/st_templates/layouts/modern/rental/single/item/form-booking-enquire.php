<div class="form-book-wrapper">
	<?php echo st()->load_template( 'layouts/modern/common/loader' ); ?>
	<div class="form-head">

	<?php if ( $min_price < $orgin_price ) : ?>
		<div class="price-regular">
			<span>
				<?php echo esc_html__( TravelHelper::format_money( $orgin_price ), 'traveler' ) ?>
			</span>
		</div>
	<?php endif; ?>

	<?php
	if ( isset( $number_day ) && $number_day > 1 ) {
		echo wp_kses( sprintf( __( '<div class="price-min">from <span class="price">%1$s</span> per %2$s nights</div>', 'traveler' ), TravelHelper::format_money( $min_price ), $number_day ), [
			'span' => [ 'class' => [] ],
			'div'  => [ 'class' => [] ],
		] );
	} else {
		echo wp_kses( sprintf( __( '<div class="price-min">from <span class="price">%s</span> per night</div>', 'traveler' ), TravelHelper::format_money( $min_price ) ), [
			'span' => [ 'class' => [] ],
			'div'  => [ 'class' => [] ],
		] );
	}
	?>
	</div>
	<h4 class="title-enquiry-form"><?php echo esc_html__( 'Inquiry', 'traveler' ); ?></h4>
	<?php echo st()->load_template( 'email/email_single_service' ); ?>
	<form id="form-booking-inpage" class="form form-has-guest-name single-room-form rental-booking-form" method="post">
		<input name="action" value="rental_add_cart" type="hidden">
		<input name="item_id" value="<?php echo esc_attr( $room_id ); ?>" type="hidden">
		<?php wp_nonce_field( 'room_search', 'room_search' ) ?>
		<?php
		$current_calendar        = TravelHelper::get_current_available_calendar( get_the_ID() );
		$current_calendar_reverb = date( 'm/d/Y', strtotime( $current_calendar ) );

		$start    = STInput::get( 'start', date( TravelHelper::getDateFormat(), strtotime( $current_calendar ) ) );
		$end      = STInput::get( 'end', date( TravelHelper::getDateFormat(), strtotime( '+ 1 day', strtotime( $current_calendar ) ) ) );
		$date     = STInput::get( 'date', date( 'd/m/Y h:i a', strtotime( $current_calendar ) ) . '-' . date( 'd/m/Y h:i a', strtotime( '+1 day', strtotime( $current_calendar ) ) ) );
		$has_icon = ( isset( $has_icon ) ) ? $has_icon : false;
		?>
		<div class="form-group form-date-field form-date-hotel-room clearfix
		<?php
		if ( $has_icon ) {
			echo ' has-icon ';}
		?>
			"
				data-format="<?php echo TravelHelper::getDateFormatMoment() ?>" data-availability-date="<?php echo esc_attr( $current_calendar_reverb ); ?>">

			<input type="hidden" class="check-in-input"
					value="<?php echo esc_attr( $start ) ?>" name="start">
			<input type="hidden" class="check-out-input"
					value="<?php echo esc_attr( $end ) ?>" name="end">
			<input type="text" class="check-in-out"
					data-minimum-day="<?php echo esc_attr( $booking_period ); ?>"
					data-room-id="<?php echo esc_attr( $room_id ) ?>"
					data-action="st_get_availability_rental_single"
					value="<?php echo esc_attr( $date ); ?>" name="date">
		</div>
		<input style="display:none;" type="submit" class="btn btn-default btn-send-message" data-id="<?php echo get_the_ID(); ?>" name="st_send_message" value="<?php echo __( 'Send message', 'traveler' ); ?>">
	</form>
</div>
