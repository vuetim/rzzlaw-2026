<?php
/** Native metadata is a fallback; SEO plugins own their output. */
function rzz_has_seo(){return defined('WPSEO_VERSION')||defined('RANK_MATH_VERSION')||defined('AIOSEO_VERSION')||defined('SEOPRESS_VERSION');}
function rzz_canonical_url(){
    if(is_singular())return wp_get_canonical_url()?:get_permalink();
    if(is_front_page())return home_url('/');
    return get_pagenum_link(max(1,(int)get_query_var('paged')),false);
}
function rzz_is_preview_site(){
    $environment=wp_get_environment_type();
    $host=(string)wp_parse_url(home_url('/'),PHP_URL_HOST);
    $path=(string)wp_parse_url(home_url('/'),PHP_URL_PATH);
    $preview=in_array($environment,['local','development','staging'],true)
        ||in_array($host,['localhost','127.0.0.1'],true)||substr($host,-6)==='.local'
        ||($host==='eza.al'&&preg_match('~^/rzzdemo(?:/|$)~',$path));
    return (bool)apply_filters('rzz_is_preview_site',$preview);
}
add_action('send_headers',function(){if(rzz_is_preview_site())header('X-Robots-Tag: noindex, nofollow',true);});
add_filter('wp_robots',function($robots){
    if(is_search()||is_404()||rzz_is_preview_site()){unset($robots['index']);$robots['noindex']=true;}
    return $robots;
});
function rzz_seo_description(){
    $description='';
    if(is_front_page())$description=rzz_setting('hero_text','Legal advice and representation for businesses, institutions and individuals in Kosovo.');
    if(is_singular())$description=rzz_meta('meta_description')?:($description?:wp_strip_all_tags(get_the_excerpt(get_queried_object_id())));
    if(is_post_type_archive('expertise'))$description='Explore RZZ LAW’s eight practice areas for businesses, institutions and individuals in Kosovo.';
    if(is_post_type_archive('team'))$description='Meet the lawyers at RZZ LAW. View their biographies, practice areas and contact details.';
    if(is_home())$description='Legal perspectives, analysis and updates from RZZ LAW.';
    if(is_category()||is_tag()||is_tax())$description=wp_strip_all_tags(term_description())?:rzz_page_label().' — legal perspectives and updates from RZZ LAW.';
    if(!$description){
        $defaults=[
            'contact'=>'Contact RZZ LAW in Pristina, Kosovo, to discuss your legal matter. Find our address, contact details and office hours.',
            'about'=>'Discover the story, values and approach behind RZZ LAW, a law firm based in Pristina, Kosovo.',
            'careers'=>'Explore career enquiries at RZZ LAW in Pristina, Kosovo. Connect with a team committed to integrity and collaboration.',
            'privacy-policy'=>'Read how RZZ LAW handles personal information, contact enquiries and optional website services.',
            'terms-and-conditions'=>'Read the terms governing the use of the RZZ LAW website and its general legal information.',
        ];
        $slug=is_singular()?get_post_field('post_name',get_queried_object_id()):'';
        $description=$defaults[$slug]??(rzz_page_label().' — RZZ LAW, Pristina, Kosovo.');
    }
    return wp_trim_words(wp_strip_all_tags(strip_shortcodes($description)),35,'');
}
add_filter('pre_get_document_title',function($title){
    if(rzz_has_seo())return $title;
    if(is_singular()&&rzz_meta('meta_title'))return rzz_meta('meta_title');
    if(is_front_page())return 'RZZ LAW | Legal Advice & Representation in Kosovo';
    return $title;
});
add_action('wp_head',function(){
    if(rzz_has_seo()||is_search()||is_404()||is_feed())return;
    $description=rzz_seo_description();$url=rzz_canonical_url();$title=wp_get_document_title();
    echo '<meta name="description" content="'.esc_attr($description).'">';
    // WordPress core already supplies the singular canonical.
    if(!is_singular())echo '<link rel="canonical" href="'.esc_url($url).'">';
    echo '<meta property="og:type" content="'.(is_singular('post')?'article':'website').'"><meta property="og:site_name" content="RZZ LAW"><meta property="og:title" content="'.esc_attr($title).'"><meta property="og:description" content="'.esc_attr($description).'"><meta property="og:url" content="'.esc_url($url).'"><meta name="twitter:card" content="summary_large_image">';
    $image=rzz_banner_image()?:((is_singular()&&has_post_thumbnail())?get_the_post_thumbnail_url(null,'large'):rzz_setting('hero_image',rzz_asset('images/hero1-1600.webp')));
    if($image)echo '<meta property="og:image" content="'.esc_url($image).'"><meta property="og:image:alt" content="'.esc_attr(rzz_current_image_details()['alt']).'">';
    $firm_id=home_url('/#firm');$website_id=home_url('/#website');
    $firm=['@type'=>'LegalService','@id'=>$firm_id,'name'=>'RZZ LAW','url'=>home_url('/'),'areaServed'=>['@type'=>'Country','name'=>'Kosovo']];
    foreach(['address','phone'=>'telephone','email'] as $key=>$value){$setting=is_int($key)?$value:$key;if(rzz_setting($setting))$firm[$value]=rzz_setting($setting);}
    if(rzz_setting('linkedin'))$firm['sameAs']=[rzz_setting('linkedin')];
    if(rzz_setting('availability','Monday to Friday, 08:00 - 16:00')==='Monday to Friday, 08:00 - 16:00')$firm['openingHoursSpecification']=[['@type'=>'OpeningHoursSpecification','dayOfWeek'=>['Monday','Tuesday','Wednesday','Thursday','Friday'],'opens'=>'08:00','closes'=>'16:00']];
    $graph=[$firm,['@type'=>'WebSite','@id'=>$website_id,'url'=>home_url('/'),'name'=>'RZZ LAW','publisher'=>['@id'=>$firm_id]]];
    $webpage=['@type'=>'WebPage','@id'=>$url.'#webpage','url'=>$url,'name'=>$title,'description'=>$description,'isPartOf'=>['@id'=>$website_id],'inLanguage'=>get_bloginfo('language')];
    if($image){$photo=rzz_current_image_details();$graph[]=['@type'=>'ImageObject','@id'=>$image.'#image','contentUrl'=>$image,'name'=>$photo['title'],'caption'=>$photo['alt'],'description'=>$photo['description']];$webpage['primaryImageOfPage']=['@id'=>$image.'#image'];}
    if(is_singular('expertise'))$graph[]=['@type'=>'Service','name'=>get_the_title(),'description'=>$description,'url'=>$url,'provider'=>['@id'=>$firm_id]];
    if(is_singular('team')){
        $person=['@type'=>'Person','name'=>get_the_title(),'url'=>$url,'worksFor'=>['@id'=>$firm_id]];
        if(rzz_meta('role'))$person['jobTitle']=rzz_meta('role');if($image)$person['image']=$image;$graph[]=$person;
    }
    if(is_singular('post')){
        $author=rzz_meta('author_name')?:get_the_author_meta('display_name',get_post_field('post_author',get_the_ID()));
        $article=['@type'=>'Article','headline'=>get_the_title(),'datePublished'=>get_the_date(DATE_W3C),'dateModified'=>get_the_modified_date(DATE_W3C),'author'=>['@type'=>$author==='RZZ LAW'?'Organization':'Person','name'=>$author],'publisher'=>['@id'=>$firm_id],'mainEntityOfPage'=>['@id'=>$url.'#webpage']];
        if($author==='RZZ LAW')$article['author']['url']=home_url('/');if($image)$article['image']=[$image];$graph[]=$article;
    }
    if(!is_front_page()){
        $items=[];foreach(rzz_breadcrumb_items() as $item)$items[]=['@type'=>'ListItem','position'=>count($items)+1,'name'=>$item['name'],'item'=>$item['url']];
        $graph[]=['@type'=>'BreadcrumbList','@id'=>$url.'#breadcrumb','itemListElement'=>$items];$webpage['breadcrumb']=['@id'=>$url.'#breadcrumb'];
    }
    $graph[]=$webpage;
    echo '<script type="application/ld+json">'.wp_json_encode(['@context'=>'https://schema.org','@graph'=>$graph],JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT).'</script>';
},5);
