<?php
/** Only expose fields consumed by this content type's templates. */
function rzz_fields($type){
    $seo=(function_exists('rzz_has_seo')&&rzz_has_seo())?[]:[
        'meta_title'=>'SEO title','meta_description'=>'SEO description'
    ];
    $photo=[
        'image_title'=>'Photo title','image_alt'=>'Photo alt text','image_description'=>'Photo description'
    ];
    $related_photo=[
        'related_image_url'=>'Related article photo URL (optional)',
        'related_image_alt'=>'Related article photo alt (optional)',
        'related_image_title'=>'Related article photo title (optional)',
        'related_image_description'=>'Related article photo description (optional)'
    ];
    return match($type){
        'page'=>$seo,
        'team'=>array_merge([
            'role'=>'Position','languages'=>'Languages','email'=>'Email','linkedin'=>'LinkedIn URL',
            'education'=>'Education (one entry per line)','career'=>'Career highlights (one per line)',
            'areas'=>'Practice area slugs (one per line, e.g. corporate-law)'
        ],$seo),
        'expertise'=>array_merge([
            'card_excerpt'=>'Short card description','services'=>'Matters covered (one per line)',
            'approach'=>'Why RZZ LAW (unique paragraph)'
        ],$photo,$seo),
        'post'=>array_merge([
            'author_name'=>'Editorial author name','areas'=>'Related expertise slugs (one per line)'
        ],$photo,$related_photo,$seo),
        'review'=>['company'=>'Client organisation / position'],
        default=>[]
    };
}
function rzz_render_details_box($post){
    wp_nonce_field('rzz_fields','rzz_fields_nonce');
    $short=['role','languages','email','linkedin','author_name','image_title','image_alt','related_image_url','related_image_alt','related_image_title','meta_title'];
    foreach(rzz_fields($post->post_type) as $key=>$label){
        $id='rzz_'.$key;$value=get_post_meta($post->ID,$id,true);
        echo '<p><label for="'.esc_attr($id).'" style="display:block;margin-bottom:6px">'.esc_html($label).'</label>';
        if(in_array($key,$short,true)){
            $type=$key==='email'?'email':(in_array($key,['linkedin','related_image_url'],true)?'url':'text');
            echo '<input style="width:100%" type="'.esc_attr($type).'" id="'.esc_attr($id).'" name="'.esc_attr($id).'" value="'.esc_attr($value).'">';
        }else{
            echo '<textarea style="width:100%" rows="3" id="'.esc_attr($id).'" name="'.esc_attr($id).'">'.esc_textarea($value).'</textarea>';
        }
        echo '</p>';
    }
    if($post->post_type==='team')echo '<p>Use Featured image for the portrait, the editor for biography, and Excerpt for the short profile. Edit portrait alt text and descriptions in Media Library.</p>';
    if(function_exists('rzz_has_seo')&&rzz_has_seo())echo '<p>SEO titles and descriptions are managed by your SEO plugin.</p>';
}
add_action('add_meta_boxes',function($type,$post){
    if(!$post||!rzz_fields($type))return;
    add_meta_box('rzz-details','RZZ LAW · Details','rzz_render_details_box',$type);
},10,2);
add_action('save_post',function($id){if(defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE)return;if(wp_is_post_revision($id)||!isset($_POST['rzz_fields_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rzz_fields_nonce'])),'rzz_fields')||!current_user_can('edit_post',$id))return;foreach(rzz_fields(get_post_type($id)) as $key=>$label){if(!isset($_POST['rzz_'.$key]))continue;$v=wp_unslash($_POST['rzz_'.$key]);$v=match($key){'email'=>sanitize_email($v),'linkedin','related_image_url'=>esc_url_raw($v),default=>sanitize_textarea_field($v)};update_post_meta($id,'rzz_'.$key,$v);}});
function rzz_meta($key,$id=0){return get_post_meta($id?:get_the_ID(),'rzz_'.$key,true);}
function rzz_lines($text){
    // Imported fields may contain escaped newlines as well as real line breaks.
    $text=str_replace(['\r\n','\n','\r'],"\n",(string)$text);
    $lines=preg_split('/\R/u',$text)?:[];
    return array_values(array_filter(array_map('trim',$lines),static fn($line)=>$line!==''));
}
function rzz_area_links($id=0){foreach(rzz_lines(rzz_meta('areas',$id)) as $slug){$area=get_page_by_path(sanitize_title($slug),OBJECT,'expertise');if($area)echo '<a class="pill" href="'.esc_url(get_permalink($area)).'">'.esc_html($area->post_title).'</a>';}}

/** One canonical label for role filters and profile displays. */
function rzz_team_role($id=0){
$role=preg_replace('/\s+/u',' ',trim(rzz_meta('role',$id)));
$known=['partner'=>'Partner','associate'=>'Associate','senior associate'=>'Senior Associate','counsel'=>'Counsel'];
return $known[mb_strtolower($role)]??$role;
}
