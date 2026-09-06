<?php
/**
 * Service page structured content (used by single-service.php).
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * External URL for a service when it should leave the main site.
 *
 * @param string $slug Service slug.
 * @return string
 */
function superb_get_service_external_url( $slug = '' ) {
	$def = superb_get_service( $slug );
	return ( $def && ! empty( $def['external_url'] ) ) ? $def['external_url'] : '';
}

/**
 * Permalink or external URL for a service.
 *
 * @param string $slug Service slug.
 * @return string
 */
function superb_get_service_link( $slug = '' ) {
	$external = superb_get_service_external_url( $slug );
	if ( $external ) {
		return $external;
	}

	$post = get_page_by_path( $slug, OBJECT, 'service' );
	return $post ? get_permalink( $post ) : home_url( SUPERB_SERVICES_URL );
}

/**
 * Get all service definitions keyed by slug.
 */
function superb_get_service_definitions() {
	return array(
		'interior-wall-painting' => array(
			'title'    => 'Interior & Exterior Painting',
			'tagline'  => 'Premium interior and exterior painting with full surface preparation',
			'random'   => 101,
			'intro'    => 'Our most popular service covers everything from surface preparation and patching to the final coat. We use premium low-VOC Dulux, Taubmans, Resene and Porter\'s paints for a clean, healthy finish that lasts years.',
			'included' => array(
				'Full interior and exterior surface preparation',
				'Premium low-VOC Dulux, Taubmans, Resene or Porter\'s paint',
				'Two coats minimum on all surfaces',
				'Trim, skirting and door painting included',
				'Complete furniture protection and daily cleanup',
				'Colour consultation at no extra cost',
			),
			'steps'    => array(
				array( 'title' => 'Free Assessment', 'desc' => 'We visit your home and provide a detailed written quote within 24 hours.' ),
				array( 'title' => 'Preparation', 'desc' => 'We patch, sand, prime and protect all furniture and floors.' ),
				array( 'title' => 'Painting', 'desc' => 'Two coats of premium paint with precision cutting-in and even coverage.' ),
				array( 'title' => 'Final Inspection', 'desc' => 'We walk through every room with you before we leave.' ),
			),
			'faq'      => array(
				array( 'q' => 'How long does a single room take?', 'a' => 'Most standard rooms are completed in one day including preparation and two coats.' ),
				array( 'q' => 'Do you move furniture?', 'a' => 'Yes, we carefully move and cover all furniture with full protection.' ),
				array( 'q' => 'What paint brands do you use?', 'a' => 'We use premium Dulux, Taubmans, Resene and Porter\'s paint systems.' ),
				array( 'q' => 'Do you paint exteriors as well?', 'a' => 'Yes — interior and exterior painting for homes, apartments and commercial spaces.' ),
			),
			'testimonial' => array(
				'quote'    => 'The team painted our entire 4-bedroom home in just 3 days and the finish is immaculate.',
				'author'   => 'Sarah M.',
				'location' => 'Hawthorn',
			),
		),
		'kitchen-cabinet-painting' => array(
			'title'        => 'Kitchen Cabinet, Doors, Metal TwoPak Painting',
			'tagline'      => 'Factory-quality cabinet, door and metal TwoPak finishes',
			'random'       => 111,
			'external_url' => 'https://superbtwopak.com.au',
			'intro'        => 'For kitchen cabinets, doors and metal TwoPak spray finishes, visit our dedicated TwoPak site. Professional spray-painted finishes at a fraction of replacement cost.',
			'included'     => array(
				'Kitchen cabinet spray refinishing',
				'Door and joinery TwoPak painting',
				'Metal TwoPak coating systems',
				'Professional off-site spray booth finishing',
				'Wide colour and sheen options',
				'Factory-quality durability',
			),
			'steps'        => array(
				array( 'title' => 'Consultation', 'desc' => 'We assess your project and provide a written quote.' ),
				array( 'title' => 'Preparation', 'desc' => 'Surfaces are degreased, sanded and primed for adhesion.' ),
				array( 'title' => 'Spray Finish', 'desc' => 'Components are spray-painted for a flawless factory finish.' ),
				array( 'title' => 'Reinstallation', 'desc' => 'Everything is reinstalled and touched up on-site.' ),
			),
			'faq'          => array(
				array( 'q' => 'Where can I learn more?', 'a' => 'Visit superbtwopak.com.au for full details on cabinet, door and metal TwoPak services.' ),
				array( 'q' => 'Do you paint laminate cabinets?', 'a' => 'Yes, with proper prep and bonding primer.' ),
				array( 'q' => 'How durable is the finish?', 'a' => 'Our TwoPak polyurethane systems are designed for high-traffic kitchens.' ),
				array( 'q' => 'Do you handle metal surfaces?', 'a' => 'Yes — gates, railings, metal doors and joinery.' ),
			),
			'testimonial'  => array(
				'quote'    => 'Guests think we had a full renovation! Professional, clean, and exactly on schedule.',
				'author'   => 'Michael T.',
				'location' => 'Richmond',
			),
		),
		'special-finishes' => array(
			'title'    => 'Special Finishes',
			'tagline'  => 'Decorative and specialist paint finishes for distinctive interiors and exteriors',
			'random'   => 121,
			'intro'    => 'We specialise in decorative and specialist finishes using premium Resene, Porter\'s, Dulux and Taubmans systems — from limewash and French wash to epoxy floors, texture coatings and mirror finishes.',
			'included' => array(
				'Lime Wash',
				'French Wash',
				'Stone Paint',
				'STE',
				'TwoPak',
				'Handpainting Joinery',
				'Epoxy Floors',
				'Concrete Paint',
				'Texture Coating',
				'Staining',
				'Clear Coating',
				'Render Painting',
				'Anti Graffiti',
				'Mirror Finish',
			),
			'steps'    => array(
				array( 'title' => 'Consultation', 'desc' => 'We discuss the look you want and recommend suitable specialist finishes.' ),
				array( 'title' => 'Samples', 'desc' => 'Test patches ensure you love the effect before we proceed.' ),
				array( 'title' => 'Application', 'desc' => 'Specialist products applied with precision and care.' ),
				array( 'title' => 'Reveal', 'desc' => 'Final cleanup and walkthrough with you.' ),
			),
			'faq'      => array(
				array( 'q' => 'What special finishes do you offer?', 'a' => 'Lime wash, French wash, stone paint, STE, TwoPak, handpainting joinery, epoxy floors, concrete paint, texture coating, staining, clear coating, render painting, anti graffiti and mirror finish.' ),
				array( 'q' => 'Which brands do you use?', 'a' => 'Resene, Porter\'s, Dulux and Taubmans specialist systems.' ),
				array( 'q' => 'Can you match a sample or mood board?', 'a' => 'Yes — we work from photos, samples and designer briefs.' ),
				array( 'q' => 'Interior and exterior?', 'a' => 'Yes — specialist finishes for both interior and exterior surfaces.' ),
			),
			'testimonial' => array(
				'quote'    => 'The limewash feature wall completely transformed our living room.',
				'author'   => 'Linda K.',
				'location' => 'Brighton',
			),
		),
		'apartment-painting' => array(
			'title'    => 'Apartment Painting',
			'tagline'  => 'Specialist interior and exterior painting for Melbourne apartments',
			'random'   => 131,
			'intro'    => 'We coordinate with strata, use low-odour paints, and complete most apartments in 1–2 days with minimal disruption to neighbours.',
			'included' => array(
				'Strata and building access coordination',
				'Low-odour, low-VOC paints',
				'Lift and common area protection',
				'Quiet, respectful working practices',
				'Minimal disruption to neighbours',
				'Complete cleanup and rubbish removal',
			),
			'steps'    => array(
				array( 'title' => 'Access Planning', 'desc' => 'We coordinate lift bookings and building access.' ),
				array( 'title' => 'Protection', 'desc' => 'Common areas and neighbouring walls are fully protected.' ),
				array( 'title' => 'Efficient Painting', 'desc' => 'Most apartments completed in 1–2 days.' ),
				array( 'title' => 'Handover', 'desc' => 'Common areas cleaned before we leave.' ),
			),
			'faq'      => array(
				array( 'q' => 'Do you work in high-rise buildings?', 'a' => 'Yes, regularly across inner Melbourne towers.' ),
				array( 'q' => 'Will neighbours be disturbed?', 'a' => 'We use low-odour paints and work quietly.' ),
				array( 'q' => 'Do I need strata approval?', 'a' => 'We can advise and provide documentation if required.' ),
				array( 'q' => 'Can I stay during painting?', 'a' => 'Yes — we work room by room.' ),
			),
			'testimonial' => array(
				'quote'    => 'They finished our 2-bed apartment in a single day. Impressive.',
				'author'   => 'David P.',
				'location' => 'South Yarra',
			),
		),
		'new-build-painting' => array(
			'title'    => 'New Build & Renovation Painting',
			'tagline'  => 'Builder-grade reliability with a premium finish',
			'random'   => 141,
			'intro'    => 'We work with builders and homeowners on new constructions and major renovations. Written quotes with clear scope before work begins.',
			'included' => array(
				'New construction and renovation experience',
				'Written quotes with agreed scope',
				'Builder and homeowner invoicing',
				'Colour schedule coordination',
				'Full interior, exterior and trim painting',
				'Touch-up service after handover',
			),
			'steps'    => array(
				array( 'title' => 'Scope & Quote', 'desc' => 'We review plans and provide a detailed written quote.' ),
				array( 'title' => 'Scheduling', 'desc' => 'We coordinate with your builder\'s schedule.' ),
				array( 'title' => 'Execution', 'desc' => 'All interior and exterior painting on time and to spec.' ),
				array( 'title' => 'Handover', 'desc' => 'Final walkthrough plus 30-day touch-up service.' ),
			),
			'faq'      => array(
				array( 'q' => 'Do you work with builders?', 'a' => 'Yes — preferred painters for several Melbourne builders.' ),
				array( 'q' => 'Are quotes fixed once agreed?', 'a' => 'Yes — your written quote reflects the agreed scope.' ),
				array( 'q' => 'When should painting happen?', 'a' => 'Typically after plaster, before flooring and fixtures.' ),
				array( 'q' => 'Touch-ups after handover?', 'a' => 'Yes — complimentary 30-day touch-up included.' ),
			),
			'testimonial' => array(
				'quote'    => 'Superb Painting handled our entire new home interior and exterior. Builder was impressed.',
				'author'   => 'Chris & Anna W.',
				'location' => 'Malvern',
			),
		),
		'commercial-painting' => array(
			'title'    => 'Commercial Interior & Exterior Painting',
			'tagline'  => 'Professional painting for offices, retail and commercial spaces',
			'random'   => 151,
			'intro'    => 'We paint offices, retail stores, restaurants and medical centres. After-hours and weekend work available to minimise disruption.',
			'included' => array(
				'After-hours and weekend work available',
				'Commercial invoicing and payment terms',
				'Minimal business disruption',
				'OH&S compliant work practices',
				'Large team capacity for tight deadlines',
				'Maintenance programs available',
			),
			'steps'    => array(
				array( 'title' => 'Site Assessment', 'desc' => 'We assess your space and provide a detailed proposal.' ),
				array( 'title' => 'Planning', 'desc' => 'Work schedule that minimises disruption.' ),
				array( 'title' => 'Execution', 'desc' => 'Efficient work, often after hours.' ),
				array( 'title' => 'Sign-off', 'desc' => 'Final inspection with your facilities manager.' ),
			),
			'faq'      => array(
				array( 'q' => 'After-hours work?', 'a' => 'Yes — evenings and weekends available.' ),
				array( 'q' => 'Commercial invoicing?', 'a' => 'Standard payment terms for accounts departments.' ),
				array( 'q' => 'OH&S compliant?', 'a' => 'Fully — current SWMS and insurance documentation.' ),
				array( 'q' => 'Large projects?', 'a' => 'We scale our team for any size project.' ),
			),
			'testimonial' => array(
				'quote'    => 'Monday morning it looked brand new. Not a speck of dust anywhere.',
				'author'   => 'Rebecca L.',
				'location' => 'Melbourne CBD',
			),
		),
	);
}

/**
 * Get service definition for current or given slug.
 *
 * @param string $slug Service slug.
 */
function superb_get_service( $slug = '' ) {
	if ( ! $slug ) {
		$slug = get_post_field( 'post_name', get_the_ID() );
	}
	$all = superb_get_service_definitions();
	return isset( $all[ $slug ] ) ? $all[ $slug ] : null;
}
