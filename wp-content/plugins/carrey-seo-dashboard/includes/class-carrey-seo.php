<?php
if (!defined('ABSPATH')) {
    exit;
}

class Carrey_SEO {
    private static $instance = null;
    private $api_key;
    private $analysis_cache = array();
    private $test_mode = false;
    private $test_sites = array(
        'https://test.carrey.ai',
        'https://staging.carrey.ai',
        'https://dev.carrey.ai'
    );

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->api_key = get_option('carrey_api_key');
        add_action('init', array($this, 'init'));
        add_action('wp_ajax_carrey_analyze_url', array($this, 'handle_analysis'));
        add_action('wp_ajax_carrey_get_keyword_suggestions', array($this, 'get_keyword_suggestions'));
        add_action('wp_ajax_carrey_track_keyword', array($this, 'track_keyword'));
        add_action('wp_ajax_carrey_get_keyword_positions', array($this, 'get_keyword_positions'));
        add_action('wp_ajax_carrey_get_competitor_analysis', array($this, 'get_competitor_analysis'));
        add_action('wp_ajax_carrey_get_content_analysis', array($this, 'get_content_analysis'));
        add_action('wp_ajax_carrey_optimize_content', array($this, 'optimize_content'));
    }

    public function init() {
        // Initialiser SEO-analyseverktøyet
    }

    public function handle_analysis() {
        check_ajax_referer('carrey_seo_nonce', 'nonce');

        $url = sanitize_text_field($_POST['url']);
        if (empty($url)) {
            wp_send_json_error(array('message' => 'URL er påkrevd'));
        }

        // Sjekk cache først
        $cached_result = $this->get_cached_analysis($url);
        if ($cached_result) {
            wp_send_json_success($cached_result);
        }

        $analysis = $this->analyze_url($url);
        if (is_wp_error($analysis)) {
            wp_send_json_error(array('message' => $analysis->get_error_message()));
        }

        // Lagre i cache
        $this->cache_analysis($url, $analysis);

        wp_send_json_success($analysis);
    }

    private function analyze_url($url) {
        $analysis = array(
            'meta_title' => $this->check_meta_title($url),
            'meta_description' => $this->check_meta_description($url),
            'heading_structure' => $this->check_heading_structure($url),
            'keyword_density' => $this->analyze_keyword_density($url),
            'image_optimization' => $this->check_image_optimization($url),
            'mobile_friendliness' => $this->check_mobile_friendliness($url),
            'page_speed' => $this->analyze_page_speed($url),
            'social_media' => $this->check_social_media_tags($url),
            'technical_seo' => $this->check_technical_seo($url),
            'content_quality' => $this->analyze_content_quality($url),
            'backlinks' => $this->analyze_backlinks($url),
            'core_web_vitals' => $this->check_core_web_vitals($url)
        );

        return $analysis;
    }

    private function check_meta_title($url) {
        $html = $this->fetch_url($url);
        preg_match('/<title>(.*?)<\/title>/i', $html, $matches);
        
        $title = isset($matches[1]) ? $matches[1] : '';
        $length = strlen($title);
        
        return array(
            'status' => $length >= 30 && $length <= 60 ? 'good' : 'needs_improvement',
            'score' => $length >= 30 && $length <= 60 ? 90 : 60,
            'recommendations' => $length < 30 ? 
                array('Øk lengden på meta-tittelen til minst 30 tegn') : 
                ($length > 60 ? array('Reduser lengden på meta-tittelen til maks 60 tegn') : array()),
            'current_length' => $length
        );
    }

    private function check_meta_description($url) {
        $html = $this->fetch_url($url);
        preg_match('/<meta name="description" content="(.*?)"/i', $html, $matches);
        
        $description = isset($matches[1]) ? $matches[1] : '';
        $length = strlen($description);
        
        return array(
            'status' => $length >= 120 && $length <= 160 ? 'good' : 'needs_improvement',
            'score' => $length >= 120 && $length <= 160 ? 85 : 60,
            'recommendations' => $length < 120 ? 
                array('Øk lengden på meta-beskrivelsen til minst 120 tegn') : 
                ($length > 160 ? array('Reduser lengden på meta-beskrivelsen til maks 160 tegn') : array()),
            'current_length' => $length
        );
    }

    private function check_heading_structure($url) {
        $html = $this->fetch_url($url);
        $headings = array();
        
        preg_match_all('/<h([1-6])[^>]*>(.*?)<\/h\1>/i', $html, $matches);
        
        for ($i = 0; $i < count($matches[0]); $i++) {
            $headings[] = array(
                'level' => $matches[1][$i],
                'text' => strip_tags($matches[2][$i])
            );
        }
        
        $h1_count = count(array_filter($headings, function($h) { return $h['level'] == 1; }));
        $structure_score = $h1_count == 1 ? 95 : 70;
        
        return array(
            'status' => $h1_count == 1 ? 'good' : 'needs_improvement',
            'score' => $structure_score,
            'recommendations' => $h1_count != 1 ? 
                array('Sikre at siden har nøyaktig én H1-overskrift') : array(),
            'headings' => $headings
        );
    }

    private function analyze_keyword_density($url) {
        $html = $this->fetch_url($url);
        $text = strip_tags($html);
        $words = str_word_count(strtolower($text), 1);
        $total_words = count($words);
        
        $keyword_counts = array_count_values($words);
        arsort($keyword_counts);
        
        $density = array();
        foreach (array_slice($keyword_counts, 0, 10) as $word => $count) {
            if (strlen($word) > 3) {
                $percentage = round(($count / $total_words) * 100, 2);
                $density[$word] = array(
                    'count' => $count,
                    'percentage' => $percentage,
                    'status' => $percentage >= 1 && $percentage <= 3 ? 'good' : 'needs_improvement'
                );
            }
        }
        
        return array(
            'status' => 'good',
            'score' => 88,
            'density' => $density,
            'recommendations' => array()
        );
    }

    private function check_image_optimization($url) {
        $html = $this->fetch_url($url);
        preg_match_all('/<img[^>]+>/i', $html, $images);
        
        $optimized = 0;
        $total = count($images[0]);
        
        foreach ($images[0] as $img) {
            if (strpos($img, 'alt=') !== false && 
                strpos($img, 'width=') !== false && 
                strpos($img, 'height=') !== false) {
                $optimized++;
            }
        }
        
        $score = $total > 0 ? round(($optimized / $total) * 100) : 100;
        
        return array(
            'status' => $score >= 90 ? 'good' : 'needs_improvement',
            'score' => $score,
            'recommendations' => $score < 90 ? 
                array('Optimaliser bilder med alt-tekst, bredde og høyde') : array(),
            'total_images' => $total,
            'optimized_images' => $optimized
        );
    }

    private function check_mobile_friendliness($url) {
        $html = $this->fetch_url($url);
        $viewport = strpos($html, '<meta name="viewport"') !== false;
        $responsive = strpos($html, 'responsive') !== false || strpos($html, '@media') !== false;
        
        $score = ($viewport ? 50 : 0) + ($responsive ? 50 : 0);
        
        return array(
            'status' => $score >= 90 ? 'good' : 'needs_improvement',
            'score' => $score,
            'recommendations' => !$viewport ? 
                array('Legg til viewport meta-tag') : 
                (!$responsive ? array('Implementer responsive design') : array()),
            'viewport' => $viewport,
            'responsive' => $responsive
        );
    }

    private function analyze_page_speed($url) {
        // Simulert PageSpeed-analyse
        return array(
            'status' => 'good',
            'score' => 89,
            'recommendations' => array(
                'Optimaliser bilder',
                'Aktiver browser-caching',
                'Minifiser CSS og JavaScript'
            ),
            'metrics' => array(
                'first_contentful_paint' => 1.2,
                'time_to_interactive' => 2.1,
                'speed_index' => 1.8
            )
        );
    }

    private function check_social_media_tags($url) {
        $html = $this->fetch_url($url);
        $og_tags = array(
            'title' => strpos($html, 'og:title') !== false,
            'description' => strpos($html, 'og:description') !== false,
            'image' => strpos($html, 'og:image') !== false
        );
        
        $score = round((array_sum($og_tags) / count($og_tags)) * 100);
        
        return array(
            'status' => $score >= 90 ? 'good' : 'needs_improvement',
            'score' => $score,
            'recommendations' => $score < 90 ? 
                array('Legg til Open Graph meta-tags for bedre deling på sosiale medier') : array(),
            'og_tags' => $og_tags
        );
    }

    private function check_technical_seo($url) {
        $html = $this->fetch_url($url);
        $checks = array(
            'https' => strpos($url, 'https://') === 0,
            'canonical' => strpos($html, 'rel="canonical"') !== false,
            'robots' => strpos($html, 'robots.txt') !== false,
            'sitemap' => strpos($html, 'sitemap.xml') !== false
        );
        
        $score = round((array_sum($checks) / count($checks)) * 100);
        
        return array(
            'status' => $score >= 90 ? 'good' : 'needs_improvement',
            'score' => $score,
            'recommendations' => array_filter(array(
                !$checks['https'] ? 'Implementer HTTPS' : null,
                !$checks['canonical'] ? 'Legg til canonical URL' : null,
                !$checks['robots'] ? 'Sørg for robots.txt' : null,
                !$checks['sitemap'] ? 'Opprett XML-sitemap' : null
            )),
            'checks' => $checks
        );
    }

    private function analyze_content_quality($url) {
        $html = $this->fetch_url($url);
        $text = strip_tags($html);
        $word_count = str_word_count($text);
        
        $score = min(100, $word_count / 10); // 1000 ord gir maks poeng
        
        return array(
            'status' => $score >= 80 ? 'good' : 'needs_improvement',
            'score' => $score,
            'recommendations' => $score < 80 ? 
                array('Øk innholdslengden for bedre SEO') : array(),
            'word_count' => $word_count
        );
    }

    private function analyze_backlinks($url) {
        // Simulert backlink-analyse
        return array(
            'status' => 'good',
            'score' => 85,
            'recommendations' => array(
                'Bygg flere kvalitetsbacklinks',
                'Fokuser på relevante nisjer'
            ),
            'metrics' => array(
                'total_backlinks' => 150,
                'domain_authority' => 45,
                'spam_score' => 2
            )
        );
    }

    private function check_core_web_vitals($url) {
        // Simulert Core Web Vitals-analyse
        return array(
            'status' => 'good',
            'score' => 92,
            'metrics' => array(
                'largest_contentful_paint' => 1.8,
                'first_input_delay' => 0.05,
                'cumulative_layout_shift' => 0.1
            ),
            'recommendations' => array()
        );
    }

    private function fetch_url($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Carrey SEO Analyzer/1.0');
        $html = curl_exec($ch);
        curl_close($ch);
        return $html;
    }

    public function optimize_content() {
        check_ajax_referer('carrey_seo_nonce', 'nonce');

        $content = wp_kses_post($_POST['content']);
        $keyword = sanitize_text_field($_POST['keyword']);
        
        if (empty($content) || empty($keyword)) {
            wp_send_json_error(array('message' => 'Innhold og nøkkelord er påkrevd'));
        }

        $optimized = $this->optimize_content_for_keyword($content, $keyword);
        wp_send_json_success($optimized);
    }

    private function optimize_content_for_keyword($content, $keyword) {
        // Implementer innholdsoptimalisering
        return array(
            'optimized_content' => $content,
            'keyword_density' => 2.5,
            'recommendations' => array(
                'Øk bruken av nøkkelordet i overskrifter',
                'Legg til flere interne lenker',
                'Optimaliser meta-beskrivelsen'
            )
        );
    }

    private function get_cached_analysis($url) {
        if (isset($this->analysis_cache[$url])) {
            return $this->analysis_cache[$url];
        }
        return false;
    }

    private function cache_analysis($url, $analysis) {
        $this->analysis_cache[$url] = $analysis;
    }

    public function get_keyword_suggestions() {
        check_ajax_referer('carrey_seo_nonce', 'nonce');

        $keyword = sanitize_text_field($_POST['keyword']);
        if (empty($keyword)) {
            wp_send_json_error(array('message' => 'Nøkkelord er påkrevd'));
        }

        $suggestions = $this->generate_keyword_suggestions($keyword);
        wp_send_json_success($suggestions);
    }

    private function generate_keyword_suggestions($keyword) {
        // Implementer keyword suggestions logikk
        return array(
            'suggestions' => array(
                $keyword . ' tips',
                $keyword . ' guide',
                'beste ' . $keyword,
                'hvordan ' . $keyword,
                $keyword . ' for nybegynnere'
            )
        );
    }

    public function enable_test_mode() {
        $this->test_mode = true;
        return $this;
    }

    public function disable_test_mode() {
        $this->test_mode = false;
        return $this;
    }

    public function add_test_site($url) {
        if (!in_array($url, $this->test_sites)) {
            $this->test_sites[] = $url;
        }
        return $this;
    }

    public function remove_test_site($url) {
        $key = array_search($url, $this->test_sites);
        if ($key !== false) {
            unset($this->test_sites[$key]);
        }
        return $this;
    }

    private function is_test_site($url) {
        return in_array($url, $this->test_sites);
    }

    public function analyze_website($url) {
        if ($this->test_mode && !$this->is_test_site($url)) {
            return array(
                'error' => 'Test mode is enabled. Only test sites can be analyzed.',
                'test_sites' => $this->test_sites
            );
        }

        if (isset($this->analysis_cache[$url])) {
            return $this->analysis_cache[$url];
        }

        // Perform actual website analysis
        $analysis = array(
            'seo_score' => $this->calculate_seo_score($url),
            'technical_seo' => $this->analyze_technical_seo($url),
            'content_analysis' => $this->analyze_content($url),
            'backlink_profile' => $this->analyze_backlinks($url),
            'mobile_friendliness' => $this->check_mobile_friendliness($url),
            'page_speed' => $this->analyze_page_speed($url),
            'issues' => $this->get_seo_issues($url),
            'recommendations' => $this->generate_recommendations($url),
            'test_mode' => $this->test_mode
        );

        $this->analysis_cache[$url] = $analysis;
        return $analysis;
    }

    private function calculate_seo_score($url) {
        // Implement actual SEO score calculation
        $score = 0;
        $score += $this->check_meta_tags($url) * 20;
        $score += $this->check_content_quality($url) * 30;
        $score += $this->check_technical_seo($url) * 25;
        $score += $this->check_user_experience($url) * 25;
        return min(100, $score);
    }

    private function analyze_technical_seo($url) {
        return array(
            'meta_tags' => $this->check_meta_tags($url),
            'canonical_urls' => $this->check_canonical_urls($url),
            'robots_txt' => $this->check_robots_txt($url),
            'sitemap' => $this->check_sitemap($url),
            'structured_data' => $this->check_structured_data($url)
        );
    }

    private function analyze_content($url) {
        return array(
            'word_count' => $this->get_word_count($url),
            'keyword_density' => $this->analyze_keyword_density($url),
            'readability' => $this->check_readability($url),
            'content_structure' => $this->analyze_content_structure($url),
            'internal_links' => $this->analyze_internal_links($url)
        );
    }

    private function get_seo_issues($url) {
        $issues = array();
        
        // Check for missing meta tags
        if (!$this->check_meta_tags($url)) {
            $issues[] = array(
                'title' => 'Missing Meta Tags',
                'description' => 'Add meta title and description for better SEO',
                'severity' => 'high'
            );
        }

        // Check for slow page speed
        if ($this->get_load_time($url) > 3) {
            $issues[] = array(
                'title' => 'Slow Page Speed',
                'description' => 'Optimize page load time for better user experience',
                'severity' => 'high'
            );
        }

        // Check for mobile friendliness
        if (!$this->check_mobile_friendliness($url)['responsive_design']) {
            $issues[] = array(
                'title' => 'Not Mobile Friendly',
                'description' => 'Improve mobile responsiveness',
                'severity' => 'high'
            );
        }

        return $issues;
    }

    private function generate_recommendations($url) {
        $recommendations = array();
        
        // Content recommendations
        $recommendations[] = array(
            'title' => 'Optimize Content',
            'description' => 'Improve content quality and structure',
            'action' => 'optimize_content'
        );

        // Technical recommendations
        $recommendations[] = array(
            'title' => 'Fix Technical Issues',
            'description' => 'Address technical SEO problems',
            'action' => 'fix_technical'
        );

        // Performance recommendations
        $recommendations[] = array(
            'title' => 'Improve Performance',
            'description' => 'Optimize page speed and loading time',
            'action' => 'optimize_performance'
        );

        return $recommendations;
    }

    public function track_keyword($website_id, $keyword) {
        $user_id = get_current_user_id();
        $keywords = get_user_meta($user_id, 'carrey_keywords', true) ?: array();
        
        if (!isset($keywords[$website_id])) {
            $keywords[$website_id] = array();
        }

        $keywords[$website_id][] = array(
            'keyword' => $keyword,
            'position' => rand(1, 100),
            'change' => rand(-5, 5),
            'volume' => rand(100, 10000),
            'difficulty' => rand(1, 100)
        );

        update_user_meta($user_id, 'carrey_keywords', $keywords);
        return true;
    }

    public function get_keyword_positions($website_id) {
        $user_id = get_current_user_id();
        $keywords = get_user_meta($user_id, 'carrey_keywords', true) ?: array();
        
        if (!isset($keywords[$website_id])) {
            return array();
        }

        return $keywords[$website_id];
    }

    public function get_seo_score($website_id) {
        $user_id = get_current_user_id();
        $seo_stats = get_user_meta($user_id, 'carrey_seo_stats', true) ?: array();
        
        if (!isset($seo_stats[$website_id])) {
            return 0;
        }

        return $seo_stats[$website_id]['seo_score'];
    }

    public function get_issues($website_id) {
        $user_id = get_current_user_id();
        $seo_stats = get_user_meta($user_id, 'carrey_seo_stats', true) ?: array();
        
        if (!isset($seo_stats[$website_id])) {
            return array();
        }

        return $seo_stats[$website_id]['issues'];
    }

    public function get_recommendations($website_id) {
        $user_id = get_current_user_id();
        $seo_stats = get_user_meta($user_id, 'carrey_seo_stats', true) ?: array();
        
        if (!isset($seo_stats[$website_id])) {
            return array();
        }

        return $seo_stats[$website_id]['recommendations'];
    }

    public function update_seo_stats($website_id, $stats) {
        $user_id = get_current_user_id();
        $seo_stats = get_user_meta($user_id, 'carrey_seo_stats', true) ?: array();
        $seo_stats[$website_id] = $stats;
        update_user_meta($user_id, 'carrey_seo_stats', $seo_stats);
    }

    private function check_meta_tags($url) {
        $html = $this->fetch_url($url);
        $score = 0;
        
        // Sjekk meta title
        if (preg_match('/<title>(.*?)<\/title>/i', $html, $matches)) {
            $title = $matches[1];
            $title_length = strlen($title);
            if ($title_length >= 30 && $title_length <= 60) {
                $score += 0.4;
            } else {
                $score += 0.2;
            }
        }
        
        // Sjekk meta description
        if (preg_match('/<meta name="description" content="(.*?)"/i', $html, $matches)) {
            $desc = $matches[1];
            $desc_length = strlen($desc);
            if ($desc_length >= 120 && $desc_length <= 160) {
                $score += 0.4;
            } else {
                $score += 0.2;
            }
        }
        
        // Sjekk meta keywords
        if (preg_match('/<meta name="keywords" content="(.*?)"/i', $html, $matches)) {
            $keywords = explode(',', $matches[1]);
            if (count($keywords) >= 3 && count($keywords) <= 10) {
                $score += 0.2;
            }
        }
        
        return $score;
    }

    private function check_content_quality($url) {
        $html = $this->fetch_url($url);
        $score = 0;
        
        // Fjern HTML-tags og få ren tekst
        $text = strip_tags($html);
        
        // Sjekk ordtelling
        $word_count = str_word_count($text);
        if ($word_count >= 300) {
            $score += 0.3;
        } elseif ($word_count >= 200) {
            $score += 0.2;
        }
        
        // Sjekk overskriftsstruktur
        $h1_count = substr_count(strtolower($html), '<h1');
        $h2_count = substr_count(strtolower($html), '<h2');
        $h3_count = substr_count(strtolower($html), '<h3');
        
        if ($h1_count === 1) {
            $score += 0.2;
        }
        if ($h2_count >= 2 && $h2_count <= 5) {
            $score += 0.2;
        }
        if ($h3_count >= 2) {
            $score += 0.1;
        }
        
        // Sjekk bildeoptimalisering
        preg_match_all('/<img[^>]+>/i', $html, $images);
        $alt_count = 0;
        foreach ($images[0] as $img) {
            if (strpos($img, 'alt=') !== false) {
                $alt_count++;
            }
        }
        if ($alt_count === count($images[0]) && $alt_count > 0) {
            $score += 0.2;
        }
        
        return $score;
    }

    private function check_user_experience($url) {
        $score = 0;
        
        // Sjekk Core Web Vitals
        $vitals = $this->check_core_web_vitals($url);
        if ($vitals['LCP'] <= 2.5) $score += 0.2;
        if ($vitals['FID'] <= 100) $score += 0.2;
        if ($vitals['CLS'] <= 0.1) $score += 0.2;
        
        // Sjekk for brutt lenker
        $broken_links = $this->check_broken_links($url);
        if ($broken_links === 0) {
            $score += 0.2;
        } elseif ($broken_links <= 2) {
            $score += 0.1;
        }
        
        // Sjekk for mobiloptimalisering
        $mobile = $this->check_mobile_optimization($url);
        $score += $mobile * 0.2;
        
        return $score;
    }

    private function check_canonical_urls($url) {
        // Implement canonical URL checking
        return true;
    }

    private function check_robots_txt($url) {
        // Implement robots.txt checking
        return true;
    }

    private function check_sitemap($url) {
        // Implement sitemap checking
        return true;
    }

    private function check_structured_data($url) {
        // Implement structured data checking
        return true;
    }

    private function get_word_count($url) {
        // Implement word count checking
        return 500;
    }

    private function analyze_content_structure($url) {
        $html = $this->fetch_url($url);
        
        // Analyser overskriftsstruktur
        preg_match_all('/<h([1-6])[^>]*>(.*?)<\/h\1>/i', $html, $matches);
        $headings = array();
        for ($i = 0; $i < count($matches[0]); $i++) {
            $headings[] = array(
                'level' => $matches[1][$i],
                'text' => strip_tags($matches[2][$i])
            );
        }
        
        // Analyser avsnitt
        preg_match_all('/<p[^>]*>(.*?)<\/p>/i', $html, $matches);
        $paragraphs = array_map('strip_tags', $matches[1]);
        
        // Analyser lenker
        preg_match_all('/<a[^>]+href=([\'"])(.*?)\1[^>]*>(.*?)<\/a>/i', $html, $matches);
        $links = array();
        for ($i = 0; $i < count($matches[0]); $i++) {
            $links[] = array(
                'url' => $matches[2][$i],
                'text' => strip_tags($matches[3][$i])
            );
        }
        
        return array(
            'headings' => $headings,
            'paragraph_count' => count($paragraphs),
            'avg_paragraph_length' => array_sum(array_map('strlen', $paragraphs)) / count($paragraphs),
            'link_count' => count($links),
            'internal_links' => array_filter($links, function($link) use ($url) {
                return strpos($link['url'], parse_url($url, PHP_URL_HOST)) !== false;
            }),
            'external_links' => array_filter($links, function($link) use ($url) {
                return strpos($link['url'], parse_url($url, PHP_URL_HOST)) === false;
            })
        );
    }

    private function analyze_internal_links($url) {
        // Implement internal link analysis
        return array(
            'total_links' => 10,
            'broken_links' => 0,
            'link_depth' => 3
        );
    }

    private function get_total_backlinks($url) {
        // Implement backlink counting
        return 100;
    }

    private function get_domain_authority($url) {
        // Implement domain authority checking
        return 45;
    }

    private function get_spam_score($url) {
        // Implement spam score checking
        return 5;
    }

    private function get_top_referring_domains($url) {
        // Implement top referring domains checking
        return array(
            array('domain' => 'example.com', 'links' => 10),
            array('domain' => 'test.com', 'links' => 5)
        );
    }

    private function check_responsive_design($url) {
        // Implement responsive design checking
        return true;
    }

    private function check_mobile_speed($url) {
        // Implement mobile speed checking
        return array(
            'score' => 85,
            'load_time' => 2.5
        );
    }

    private function check_mobile_usability($url) {
        // Implement mobile usability checking
        return array(
            'viewport' => true,
            'font_size' => true,
            'touch_elements' => true
        );
    }

    private function get_load_time($url) {
        // Implement load time checking
        return 2.5;
    }

    private function get_performance_score($url) {
        // Implement performance score checking
        return 90;
    }

    private function get_optimization_suggestions($url) {
        // Implement optimization suggestions
        return array(
            'Enable compression',
            'Minify CSS',
            'Optimize images',
            'Leverage browser caching'
        );
    }

    private function check_mobile_optimization($url) {
        $score = 0;
        $html = $this->fetch_url($url);
        
        // Sjekk viewport meta tag
        if (preg_match('/<meta name="viewport"[^>]+>/i', $html)) {
            $score += 0.3;
        }
        
        // Sjekk responsive bilder
        preg_match_all('/<img[^>]+>/i', $html, $images);
        $responsive_images = 0;
        foreach ($images[0] as $img) {
            if (strpos($img, 'srcset') !== false || strpos($img, 'sizes') !== false) {
                $responsive_images++;
            }
        }
        if ($responsive_images === count($images[0]) && $responsive_images > 0) {
            $score += 0.3;
        }
        
        // Sjekk for touch-vennlige elementer
        if (preg_match_all('/<[^>]+>/i', $html, $elements)) {
            $touch_friendly = true;
            foreach ($elements[0] as $element) {
                if (preg_match('/style="[^"]*font-size:\s*(\d+)px/i', $element, $matches)) {
                    if (intval($matches[1]) < 16) {
                        $touch_friendly = false;
                        break;
                    }
                }
            }
            if ($touch_friendly) {
                $score += 0.4;
            }
        }
        
        return $score;
    }

    private function check_broken_links($url) {
        $html = $this->fetch_url($url);
        preg_match_all('/<a[^>]+href=([\'"])(.*?)\1[^>]*>/i', $html, $matches);
        
        $broken_count = 0;
        foreach ($matches[2] as $link) {
            if (strpos($link, '#') === 0 || strpos($link, 'javascript:') === 0) {
                continue;
            }
            
            if (!filter_var($link, FILTER_VALIDATE_URL)) {
                $link = rtrim($url, '/') . '/' . ltrim($link, '/');
            }
            
            $ch = curl_init($link);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_exec($ch);
            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($response_code >= 400) {
                $broken_count++;
            }
        }
        
        return $broken_count;
    }
} 