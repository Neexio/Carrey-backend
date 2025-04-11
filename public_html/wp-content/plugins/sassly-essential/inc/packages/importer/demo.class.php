<?php
namespace SasslyEssentialApp\Importer;

/**
 * demo import.
 */
class Wcf_Theme_Demos
{
    public $_metas = array(        
        'sassly_lic_Key',
        'sassly_lic_email',
    );
	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function __construct()
	{
       
       add_action( 'fw:ext:backups:tasks:success', [$this,'success'] );
       
        if( !sassly_theme_service_pass() ){
            return;
        }
       
       add_filter( 'fw:ext:backups-demo:demos', [$this,'backups_demos'] );     
 	}
	
    function backups_demos( $demos ) {
        
        $demo_content_installer	 = 'https://themecrowdy.com/demo-content/sassly';
        
        $demos_array			 = array(
        
            'ai-content-writer' => array(
                'title'        => esc_html__( 'AI Content Writer', 'sassly-essential' ),
                'category'     => [ 'ai-content-writer' ],
                'screenshot'   => esc_url( $demo_content_installer ) . '/ai-content-writer/sc.png',
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/sassly/ai-conetnt-writer/' ),
            ),
            'ai-image-generator' => array(
                'title'        => esc_html__( 'AI Image Generator', 'sassly-essential' ),
                'category'     => [ 'ai-image-generator' ],
                'screenshot'   => esc_url( $demo_content_installer ) . '/ai-image-generator/sc.png',
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/sassly/image-generator/' ),
            ),
            'ai-chatbot' => array(
                'title'        => esc_html__( 'AI Chatbot', 'sassly-essential' ),
                'category'     => [ 'ai-chatbot' ],
                'screenshot'   => esc_url( $demo_content_installer ) . '/ai-chatbot/sc.png',
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/sassly/ai-chatbot/' ),
            ),
            'ai-seo' => array(
                'title'        => esc_html__( 'Ai SEO Software', 'sassly-essential' ),
                'category'     => [ 'ai-seo' ],
                'screenshot'   => esc_url( $demo_content_installer ) . '/ai-seo/sc.png',
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/sassly/ai-seo/' ),
            ),
            'ai-startup' => array(
                'title'        => esc_html__( 'AI Startup', 'sassly-essential' ),
                'category'     => [ 'ai-startup' ],
                'screenshot'   => esc_url( $demo_content_installer ) . '/ai-startup/sc.png',
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/sassly/ai-software/' ),
            ),
            'ai-video-editor' => array(
                'title'        => esc_html__( 'AI Video Editor', 'sassly-essential' ),
                'category'     => [ 'ai-video-editor' ],
                'screenshot'   => esc_url( $demo_content_installer ) . '/ai-video-editor/sc.png',
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/sassly/ai-video-editor/' ),
            ),
            'booking-software' => array(
                'title'        => esc_html__( 'Booking Software', 'sassly-essential' ),
                'category'     => [ 'booking-software' ],
                'screenshot'   => esc_url( $demo_content_installer ) . '/booking-software/sc.png',
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/sassly/home-booking/' ),
            ),
            'virtual-meeting' => array(
                'title'        => esc_html__( 'Virtual Meeting', 'sassly-essential' ),
                'category'     => [ 'virtual-meeting' ],
                'screenshot'   => esc_url( $demo_content_installer ) . '/virtual-meeting/sc.png',
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/sassly/online-meeting/' ),
            ),
            'crm-software' => array(
                'title'        => esc_html__( 'CRM Software', 'sassly-essential' ),
                'category'     => [ 'crm-software' ],
                'screenshot'   => esc_url( $demo_content_installer ) . '/crm-software/sc.png',
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/sassly/crm/' ),
            ),
            'customer-support' => array(
                'title'        => esc_html__( 'Customer Support', 'sassly-essential' ),
                'category'     => [ 'customer-support' ],
                'screenshot'   => esc_url( $demo_content_installer ) . '/customer-support/sc.png',
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/sassly/customer-service/' ),
            ),
            'mobile-app' => array(
                'title'        => esc_html__( 'Mobile App', 'sassly-essential' ),
                'category'     => [ 'mobile-app' ],
                'screenshot'   => esc_url( $demo_content_installer ) . '/mobile-app/sc.png',
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/sassly/mobileapps/' ),
            ),
            'marketing-automation' => array(
                'title'        => esc_html__( 'Marketing Automation', 'sassly-essential' ),
                'category'     => [ 'marketing-automation' ],
                'screenshot'   => esc_url( $demo_content_installer ) . '/marketing-automation/sc.png',
                'preview_link' => esc_url( 'https://crowdytheme.com/wp/sassly/marketing-automation/' ),
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
    
    public function success(){
       
        foreach($this->_metas as $key) {
            $value = get_user_meta(1, $key, true);
            update_option( $key, $value );
        }
    }

}

new Wcf_Theme_Demos();




