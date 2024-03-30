<?php

declare(strict_types = 1);

use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Export\Customer;
use NineteenEightyFour\NineteenEightyWoo\Export\Order;

$dk_order_number = Order::get_dk_order_number( $order );

$customer_id = $order->get_customer_id();
if ( 0 === $customer_id ) {
	$dk_customer_number = Config::get_guest_customer_number();
} else {
	$customer           = new WC_Customer( $customer_id );
	$dk_customer_number = Customer::get_dk_customer_number( $customer );
}

?>

<?php if ( false === empty( $dk_customer_number ) && false === empty( $dk_order_number ) ) : ?>

<table id="woocommerce-dk-order-meta">
	<tbody>
		<?php if ( false === empty( $dk_order_number ) ) : ?>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'DK Order Number', 'NineteenEightyWoo' ); ?></th>
			<td>
				<?php echo esc_html( $dk_order_number ); ?>
			</td>
		</tr>
		<?php endif ?>

		<?php if ( false === empty( $dk_customer_number ) ) : ?>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'DK Customer Number', 'NineteenEightyWoo' ); ?></th>
			</th>
			<td>
				<a
					href="https://www.dkplus.is/customers/detail?id=<?php echo esc_attr( $dk_customer_number ); ?>"
					target="_blank"
					title="<?php esc_attr_e( 'View in dkPlus', 'NineteenEightyWoo' ); ?>"
				>
					<?php echo esc_html( $dk_customer_number ); ?>
				</a>
			</td>
		</tr>
		<?php endif ?>
	</tbody>
</table>

<?php endif ?>
