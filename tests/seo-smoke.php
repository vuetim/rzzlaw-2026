<?php
if(PHP_SAPI!=='cli')exit;
$hooks=[];$context=[];$checks=0;
function add_action($name,$cb,$priority=10){global $hooks;$hooks[$name][]=$cb;}
function add_filter($name,$cb,$priority=10){add_action($name,$cb,$priority);}
function apply_filters($name,$value){return $value;}
function is_singular($types=null){global $context;return !empty($context['singular'])&&(!$types||in_array($context['singular'],(array)$types,true));}
function is_front_page(){global $context;return !empty($context['front']);}
function is_home(){global $context;return !empty($context['home']);}
function is_search(){global $context;return !empty($context['search']);}
function is_404(){global $context;return !empty($context['404']);}
function is_feed(){return false;}
function is_post_type_archive($type){global $context;return ($context['archive']??'')===$type;}
function is_archive(){global $context;return !empty($context['archive'])||!empty($context['category']);}
function is_category(){global $context;return !empty($context['category']);}
function is_tag(){return false;}function is_tax(){return false;}
function home_url($path='/'){global $context;return ($context['base']??'https://rzzlaw.com').$path;}
function wp_get_environment_type(){global $context;return $context['env']??'production';}
function wp_parse_url($url,$component){return parse_url($url,$component);}
function get_query_var($key){global $context;return $context[$key]??0;}
function get_pagenum_link($page=1,$escape=true){return home_url('/insights/'.($page>1?'page/'.$page.'/':''));}
function wp_get_canonical_url(){global $context;return $context['canonical']??get_permalink();}
function get_permalink($id=null){return home_url('/test/');}
function get_option($key){return 12;}
function get_post_type(){global $context;return $context['singular']??'';}
function get_post_type_archive_link($type){return home_url('/'.$type.'/');}
function get_the_archive_title(){return 'Category: Legal Updates';}
function get_queried_object_id(){return 10;}
function get_the_ID(){return 10;}
function get_the_title($id=null){return 'Test & Legal';}
function get_post_field($key,$id=null){global $context;return $key==='post_name'?($context['slug']??'test'):1;}
function get_the_excerpt($id=null){global $context;return $context['excerpt']??'';}
function term_description(){return '';}
function wp_strip_all_tags($text){return strip_tags($text);}
function strip_shortcodes($text){return $text;}
function wp_trim_words($text,$number,$more){return implode(' ',array_slice(explode(' ',$text),0,$number));}
function esc_attr($value){return htmlspecialchars($value,ENT_QUOTES);}
function esc_html($value){return esc_attr($value);}
function esc_url($value){return esc_attr($value);}
function rzz_meta($key){return '';}
function rzz_setting($key,$fallback=''){return $fallback;}
function rzz_asset($path){return home_url('/assets/'.$path);}
function rzz_link($path){return home_url('/'.$path.'/');}
function rzz_banner_image(){return '';}
function has_post_thumbnail(){return false;}
function rzz_current_image_details(){return ['title'=>'Office','alt'=>'Law office','description'=>'Law office in Pristina'];}
function wp_get_document_title(){return 'Test | RZZ LAW';}
function get_bloginfo($key){return 'en-US';}
function get_the_author_meta($key,$id){return 'RZZ LAW';}
function get_the_date($format){return '2026-11-09T12:00:00+00:00';}
function get_the_modified_date($format){return get_the_date($format);}
function wp_json_encode($value,$options=0){return json_encode($value,$options);}
require __DIR__.'/../inc/seo.php';
$source=file_get_contents(__DIR__.'/../functions.php');
$start=strpos($source,'function rzz_page_label');$end=strpos($source,'function rzz_intro');
eval(substr($source,$start,$end-$start));
function check($condition,$label){global $checks;if(!$condition)throw new Exception($label);$checks++;}
$context=['paged'=>3];check(rzz_canonical_url()==='https://rzzlaw.com/insights/page/3/','Pagination self canonical');
$context=['front'=>true];check(rzz_canonical_url()==='https://rzzlaw.com/','Home canonical');
$context=['singular'=>'post','canonical'=>'https://rzzlaw.com/test/2/'];check(rzz_canonical_url()===$context['canonical'],'Multipage article canonical');
foreach([
 ['base'=>'https://eza.al/rzzdemo','expected'=>true],
 ['base'=>'https://eza.al/rzzdemo/','expected'=>true],
 ['base'=>'https://eza.al/rzzdemo-other','expected'=>false],
 ['base'=>'https://rzzlaw.local','expected'=>true],
 ['base'=>'https://rzzlaw.com','expected'=>false],
 ['base'=>'https://www.rzzlaw.com','expected'=>false],
 ['base'=>'https://rzzlaw.com','env'=>'staging','expected'=>true],
] as $case){$context=$case;check(rzz_is_preview_site()===$case['expected'],'Preview detection '.$case['base']);}
$context=['home'=>true];check(rzz_page_label()==='Insights','Blog label uses queried context');
$context=['category'=>true];check(rzz_page_label()==='Category: Legal Updates','Taxonomy label');
$context=['singular'=>'post'];$crumbs=rzz_breadcrumb_items();check($crumbs[1]['name']==='Insights','Article Insights parent');
$context=['singular'=>'team'];$crumbs=rzz_breadcrumb_items();check($crumbs[1]['name']==='Our team','Profile parent');
ob_start();rzz_breadcrumb();$html=ob_get_clean();$dom=new DOMDocument();@$dom->loadHTML($html);$x=new DOMXPath($dom);
check($x->query('//nav/ol/li')->length===3,'Breadcrumb semantic ordered list');
check($x->query('//*[@aria-current="page"]')->length===1,'One current breadcrumb');
$context=['singular'=>'page','slug'=>'contact'];check(strpos(rzz_seo_description(),'Contact RZZ LAW')===0,'Empty contact description fallback');
$context=['category'=>true];check(strlen(rzz_seo_description())>20,'Empty taxonomy description fallback');
foreach([['front'=>true],['home'=>true],['singular'=>'post'],['singular'=>'team'],['singular'=>'expertise'],['category'=>true]] as $case){
 $context=$case;ob_start();foreach($hooks['wp_head'] as $cb)$cb();$head=ob_get_clean();
 $dom=new DOMDocument();@$dom->loadHTML('<html><head>'.$head.'</head></html>');$x=new DOMXPath($dom);
 check($x->query('//meta[@name="description"]')->length===1,'One native description');
 check($x->query('//link[@rel="canonical"]')->length===(is_singular()?0:1),'Core singular canonical ownership');
 $json=$x->evaluate('string(//script[@type="application/ld+json"])');$data=json_decode($json,true);
 check(json_last_error()===JSON_ERROR_NONE&&count($data['@graph'])>=4,'Valid JSON graph');
 if(is_singular('post')){$article=array_values(array_filter($data['@graph'],fn($n)=>$n['@type']==='Article'))[0];check(isset($article['image'],$article['publisher']),'Article image and publisher');}
}
$context=['base'=>'https://eza.al/rzzdemo'];$robots=['index'=>true];foreach($hooks['wp_robots'] as $cb)$robots=$cb($robots);check(isset($robots['noindex'])&&!isset($robots['index']),'Preview noindex');
$context=['base'=>'https://rzzlaw.com'];$robots=[];foreach($hooks['wp_robots'] as $cb)$robots=$cb($robots);check(!isset($robots['noindex']),'Production not noindexed by theme');
define('WPSEO_VERSION','test');ob_start();foreach($hooks['wp_head'] as $cb)$cb();$head=ob_get_clean();check($head==='','Yoast owns all SEO head metadata');
foreach($hooks['pre_get_document_title'] as $cb)check($cb('Yoast title')==='Yoast title','Yoast title retained');
echo 'PASS '.$checks." SEO regression checks\n";
