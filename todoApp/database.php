<?php
switch ( $_REQUEST['action'] ) {
	case 'select' :
		get_todolist();
		break;
	case 'add' :
		add_todo();
		break;
	case 'update' :
		update_todo();
		break;
	case 'delete' :
		delete_todo();
		break;
	default :
		echo 'Nothing to see here!';
	die();
}

function get_todolist(){
	$status = isset( $_REQUEST['status'] ) ? $_REQUEST['status'] : null;
	$is_done = ( 'hideCompleted' == $status ) ? 'false' : 'true';
	if( isset( $_REQUEST['status'] ) ){
		$sql = "SELECT * FROM todo_list WHERE is_done='{$is_done}' ORDER BY item_order ASC";
	} else {
		$sql = "SELECT * FROM todo_list ORDER BY item_order ASC";
	}
	$result = todo_database_query( $sql , 'select');
	todo_json_response( $result );
}

function add_todo() {
	$itemname = isset( $_REQUEST['itemname'] ) ? $_REQUEST['itemname'] : null;
	$sql = "INSERT INTO todo_list ( item_name ) VALUES ('{$itemname}')";
	$result = todo_database_query( $sql );
	todo_json_response( $result );
}

function update_todo() {
	$itemid = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : null;
	$is_done_val = isset( $_REQUEST['is_done_val'] ) ? $_REQUEST['is_done_val'] : 'false';
	$sql = "UPDATE todo_list SET is_done = '{$is_done_val}' WHERE id={$itemid}";
	$result = todo_database_query( $sql );
	todo_json_response( $result );
}

function delete_todo(){
	$itemid = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : null;
	$sql = "DELETE FROM todo_list WHERE id={$itemid}";
	$result = todo_database_query( $sql , 'select');
	todo_json_response( $result );
}

function todo_database_query( $query = '', $action = '' ) {
	$result = null;

	if ( ! empty( $query ) ) {
		$mysqli = new mysqli('localhost', 'root', '123', 'angulardb');
		$result = $mysqli->query( $query ) or die( $mysqli->error . __LINE__ );
		if ( 'select' === $action) {
			if ($result->num_rows > 0) {
				$res = array();
				$i = 0;
				while($row = $result->fetch_assoc()) {
					$res[$i]['id'] = $row["id"];
					$res[$i]['itemname'] = $row["item_name"];
					$res[$i]['is_done'] = $row["is_done"];
					$i++;
				}
			}
		$result = $res;
		}
	}
	return $result;
}

function todo_json_response( $response ) {
	@header( 'Content-Type: application/json; charset=UTF-8' );
	echo json_encode( $response );
	die();
}
?>
