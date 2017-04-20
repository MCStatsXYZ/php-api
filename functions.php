<?php


function output_to_json( $data ) {
    header( 'Content-Type: application/json' );
    echo json_encode( $data );
    exit;
}

function get_uptime() {
    // $str   = @file_get_contents('/proc/uptime');
    // $num   = floatval($str);
    // $secs  = $num % 60;
    // $num   = (int)($num / 60);
    // $mins  = $num % 60;
    // $num   = (int)($num / 60);
    // $hours = $num % 24;
    // $num   = (int)($num / 24);
    // $days  = $num;

    // $time = $days.":".$hours.":".$minutes.":".$seconds;
    return '';
    return $time;
}

function get_open_connections( $conn ) {
    $sql = 'show status where `Variable_name` = "Threads_connected";';
    $result = $conn->query( $sql );
    if( $result->num_rows == 1 ) {
        while( $row = $result->fetch_assoc() ) {
            return $row['Value'];
        }
    }
    return 0;
}

function get_table_size( $conn ) {
    $sql = 'SELECT 
    table_name AS `Table`, 
    round(((data_length + index_length) / 1024 / 1024), 2) `Size` 
    FROM information_schema.TABLES 
    WHERE table_schema = "connorli_mcstats"
    AND table_name = "stats_cratesplus";';
    $result = $conn->query( $sql );
    if( $result->num_rows == 1 ) {
        while( $row = $result->fetch_assoc() ) {
            return $row['Size'];
        }
    }
    return 0;
}

function get_database_size( $conn ) {
    $sql = 'SELECT table_schema,
    Round(Sum(data_length + index_length) / 1024 / 1024, 1) "Size" 
    FROM   information_schema.tables 
    WHERE table_schema LIKE "connorli_mcstats"
    GROUP  BY table_schema; ';
    $result = $conn->query( $sql );
    if( $result->num_rows == 1 ) {
        while( $row = $result->fetch_assoc() ) {
            return $row['Size'];
        }
    }
    return 0;
}

function get_servers_count( $conn ) {
    $result = $conn->query( 'SELECT COUNT(distinct guid) as `Rows` FROM `stats_cratesplus`;' );
    if( $result->num_rows == 1 ) {
        while( $row = $result->fetch_assoc() ) {
            return $row['Rows'];
        }
    }
    return 0;
}

function does_table_exist( $conn, $table ) {
    $result = $conn->query( 'SHOW TABLES LIKE "' . $table . '";' );
    return $result->num_rows > 0;
}

function clone_table_structure( $conn, $old, $new ) {
    $conn->query( 'CREATE TABLE ' . $new . ' LIKE ' . $old . '; ' );
    return true;
}