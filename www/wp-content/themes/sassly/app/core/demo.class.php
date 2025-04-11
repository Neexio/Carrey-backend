<?php
namespace sassly\Core;

/**
 * demo import.
 */
class Demo
{
	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function register()
	{	
       add_filter( 'fw:ext:backups-demo:demos', [$this,'backups_demos'] );
	}
	
    function backups_demos( $demos ) {
        
        $demo_content_installer	 = 'https://themecrowdy.com/demo-content/info';
        
        $demos_array			 = array(
        
            'health' => array(
                'title'        => esc_html__( 'Health Coach', 'sassly' ),
                'category'     => ['health','hospital'],
                'screenshot'   => esc_url( $demo_content_installer ) . '/health/screenshot.webp',
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/health-coach' ),
            ),
            
            'artist' => array(
                'title'        => esc_html__( 'Artist', 'sassly' ),
                'category'     => ['artist','writer'],
                'screenshot'   => esc_url( $demo_content_installer ) . '/artist/screenshot.webp',
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/artist' ),
            ),
            
            'chef' => array(
                'title'        => esc_html__( 'Chef', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/chef/screenshot.webp',
                'category'     => ['hotel', 'chef'],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/chef/' ),
            ),
            
            'travel-guider' => array(
                'title'        => esc_html__( 'Travel Guider', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/travel-guider/screenshot.webp',
                'category'     => ['travel','tourist'],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/travel-guider/' ),
            ), 
            
            'doctor' => array(
                'title'        => esc_html__( 'Doctor', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/doctor/screenshot.webp',
                'category'     => ['doctor','hospital'],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/doctor/' ),
            ),
            
            'writer' => array(
                'title'        => esc_html__( 'Writer', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/writer/screenshot.webp',
                'category'     => [ 'author','writer','artist' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/writer/' ),
            ),
            
            'interior-design' => array(
                'title'        => esc_html__( 'Interior Designer', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/interior-design/screenshot.webp',
                'category'     => [ 'interior','designer','interior Designer' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/interior-designer/' ),
            ), 
            
            'photographer' => array(
                'title'        => esc_html__( 'Photographer', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/photographer/screenshot.webp',
                'category'     => [ 'photographer' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/photographer/' ),
            ),
            
            'electrician' => array(
                'title'        => esc_html__( 'Electrician', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/electrician/screenshot.webp',
                'category'     => [ 'electrician' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/electrician/' ),
            ),
            
            'content-creator' => array(
                'title'        => esc_html__( 'Content Creator', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/content-creator/content-creator.webp',
                'category'     => [ 'writer', 'author' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/content-creator/' ),
            ),
            
            'developer' => array(
                'title'        => esc_html__( 'Developer', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/developer/developer.webp',
                'category'     => [ 'developer' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/developer/' ),
            ),            
          
            
            'digital-marketer' => array(
                'title'        => esc_html__( 'Digital Marketer', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/digital-marketer/digital-marketer.webp',
                'category'     => [ 'marketer' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/digital-marketer/' ),
            ),
            
            'dancer' => array(
                'title'        => esc_html__( 'Dancer', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/dancer/dancer.webp',
                'category'     => [ 'dancer', 'artist' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/dancer/' ),
            ),           
           
            
            'yoga-trainer' => array(
                'title'        => esc_html__( 'Yoga Trainer', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/yoga-trainer/yoga-trainer.webp',
                'category'     => [ 'dancer', 'artist' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/yoga-trainer/' ),
            ),
            
            'lawyer' => array(
                'title'        => esc_html__( 'Lawyer', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/lawyer/lawyer.webp',
                'category'     => [ 'lawyer', 'artist' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/lawyer/' ),
            ),
            
            'motivational-speaker' => array(
                'title'        => esc_html__( 'Motivational Speaker', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/motivational-speaker/motivational-speaker.webp',
                'category'     => [ 'artist', 'Speaker' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/motivational-speaker/' ),
            ), 
            
            'dentist' => array(
                'title'        => esc_html__( 'Dentist', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/dentist/dentist.webp',
                'category'     => [ 'dentist' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/dentist/' ),
            ),
            
            'event-planner' => array(
                'title'        => esc_html__( 'Event Planner', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/event-planner/event-planner.webp',
                'category'     => [ 'event' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/event-planner/' ),
            ),
            
            'content-writer' => array(
                'title'        => esc_html__( 'Content Writer', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/content-writer/content-writer.webp',
                'category'     => [ 'writter' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/content-writer/' ),
            ),
            
            'product-designer' => array(
                'title'        => esc_html__( 'Product Designer', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/product-designer/product-designer.webp',
                'category'     => [ 'Designer' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/product-designer/' ),
            ), 
            
            'freelancer' => array(
                'title'        => esc_html__( 'Freelancer', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/freelancer/freelancer.webp',
                'category'     => [ 'Designer' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/freelancer/' ),
            ),
            'resume' => array(
                'title'        => esc_html__( 'Resume', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/resume/resume.webp',
                'category'     => [ 'resume' , 'CV' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/resume/' ),
            ), 
            
            'social-influencer' => array(
                'title'        => esc_html__( 'Social Influencer', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/social-influencer/social-influence.webp',
                'category'     => [ 'social-influencer' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/social-influencer/' ),
            ),
            
            'film-maker' => array(
                'title'        => esc_html__( 'Film Maker', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/film-maker/film-maker.webp',
                'category'     => [ 'film-maker' , 'artist' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/film-maker/' ),
            ),
            
            'beautician' => array(
                'title'        => esc_html__( 'Beautician', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/beautician/beautician-1.png',
                'category'     => [ 'beautician' , 'artist' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/beautician/' ),
            ),
            
            'barber' => array(
                'title'        => esc_html__( 'Barber', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/barber/barber.webp',
                'category'     => [ 'Barber' , 'saloon' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/barber/' ),
            ),
            
            'barber' => array(
                'title'        => esc_html__( 'Barber', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/barber/barber.webp',
                'category'     => [ 'Barber' , 'saloon' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/barber/' ),
            ), 
            'athlete' => array(
                'title'        => esc_html__( 'Athlete', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/athlete/athlete.webp',
                'category'     => [ 'Athlete' , 'Athlete' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/athlete/' ),
            ), 
            
            'architect' => array(
                'title'        => esc_html__( 'Architect', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/architect/architect.webp',
                'category'     => [ 'Architect', 'Designer' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/architect/' ),
            ), 
            
            'fashion-designer' => array(
                'title'        => esc_html__( 'Fashion Designer', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/fashion-designer/fashion-designer.webp',
                'category'     => [ 'fashion', 'Designer' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/fashion-designer/' ),
            ),
            
            'singer' => array(
                'title'        => esc_html__( 'Singer', 'sassly' ),
                'screenshot'   => esc_url( $demo_content_installer ) . '/singer/singer.webp',
                'category'     => [ 'singer', 'artist' ],
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/info/singer/' ),
            ),            
            
        );

        $download_url = esc_url( $demo_content_installer ) . '/download.php';
        try {
            foreach ( $demos_array as $id => $data ) {
                $demo		 = new \FW_Ext_Backups_Demo( $id, 'piecemeal', array(
                    'url'		 => $download_url,
                    'file_id'	 => $id,
                ) );
                $category = isset($data[ 'category' ]) ? $data[ 'category' ] : [];
                $demo->set_title( $data[ 'title' ] );
                $demo->set_screenshot( $data[ 'screenshot' ] );
                $demo->set_preview_link( $data[ 'preview_link' ] );
                $demo->set_category( $category );
                $demos[ $demo->get_id() ]	 = $demo;
                unset( $demo );
            }
        } catch (\Exception $e) {
            
        }  
        

        return $demos;
    }


}




