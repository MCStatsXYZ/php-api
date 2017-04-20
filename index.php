<?php
require_once( 'db.php' ); // Gives us $conn
require_once( 'functions.php' ); // Gives us some useful functions

$path = ! empty( $_GET['path'] ) ? strtolower( $_GET['path'] ) : '';
$args = explode( '/', $path );
if( $args[0] == 'plugin' ) {
    
    $plugin = $args[1];
    
    if( ! does_table_exist( $conn, 'stats_' . $plugin ) ) {
        clone_table_structure( $conn, 'template', 'stats_' . $plugin );
    }

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
		
		$sql = 'INSERT INTO `stats_' . $plugin . '` ( `date`, `guid`, `plugin_version`, `server_version`, `players_online`, `osname`, `osarch`, `osversion`, `cores`, `auth_mode`, `java_version`, `remote_ip` ) VALUE ( "' . $date . '", "' . $guid . '", "' . $plugin_version . '", "' . $server_version . '", "' . $players_online . '", "' . $osname . '", "' . $osarch . '", "' . $osversion . '", "' . $cores . '", "' . $auth_mode . '", "' . $java_version . '", "' . $remote_ip . '" );';
		$conn->query( $sql );
		echo '1';
	} else {
		echo '7,Failed to parse data';
	}
	
} else {
    
    switch( strtolower( ! empty( $args[0] ) ? $args[0] : '' ) ) {
        default:
            output_to_json( array( 'success' => false, 'error' => 'Unknown API version' ) );
            break;
            
        case 'v1':
            array_shift( $args );

            switch( strtolower( ! empty( $args[0] ) ? $args[0] : '' ) ) {
                default:
                    output_to_json( array( 'success' => false, 'error' => 'Unknown method' ) );
                    break;
                    
                case 'stats':
                    $row_count = 0;
                    $row_count_result = $conn->query( 'SELECT SUM(TABLE_ROWS) as Rows FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = "connorli_mcstats";' );
                    if( $row_count_result->num_rows == 1 ) {
                        while( $row = $row_count_result->fetch_assoc() ) {
                            $row_count = $row['Rows'];
                        }
                    }
                    output_to_json( array( 'success' => true, 'rows' => $row_count, 'uptime' => get_uptime(), 'db_connections' => get_open_connections( $conn ), 'db_size' => get_database_size( $conn ) . 'MB', 'servers' => get_servers_count( $conn ) ) );
                    break;
            }
            
            break;
        
    }
    
    
	//header( 'Location: https://mcstats.xyz', true, 302 );
}