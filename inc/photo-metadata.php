<?php
/** Image publishing metadata and one-time Media Library registration. */
function rzz_image_details($key,$url='') {
    $catalog=rzz_photo_catalog();
    $details=$catalog[$key]??['title'=>'RZZ LAW','alt'=>'Legal practice in Kosovo','description'=>'Legal services and insights from RZZ LAW in Kosovo.'];
    $ids=get_option('rzz_photo_media_ids',[]);$id=(int)($ids[$key]??0);
    if($url && !str_contains($url,'/themes/rzzlaw/assets/images/editorial/'))$id=attachment_url_to_postid($url);
    if($id){
        $media=get_post($id);
        if($media && $media->post_type==='attachment'){
            $alt=get_post_meta($id,'_wp_attachment_image_alt',true);
            if($alt!=='')$details['alt']=$alt;
            if($media->post_title)$details['title']=$media->post_title;
            if($media->post_content)$details['description']=wp_strip_all_tags($media->post_content);
        }
    }
    return $details;
}
function rzz_content_image_details($post,$url='') {
    $post=get_post($post);if(!$post)return rzz_image_details('');
    $key=$post->post_type==='expertise'?$post->post_name:'legislative-consulting';
    if($post->post_type==='post')foreach(rzz_lines(rzz_meta('areas',$post->ID)) as $area){if(isset(rzz_photo_catalog()[$area])){$key=$area;break;}}
    $details=rzz_image_details($key,$url);
    if(has_post_thumbnail($post) && (!$url || $url===get_the_post_thumbnail_url($post,'large') || $url===get_the_post_thumbnail_url($post,'medium_large'))){
        $id=get_post_thumbnail_id($post);$media=get_post($id);$alt=get_post_meta($id,'_wp_attachment_image_alt',true);
        if($alt!=='')$details['alt']=$alt;
        if($media && $media->post_title)$details['title']=$media->post_title;
        if($media && $media->post_content)$details['description']=wp_strip_all_tags($media->post_content);
    }
    foreach(['image_alt'=>'alt','image_title'=>'title','image_description'=>'description'] as $field=>$target){$value=rzz_meta($field,$post->ID);if($value!=='')$details[$target]=$value;}
    return $details;
}
function rzz_related_image($post,$size=800) {
    $post=get_post($post);if(!$post)return '';
    $override=rzz_meta('related_image_url',$post->ID);
    if($override)return esc_url_raw($override);
    return $post->post_type==='expertise'?rzz_practice_image($post,$size):rzz_article_image($post,$size);
}
function rzz_related_image_details($post){
    $post=get_post($post);$details=rzz_content_image_details($post,rzz_related_image($post));
    foreach(['related_image_alt'=>'alt','related_image_title'=>'title','related_image_description'=>'description'] as $field=>$target){$value=rzz_meta($field,$post->ID);if($value!=='')$details[$target]=$value;}
    return $details;
}
function rzz_photo_attributes($details) {
    return 'alt="'.esc_attr($details['alt']).'" title="'.esc_attr($details['title']).'"';
}
function rzz_current_image_details() {
    if(is_singular('team')){
        $post=get_post(get_queried_object_id());$id=get_post_thumbnail_id($post);$alt=$id?get_post_meta($id,'_wp_attachment_image_alt',true):'';
        return ['title'=>$post->post_title.' | RZZ LAW','alt'=>$alt?:$post->post_title.' — RZZ LAW','description'=>wp_strip_all_tags($post->post_excerpt)];
    }
    if(is_singular(['expertise','post']))return rzz_content_image_details(get_queried_object_id(),rzz_banner_image());
    if(is_page('about')||is_post_type_archive('expertise'))return rzz_image_details('about-office',rzz_banner_image());
    if(is_page('careers'))return rzz_image_details('careers-office',rzz_banner_image());
    if(is_post_type_archive('team'))return rzz_image_details('careers-office',rzz_banner_image());
    if(is_page('contact'))return rzz_image_details('contact-boardroom',rzz_banner_image());
    if(is_home()||is_category()||is_tag()||is_author()||is_date())return rzz_image_details('legislative-consulting',rzz_banner_image());
    if(!is_front_page())return rzz_image_details('about-office',rzz_banner_image());
    return ['title'=>'RZZ LAW | Kosovo','alt'=>'Urban architecture in Prishtina','description'=>'RZZ LAW provides legal advice and representation in Kosovo.'];
}
add_action('admin_init',function(){
    if(!current_user_can('manage_options')||get_stylesheet()!=='rzzlaw'||get_option('rzz_photo_metadata_version')==='1.2.1')return;
    require_once ABSPATH.'wp-admin/includes/image.php';
    $catalog=rzz_photo_catalog();$ids=get_option('rzz_photo_media_ids',[]);$complete=true;
    foreach($catalog as $key=>$details){
        // Update stored links that pointed to the previous bundled filenames.
        foreach(get_theme_mods()?:[] as $setting=>$value){
            if(is_string($value)&&str_starts_with($setting,'rzz_'))foreach([800,1600] as $size){
                if(str_ends_with($value,'/assets/images/editorial/'.$key.'-'.$size.'.webp'))set_theme_mod($setting,rzz_editorial_image($key,$size));
            }
        }
        if(!empty($ids[$key]) && get_post((int)$ids[$key]))continue;
        $existing=get_posts(['post_type'=>'attachment','post_status'=>'inherit','numberposts'=>1,'fields'=>'ids','meta_key'=>'rzz_photo_key','meta_value'=>$key]);
        if($existing){$ids[$key]=(int)$existing[0];continue;}
        $base=get_template_directory().'/assets/images/editorial/';
        $file=$base.$details['file'].'-1600.webp';$small=$base.$details['file'].'-800.webp';
        if(!is_file($file)||!is_file($small)){$complete=false;continue;}
        $upload=wp_upload_bits(basename($file),null,file_get_contents($file));
        if(!empty($upload['error'])){$complete=false;continue;}
        $id=wp_insert_attachment(['post_title'=>$details['title'],'post_excerpt'=>$details['title'],'post_content'=>$details['description'],'post_mime_type'=>'image/webp','post_status'=>'inherit'],$upload['file'],0,true);
        if(is_wp_error($id)){$complete=false;continue;}
        update_post_meta($id,'_wp_attachment_image_alt',$details['alt']);update_post_meta($id,'rzz_photo_key',$key);
        $dimensions=getimagesize($file);$metadata=['width'=>$dimensions[0],'height'=>$dimensions[1],'file'=>_wp_relative_upload_path($upload['file']),'filesize'=>filesize($file),'sizes'=>[]];
        $thumb=wp_upload_bits(basename($small),null,file_get_contents($small));
        if(empty($thumb['error'])){$dimensions=getimagesize($small);$metadata['sizes']['medium_large']=['file'=>basename($thumb['file']),'width'=>$dimensions[0],'height'=>$dimensions[1],'mime-type'=>'image/webp','filesize'=>filesize($small)];}
        wp_update_attachment_metadata($id,$metadata);$ids[$key]=$id;
        update_option('rzz_photo_media_ids',$ids,false);
    }
    update_option('rzz_photo_media_ids',$ids,false);
    if($complete && count($ids)===count($catalog))update_option('rzz_photo_metadata_version','1.2.1',false);
    wp_cache_flush();do_action('litespeed_purge_all');
});
