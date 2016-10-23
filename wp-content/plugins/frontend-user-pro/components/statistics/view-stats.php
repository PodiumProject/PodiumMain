<?php


$stats = get_option( 'feup_members_statistics' );

$stats_view = $stats[$role];

$overall = array();

foreach ( $stats_view as $user ) {
	//$together = $user['date'];

	$together = $user['year'] . $user['month'];
//echo $together;

		++$overall[$together];
}

ksort( $overall ); ?>

<div class="wrap">

	<h2><?php _e( 'Statistics', 'feup_members' ); ?></h2>

	<?php //do_action( 'feup_members_pre_stats_form' ); // Available action hook for displaying messages. ?>

	<div id="poststuff">

			<?php //wp_nonce_field( feup_members_get_nonce( 'view-stats' ) ); ?>

			<table class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th class='name-column'><?php _e( 'Date', 'feup_members' ); ?></th>
						<th><?php _e( 'Users', 'feup_members' ); ?></th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<th class='name-column'><?php _e( 'Date', 'feup_members' ); ?></th>
						<th><?php _e( 'Users', 'feup_members' ); ?></th>
					</tr>
				</tfoot>

				<tbody id="users" class="list:user user-list plugins">


<?php foreach ( $overall as $month => $num ) {

	$date = $month;

	//$date = str_split( $month, 4 ); ?>
					<tr>
			<td><?php echo mysql2date( 'F', $date ) . ' ' . mysql2date( 'Y', $date ); ?></td>
			<td><?php echo $num; ?>
					</tr>

<?php
} ?>
				</tbody>
			</table>
	</div>
</div><?php












?>