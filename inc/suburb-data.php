<?php
/**
 * Suburb definitions for CPT seeding.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * All suburbs listed on the /suburbs/ page.
 *
 * @return array<int, array{slug:string,name:string,area:string,random:int}>
 */
function superb_get_suburb_definitions() {
	return array(
		array( 'slug' => 'albert-park', 'name' => 'Albert Park', 'area' => 'Inner South Melbourne', 'random' => 301 ),
		array( 'slug' => 'armadale', 'name' => 'Armadale', 'area' => 'Inner South-East Melbourne', 'random' => 302 ),
		array( 'slug' => 'balwyn', 'name' => 'Balwyn', 'area' => 'Inner East Melbourne', 'random' => 303 ),
		array( 'slug' => 'brighton', 'name' => 'Brighton', 'area' => 'Bayside Melbourne', 'random' => 304 ),
		array( 'slug' => 'brunswick', 'name' => 'Brunswick', 'area' => 'Inner North Melbourne', 'random' => 305 ),
		array( 'slug' => 'camberwell', 'name' => 'Camberwell', 'area' => 'Inner East Melbourne', 'random' => 306 ),
		array( 'slug' => 'canterbury', 'name' => 'Canterbury', 'area' => 'Inner East Melbourne', 'random' => 307 ),
		array( 'slug' => 'caulfield', 'name' => 'Caulfield', 'area' => 'South-East Melbourne', 'random' => 308 ),
		array( 'slug' => 'chadstone', 'name' => 'Chadstone', 'area' => 'South-East Melbourne', 'random' => 309 ),
		array( 'slug' => 'elsternwick', 'name' => 'Elsternwick', 'area' => 'South-East Melbourne', 'random' => 310 ),
		array( 'slug' => 'essendon', 'name' => 'Essendon', 'area' => 'North-West Melbourne', 'random' => 311 ),
		array( 'slug' => 'glen-iris', 'name' => 'Glen Iris', 'area' => 'Inner East Melbourne', 'random' => 312 ),
		array( 'slug' => 'glen-waverley', 'name' => 'Glen Waverley', 'area' => 'South-East Melbourne', 'random' => 313 ),
		array( 'slug' => 'hampden', 'name' => 'Hampden', 'area' => 'Bayside Melbourne', 'random' => 314 ),
		array( 'slug' => 'hawthorn', 'name' => 'Hawthorn', 'area' => 'Inner East Melbourne', 'random' => 315 ),
		array( 'slug' => 'kew', 'name' => 'Kew', 'area' => 'Inner East Melbourne', 'random' => 316 ),
		array( 'slug' => 'malvern', 'name' => 'Malvern', 'area' => 'Inner South-East Melbourne', 'random' => 317 ),
		array( 'slug' => 'middle-park', 'name' => 'Middle Park', 'area' => 'Inner South Melbourne', 'random' => 318 ),
		array( 'slug' => 'mount-albert', 'name' => 'Mount Albert', 'area' => 'Eastern Suburbs Melbourne', 'random' => 319 ),
		array( 'slug' => 'peninsula-surrounds', 'name' => 'Peninsula & Around', 'area' => 'Mornington Peninsula', 'random' => 320 ),
		array( 'slug' => 'prahran', 'name' => 'Prahran', 'area' => 'Inner South Melbourne', 'random' => 321 ),
		array( 'slug' => 'richmond', 'name' => 'Richmond', 'area' => 'Inner City Melbourne', 'random' => 322 ),
		array( 'slug' => 'south-yarra', 'name' => 'South Yarra', 'area' => 'Inner South-East Melbourne', 'random' => 323 ),
		array( 'slug' => 'st-kilda', 'name' => 'St Kilda', 'area' => 'Inner South Melbourne', 'random' => 324 ),
		array( 'slug' => 'surrey-hills', 'name' => 'Surrey Hills', 'area' => 'Inner East Melbourne', 'random' => 325 ),
		array( 'slug' => 'templestowe', 'name' => 'Templestowe', 'area' => 'North-Eastern Melbourne', 'random' => 326 ),
		array( 'slug' => 'toorak', 'name' => 'Toorak', 'area' => 'Inner South-East Melbourne', 'random' => 327 ),
	);
}

