<?php
/** Generated editorial photographs, with user-selected images taking priority. */
function rzz_editorial_image($key,$size=1600){
    $catalog=rzz_photo_catalog();$name=$catalog[$key]['file']??sanitize_file_name($key);
    $file='images/editorial/'.$name.'-'.($size===800?800:1600).'.webp';
    if(is_file(get_template_directory().'/assets/'.$file))return rzz_asset($file);
    $fallback='images/editorial/rzz-law-about-legal-practice-kosovo-'.($size===800?800:1600).'.webp';
    return is_file(get_template_directory().'/assets/'.$fallback)?rzz_asset($fallback):'';
}
function rzz_practice_image($post,$size=1600){
    $post=get_post($post);if(!$post)return '';
    if(has_post_thumbnail($post))return get_the_post_thumbnail_url($post,$size===800?'medium_large':'large');
    return rzz_setting('expertise_'.$post->post_name.'_image',rzz_editorial_image($post->post_name,$size));
}
function rzz_banner_image($size=1600){
    if(is_front_page()||is_singular('team'))return '';
    if(is_singular('expertise'))return rzz_practice_image(get_queried_object_id(),$size);
    if(is_singular('post'))return rzz_article_image(get_queried_object_id(),$size);
    if(is_post_type_archive('team'))return rzz_setting('team_banner_image',rzz_editorial_image('careers-office',$size));
    if(is_page('about'))return rzz_setting('about_image',rzz_editorial_image('about-office',$size));
    if(is_page('careers'))return rzz_setting('careers_image',rzz_editorial_image('careers-office',$size));
    if(is_page('contact'))return rzz_setting('contact_image',rzz_editorial_image('contact-boardroom',$size));
    if(is_post_type_archive('expertise'))return rzz_setting('expertise_archive_image',rzz_editorial_image('about-office',$size));
    if(is_home()||is_category()||is_tag()||is_author()||is_date())return rzz_setting('insights_banner_image',rzz_editorial_image('legislative-consulting',$size));
    return rzz_setting('general_banner_image',rzz_editorial_image('about-office',$size));
}
function rzz_expertise_image_controls(){
    $fields=['expertise_archive_image'=>['Expertise overview banner',rzz_editorial_image('about-office'),'image']];
    foreach(rzz_areas() as $slug=>$area)$fields['expertise_'.$slug.'_image']=[$area[0].' banner',rzz_editorial_image($slug),'image'];
    return $fields;
}
add_action('wp_head',function(){
    $image=rzz_banner_image();if(!$image)return;
    echo '<link rel="preload" as="image" href="'.esc_url($image).'" media="(min-width:601px)" fetchpriority="high">';
    echo '<link rel="preload" as="image" href="'.esc_url(rzz_banner_image(800)).'" media="(max-width:600px)" fetchpriority="high">';
},1);
add_action('admin_init',function(){
    if(!current_user_can('manage_options')||get_stylesheet()!=='rzzlaw'||get_option('rzz_editorial_images_version')==='1.2.2')return;
    foreach(get_theme_mods()?:[] as $key=>$value){
        if(in_array($key,['rzz_hero_image','rzz_hero_image_2'],true)||!is_string($value))continue;
        if(preg_match('~/themes/rzzlaw/assets/images/(?:hero2\.jpg|hero1-(?:800|1600)\.webp)$~',$value))remove_theme_mod($key);
    }
    update_option('rzz_editorial_images_version','1.2.2',false);
    wp_cache_flush();do_action('litespeed_purge_all');
});

function rzz_article_image($post,$size=800){
    $post=get_post($post);if(!$post)return '';
    if(has_post_thumbnail($post))return get_the_post_thumbnail_url($post,$size===800?'medium_large':'large');
    foreach(rzz_lines(rzz_meta('areas',$post->ID)) as $slug){
        $practice=get_page_by_path(sanitize_title($slug),OBJECT,'expertise');
        if($practice)return rzz_practice_image($practice,$size);
    }
    return rzz_editorial_image('legislative-consulting',$size);
}
