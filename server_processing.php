<?php
include_once 'connection.php';
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'video_cv_requests';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array('db'=>"id"),
    array( 'db' => 'request_number', 'dt' => 0 ),
    array( 'db' => 'request_for',  'dt' => 1 ),
    array( 'db' => 'email',   'dt' => 2 ),
    array( 'db' => 'mobile',     'dt' => 3 ),
    array( 'db' => 'city',     'dt' => 4 ),
    array( 'db' => 'country',     'dt' => 5 ),
    array(
        'db'        => 'status',
        'dt'        => 6,
        'formatter' => function( $d, $row ) {
            switch($d){
				case '0':return 'Pending Verification';break;
				case '1':return 'Accepted';break;
				case '2':return 'Cancelled';break;
				case '3':return 'Rejected';break;
				default:return 'Pending Verification';break;
			}
        }
    ),
    array(
        'db'        => 'added',
        'dt'        => 7,
        'formatter' => function( $d, $row ) {
            return date( 'jS M y', strtotime($d));
        }
    ),
    array(
        'db'        => 'added',
        'dt'        => 8,
        'formatter' => function( $d, $row ) {
			$html="";
			if($row['status']=="2" || $row['status']=="3")
			{
				$html.= "<a href='".base_url."make-video-cv-request.php?t=".$row['id']."&action=rit' class='btn btn-success' title='Re-Initiate'><i class='fa fa-check-square-o'></i></a>";
			}
			else{
				$html.= "<a href='".base_url."make-video-cv-request.php?t=".$row['id']."&action=ccl' class='btn btn-danger' title='Cancel Request'><i class='fa fa-times'></i></a>";
			}
            $html.= "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='".base_url."make-video-cv-request.php?t=".$row['id']."&action=dlt' class='btn btn-danger' title='Delete Request'><i class='fa fa-trash'></i></a>";
			return $html;
		}
    )
);

// SQL server connection information
$sql_details = array(
    'user' => $GLOBALS['DBUSERNAME'],
    'pass' => $GLOBALS['DBPASSWORD'],
    'db'   => $GLOBALS['DBNAME'],
    'host' => $GLOBALS['DBHOST']
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);