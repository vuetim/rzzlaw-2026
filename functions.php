<?php
if (!defined('ABSPATH')) exit;
define('RZZ_VERSION','1.2.3');
foreach (['faq','about-sections','setup','photo-catalog','editorial-images','photo-metadata','content','customizer','fields','seo','contact','upgrade'] as $module) require get_template_directory().'/inc/'.$module.'.php';
function rzz_setting($key,$fallback=''){return get_theme_mod('rzz_'.$key,$fallback);}
function rzz_text($key,$fallback=''){echo esc_html(rzz_setting($key,$fallback));}
function rzz_asset($name){return get_template_directory_uri().'/assets/'.$name;}
function rzz_link($path){return home_url('/'.trim($path,'/').'/');}
function rzz_arrow(){return '<svg viewBox="0 0 32 16" fill="none" aria-hidden="true"><path d="M1 8h28M22 1l7 7-7 7" stroke="currentColor" stroke-width="1.3"/></svg>';}
function rzz_logo(){if(has_custom_logo()){the_custom_logo();}else{echo '<a class="wordmark" href="'.esc_url(home_url('/')).'" aria-label="RZZ LAW home"><span class="brand-dots" aria-hidden="true"><i></i><i></i><i></i></span>RZZ LAW</a>';}}
function rzz_icon($name){$paths=['corporate-law'=>'M8 30V8h14v22M14 8V3h13v27M3 30h29M11 12h2m4 0h2m-8 5h2m4 0h2m-8 5h2m4 0h2m6-10h2m-2 5h2m-2 5h2M14 30v-5h4v5','contract-law'=>'M7 3h22v28H7zM11 9h14M11 14h14M11 19h14M11 24h14','public-procurement'=>'m8 8 8-6 10 12-8 6zM13 15 3 27l4 4 12-14M22 28h10v4H20v-4','real-estate'=>'m2 10 15-7 15 7-15 7zM4 17l13 7 13-7M4 24l13 7 13-7','finance-law'=>'M3 17c0-5 14-5 14 0v12c0 5-14 5-14 0zM3 17c0 5 14 5 14 0M3 23c0 5 14 5 14 0M18 8c0-5 14-5 14 0v15c0 4-8 5-12 3M18 8c0 5 14 5 14 0M20 16c4 2 12 1 12-2M20 22c4 2 12 1 12-2','insurance-law'=>'m17 2 13 5v10c0 8-13 15-13 15S4 25 4 17V7zM11 16l5 5 8-10','criminal-law'=>'M17 2v29M7 31h20M3 9h28M7 9 1 23h12L7 9Zm20 0-6 14h12L27 9Z','legislative-consulting'=>'M17 7C12 3 5 3 2 5v24c5-2 10-1 15 2 5-3 10-4 15-2V5c-3-2-10-2-15 2Zm0 0v24','search'=>'m23 23 9 9M27 15A12 12 0 1 1 3 15a12 12 0 0 1 24 0Z','fingerprint'=>'M6 15a11 11 0 0 1 22 0M3 11a15 15 0 0 1 28 0M10 20v-5a7 7 0 0 1 14 0v6M14 26V15a3 3 0 0 1 6 0v10M6 20v6m4 0v5m10-1v2m8-11v6m-14 5v-2'];return '<svg viewBox="0 0 34 34" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="'.($paths[$name]??$paths['insurance-law']).'"/></svg>';}
function rzz_breadcrumb(){echo '<nav class="breadcrumb" aria-label="Breadcrumb"><a href="'.esc_url(home_url('/')).'">Home</a><span>›</span>';if(is_singular(['expertise','team'])){$type=get_post_type();echo '<a href="'.esc_url(get_post_type_archive_link($type)).'">'.esc_html(ucfirst($type)).'</a><span>›</span>';}echo '<span aria-current="page">'.esc_html(is_archive()?post_type_archive_title('',false):(is_search()?'Search':(is_404()?'Page not found':get_the_title()))).'</span></nav>';}
function rzz_intro($title,$description=''){
    $image=rzz_banner_image();
    ?>
    <section class="page-hero<?php echo $image?' has-editorial-image':''; ?>"<?php if($image): ?> style="--page-hero-image:url('<?php echo esc_url($image); ?>');--page-hero-image-mobile:url('<?php echo esc_url(rzz_banner_image(800)); ?>')"<?php endif; ?>>
        <?php if($image): ?><picture class="page-hero-media"><source media="(max-width:600px)" srcset="<?php echo esc_url(rzz_banner_image(800)); ?>"><img src="<?php echo esc_url($image); ?>" <?php echo rzz_photo_attributes(rzz_current_image_details()); ?> width="1536" height="1024" fetchpriority="high" decoding="async"></picture><?php endif; ?>
        <div class="container"><?php rzz_breadcrumb(); ?><p class="eyebrow">RZZ LAW · Kosovo</p><h1><?php echo esc_html($title); ?></h1><?php if($description): ?><p class="lead"><?php echo esc_html($description); ?></p><?php endif; ?></div>
    </section>
    <?php
}

function rzz_ui_icon($name){
$paths=[
'pin'=>'M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z M15 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z',
'phone'=>'M8 3 4 4c-2 7 9 18 16 16l1-4-5-3-2 2c-3-1-4-2-5-5l2-2-3-5Z',
'mail'=>'M3 5h18v14H3z m0 0 9 8 9-8',
'office'=>'M4 21V3h12v18M16 9h4v12M2 21h20M8 7h4M8 11h4M8 15h4M9 21v-3h2v3',
'clock'=>'M22 12a10 10 0 1 1-20 0 10 10 0 0 1 20 0ZM12 6v6l4 2',
'chevron'=>'m6 9 6 6 6-6',
'arrow-up'=>'M12 20V4m-6 6 6-6 6 6',
'linkedin'=>'M4 9v11M4 4v.2M9 20V9h4v2c3-4 7-2 7 2v7M13 20v-7'
];return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="'.($paths[$name]??$paths['pin']).'"/></svg>';}
