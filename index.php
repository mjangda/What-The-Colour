<?php

define( 'DEBUG_MODE', false );

define( 'LOCAL_MODE', false );

define( 'PIX_URL', '127.0.0.1' );
define( 'PIX_PATH', '/rest/' );

define( 'CL_URL', 'www.colourlovers.com' );

global $cl_palette_paths;
$cl_palette_paths =  array(
	'base' => '/api/palettes',
	'top' => '/api/palettes/top',
	'new' => '/api/palettes/new',
	'random' => '/api/palettes/random'
);

define( 'MAX_PALETTES', 5 );
define( 'MAX_COLOURS', 5 );
define( 'MAX_IMAGES', 5 );

require_once('HttpClient.class.php');
require_once('utils.php');
require_once('view.utils.php');

function get_palettes( $type = 'top' ) {
	if( !LOCAL_MODE ) {
		$client = new HttpClient( CL_URL );
		
		$type_path = get_palette_type_path( $type );
		$client->get( $type_path );
		$xml = $client->getContent();
		
	} else {
		$xml = file_get_contents('top.xml');
	}
	$result = parse_xml($xml);
	
	out('get_palettes result', $result);
	
	if( !count( $result ) )
		return false;
	
	if( isset( $result['palette']['id'] ) )
		return array( $result['palette'] );
	else
		return $result['palette'];
		
}

function get_palette_type_path( $type ) {
	global $cl_palette_paths;

	if( isset( $cl_palette_paths[$type] ) )
		return $cl_palette_paths[$type];
	else
		return $cl_palette_paths['base'] . '?lover=' . urlencode( $type );
}

function get_images( $colours ) {

	if( !is_array( $colours ) ) $colours = array( $colours );

	$client = new HttpClient( PIX_URL );
	if( DEBUG_MODE ) $client->setDebug(true);
	$data = array('method' => 'color_search');
	$colours_data = build_colors_data_array( $colours );
	$data = array_merge($data, $colours_data);
	
	out( 'get_images data:', $data );
	
	$client->get( PIX_PATH, $data);
	$result = $client->getContent();
	
	$result_encoded = json_decode( $result );
	return isset( $result_encoded->result ) ? $result_encoded->result : array();
}

function get_image_url( $filepath ) {
	return 'http://' . PIX_URL . '/collection/?filepath=' . $filepath;
}

function build_colors_data_array( $colours ) {
	$colours_data = array();
	for( $i = 0; $i < count($colours); $i++ ) {
		$colour = $colours[$i];
		$colours_data['colors[' . $i . ']'] = $colour;
	}
	return $colours_data;
}

if( isset($_REQUEST['doing_ajax']) ) {
	$action = $_REQUEST['action'];
	switch( $action ) {
		case 'palettes':
			$type = $_REQUEST['type'];
			$palettes = get_palettes( $type );
			
			require_once('views/view.palettes.php');
			break;
		case 'images':
			$match_all = $_REQUEST['match_all'];
			$colours = $_REQUEST['colours'];
			require_once('views/views.images.php');
			break;			
		default:
			return;
	}
} else {
	require_once('view.php');
}
?>