/**
 * Slugs removed from the menu but may still exist in the database.
 *
 * @return string[]
 */
function superb_get_retired_suburb_slugs() {
	return array(
		'box-hill',
		'doncaster',
		'mulgrave',
		'clayton',
		'oakleigh',
		'wheelers-hill',
		'fitzroy',
		'hampton',
	);
}

/**
 * Unique suburb copy keyed by slug.
 *
 * @param string $slug Suburb slug.
 * @return array<string, mixed>|null
 */
function superb_get_suburb_content( $slug ) {
	static $all = null;

	if ( null === $all ) {
		$file = SUPERB_THEME_DIR . '/inc/suburb-content.php';
		$all  = file_exists( $file ) ? include $file : array();
	}

	if ( ! isset( $all[ $slug ] ) ) {
		return null;
	}

	return $all[ $slug ];
}

/**
 * Default suburb description paragraphs.
 *
 * @param string $name Suburb name.
 * @return array<int, string>
 */
function superb_suburb_default_description( $name ) {
	return array(
		"Superb Painting provides professional interior and exterior painting services to homeowners and businesses in {$name}. Our local team understands the unique character of {$name} homes and delivers flawless finishes on every project.",
		"Whether you need a single room refreshed, a full home repaint, or specialist finishes, we bring the same level of care and attention to detail that has earned us thousands of five-star reviews across Melbourne.",
	);
}

/**
 * Default work summary for suburb.
 *
 * @param string $name Suburb name.
 */
function superb_suburb_default_work_summary( $name ) {
	return "Our recent projects in {$name} include full interior and exterior repaints, special finishes, kitchen cabinet spray painting, and apartment makeovers. Every job includes thorough surface preparation, premium Dulux, Taubmans, Resene and Porter's paints, and a meticulous cleanup before we leave.";
}

/**
 * Services offered in any suburb.
 *
 * @return array<int, string>
 */
function superb_suburb_services_list() {
	return array(
		'Interior wall and ceiling painting',
		'Exterior wall and facade painting',
		'Kitchen cabinet spray painting',
		'Special finishes and feature walls',
		'Apartment interior and exterior painting',
		'Pre-sale property makeovers',
		'Commercial interior and exterior painting',
	);
}

/**
 * Nearby suburbs for cards.
 *
 * @param string $slug Current suburb slug.
 * @return array<int, string>
 */
function superb_suburb_nearby( $slug ) {
	$content = superb_get_suburb_content( $slug );
	if ( ! empty( $content['nearby'] ) ) {
		$all = wp_list_pluck( superb_get_suburb_definitions(), 'name', 'slug' );
		$out = array();
		foreach ( $content['nearby'] as $nearby_slug ) {
			if ( isset( $all[ $nearby_slug ] ) ) {
				$out[] = $all[ $nearby_slug ];
			}
		}
		if ( $out ) {
			return $out;
		}
	}

	$all   = wp_list_pluck( superb_get_suburb_definitions(), 'name', 'slug' );
	$slugs = array_keys( $all );
	$index = array_search( $slug, $slugs, true );
	if ( false === $index ) {
		return array_slice( array_values( $all ), 0, 6 );
	}

	$nearby = array();
	for ( $i = 1; $i <= 6; $i++ ) {
		$key      = ( $index + $i ) % count( $slugs );
		$nearby[] = $all[ $slugs[ $key ] ];
	}
	return $nearby;
}

/**
 * Marketing page URL for suburbs listing.
 */
function superb_suburbs_page_url() {
	return home_url( SUPERB_SUBURBS_URL );
}

/**
 * Marketing page URL for services listing.
 */
function superb_services_page_url() {
	return home_url( SUPERB_SERVICES_URL );
}
