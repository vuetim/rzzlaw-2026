<?php
/** Two fixed practice links. Selection never depends on imported post metadata. */
$current=get_post_field('post_name',get_the_ID());
$areas=rzz_areas();
[, $pairs]=require get_template_directory().'/inc/service-defaults.php';
$slugs=explode(',',$pairs[$current]??'contract-law,finance-law');
$slugs=array_values(array_filter(array_unique(array_merge($slugs,array_keys($areas))),static fn($slug)=>$slug!==$current&&isset($areas[$slug])));
$slugs=array_slice($slugs,0,2);
?>
<section class="expertise-crosslinks container" aria-labelledby="expertise-crosslinks-heading">
    <h2 class="eyebrow" id="expertise-crosslinks-heading">Related expertise</h2>
    <div class="expertise-crosslinks-grid">
        <?php foreach($slugs as $slug):
            $photo=rzz_setting('expertise_'.$slug.'_image')?:rzz_editorial_image($slug,800);
            $details=rzz_image_details($slug,$photo);
        ?>
        <a class="expertise-crosslink" href="<?php echo esc_url(rzz_link('expertise/'.$slug)); ?>">
            <img src="<?php echo esc_url($photo); ?>" <?php echo rzz_photo_attributes($details); ?> width="800" height="533" loading="lazy" decoding="async">
            <h3><?php echo esc_html($areas[$slug][0]); ?></h3>
            <span class="expertise-crosslink-arrow" aria-hidden="true"><?php echo rzz_arrow(); ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</section>
