<?php
$query=['post_type'=>'post','numberposts'=>2,'post__not_in'=>[get_the_ID()],'orderby'=>'date','order'=>'DESC'];
$areas=rzz_lines(rzz_meta('areas'));
if($areas)$query['meta_query']=[['key'=>'rzz_areas','value'=>$areas[0],'compare'=>'LIKE']];
else { $categories=wp_get_post_categories(get_the_ID());if(!$categories)return;$query['category__in']=$categories; }
$items=get_posts($query);
if(!$items)return;
?><section class="related-articles" aria-labelledby="related-articles-title"><h2 class="eyebrow" id="related-articles-title">Related articles</h2><div class="related-articles-grid"><?php foreach($items as $item): ?><a class="article-photo-card" href="<?php echo esc_url(get_permalink($item)); ?>"><img src="<?php echo esc_url(rzz_related_image($item)); ?>" width="800" height="533" <?php echo rzz_photo_attributes(rzz_related_image_details($item)); ?> loading="lazy" decoding="async"><h3><?php echo esc_html($item->post_title); ?></h3><?php echo rzz_arrow(); ?></a><?php endforeach; ?></div></section>