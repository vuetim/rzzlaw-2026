<?php
function rzz_fields($type){$shared=['image_title'=>'Photo title','image_alt'=>'Photo alt text','image_description'=>'Photo description','related_image_url'=>'Related card photo URL (optional)','related_image_alt'=>'Related card photo alt (optional)','related_image_title'=>'Related card photo title (optional)','related_image_description'=>'Related card photo description (optional)','meta_title'=>'SEO title','meta_description'=>'SEO description'];return match($type){'team'=>array_merge(['role'=>'Position','languages'=>'Languages','email'=>'Email','linkedin'=>'LinkedIn URL','education'=>'Education (one entry per line)','career'=>'Career highlights (one per line)','areas'=>'Practice area slugs (one per line, e.g. corporate-law)'],$shared),'expertise'=>array_merge(['card_excerpt'=>'Short card description','services'=>'Matters covered (one per line)','approach'=>'Why RZZ LAW (unique paragraph)','related'=>'Related expertise slugs (one per line)'],$shared),'review'=>['company'=>'Client organisation / position'],default=>array_merge(['author_name'=>'Editorial author name','areas'=>'Related expertise slugs (one per line)'],$shared)};}
add_action('add_meta_boxes',function(){foreach(['team','expertise','review','page','post'] as $type)add_meta_box('rzz-details','RZZ LAW · Details',function($post){wp_nonce_field('rzz_fields','rzz_fields_nonce');foreach(rzz_fields($post->post_type) as $key=>$label)echo '<p><label for="rzz_'.esc_attr($key).'">'.esc_html($label).'</label><textarea style="width:100%" rows="3" id="rzz_'.esc_attr($key).'" name="rzz_'.esc_attr($key).'">'.esc_textarea(get_post_meta($post->ID,'rzz_'.$key,true)).'</textarea></p>';if($post->post_type==='team')echo '<p>Featured image = real portrait. Editor = biography. Excerpt = short introduction. Practice area slugs link to Expertise pages.</p>';},$type);});
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
