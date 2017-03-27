<?php
require_once( 'db.php' ); // Gives us $conn

$path = ! empty( $_GET['path'] ) ? strtolower( $_GET['path'] ) : '';
$args = explode( '/', $path );
if( $args[0] == 'plugin' && $args[1] == 'cratesplus' ) {

	$data = json_decode( gzdecode( file_get_contents('php://input') ) );
	if( ! empty( $data ) ) {
		$guid = isset( $data->guid ) ? mysqli_real_escape_string( $conn, $data->guid ) : '';
		$plugin_version = isset( $data->plugin_version ) ? mysqli_real_escape_string( $conn, $data->plugin_version ) : '';
		$server_version = isset( $data->server_version ) ? mysqli_real_escape_string( $conn, $data->server_version ) : '';
		$players_online = isset( $data->players_online ) ? mysqli_real_escape_string( $conn, $data->players_online ) : '';
		$osname = isset( $data->osname ) ? mysqli_real_escape_string( $conn, $data->osname ) : '';
		$osarch = isset( $data->osarch ) ? mysqli_real_escape_string( $conn, $data->osarch ) : '';
		$osversion = isset( $data->osversion ) ? mysqli_real_escape_string( $conn, $data->osversion ) : '';
		$cores = isset( $data->cores ) ? mysqli_real_escape_string( $conn, $data->cores ) : '';
		$auth_mode = isset( $data->auth_mode ) ? mysqli_real_escape_string( $conn, $data->auth_mode ) : '';
		$java_version = isset( $data->java_version ) ? mysqli_real_escape_string( $conn, $data->java_version ) : '';
		$remote_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? mysqli_real_escape_string( $conn, $_SERVER['REMOTE_ADDR'] ) : '';
		$date = date( 'Y-m-d H:i:s' );
		
		$sql = 'INSERT INTO `stats_cratesplus` ( `date`, `guid`, `plugin_version`, `server_version`, `players_online`, `osname`, `osarch`, `osversion`, `cores`, `auth_mode`, `java_version`, `remote_ip` ) VALUE ( "' . $date . '", "' . $guid . '", "' . $plugin_version . '", "' . $server_version . '", "' . $players_online . '", "' . $osname . '", "' . $osarch . '", "' . $osversion . '", "' . $cores . '", "' . $auth_mode . '", "' . $java_version . '", "' . $remote_ip . '" );';
		$conn->query( $sql );
		echo '1';
	} else {
		echo '7,Failed to parse data';
	}
	
} else {
	header( 'Location: https://mcstats.xyz', true, 302 );
}