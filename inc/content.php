<?php
function rzz_areas(){return [
'corporate-law'=>['Corporate Law','Legal support for the establishment, governance and ongoing operations of businesses.','Company formation and registration|Corporate governance and compliance|Shareholder arrangements|Mergers, acquisitions and reorganisations|Commercial registry matters|Ongoing business advisory','From a new venture to an established organisation, business decisions need a clear legal foundation. RZZ LAW advises businesses on their structure, internal relationships and commercial objectives. We work with management and stakeholders to identify legal risks early and put practical arrangements in place.'],
'contract-law'=>['Contract Law','Clear, workable agreements that protect your interests and support your commercial relationships.','Drafting commercial agreements|Contract review and risk assessment|Negotiation support|Distribution and supply agreements|Breach of contract disputes|Termination and settlement arrangements','A carefully prepared agreement helps parties understand their obligations before a disagreement arises. Our contract work begins with the transaction itself: what each party needs, where responsibility sits and how performance will be measured. We help clients draft, review and negotiate agreements with these questions in mind.'],
'public-procurement'=>['Public Procurement','Guidance through procurement procedures, tender documentation and review processes.','Tender documentation review|Bid preparation support|Procurement compliance|Requests for clarification|Review and challenge procedures|Public contract performance','Public procurement demands attention to both procedure and substance. We assist economic operators and organisations in understanding tender requirements, preparing documentation and responding to procedural issues. Our approach focuses on the deadlines, evidence and commercial considerations relevant to each procurement.'],
'real-estate'=>['Real Estate','Legal assistance for property transactions, development and the protection of property interests.','Property due diligence|Sale and purchase agreements|Lease negotiation|Development arrangements|Ownership and registration matters|Property disputes','Property decisions depend on a reliable understanding of ownership, documentation and contractual obligations. We assist clients with legal due diligence and the agreements that underpin real estate transactions. We also advise on practical steps for addressing title concerns and resolving disputes connected with property.'],
'finance-law'=>['Finance Law','Advice on financing arrangements, investment structures and financial transactions.','Financing documentation|Loan and security agreements|Investment arrangements|Transaction due diligence|Regulatory considerations|Debt recovery and restructuring','Financing arrangements should reflect both the transaction and the responsibilities of the parties involved. RZZ LAW supports businesses and investors in reviewing documentation, assessing obligations and structuring workable terms. We bring a coordinated legal perspective to financing, security and investment matters.'],
'insurance-law'=>['Insurance Law','Advice for policyholders and businesses on insurance coverage, claims and disputes.','Policy review and interpretation|Coverage assessments|Claims preparation|Insurer correspondence|Settlement negotiations|Insurance-related litigation','When an insured event occurs, the wording of the policy and the record of what happened both matter. We help clients understand coverage provisions, organise supporting information and present their position. Where a dispute develops, we advise on the available routes to negotiation or formal resolution.'],
'criminal-law'=>['Criminal Law','Careful legal advice and representation at every stage of a criminal matter.','Advice during investigations|Representation in proceedings|Case and evidence review|Defence preparation|Appeals and procedural remedies|Advice on individual rights','A criminal matter requires careful preparation, clear communication and respect for the rights of the person involved. RZZ LAW provides advice on procedural steps and representation according to the circumstances of each case. We work with clients to understand the evidence and prepare an informed response.'],
'legislative-consulting'=>['Legislative Consulting','Drafting, analysis and advisory support for legislative and regulatory initiatives.','Legislative analysis|Drafting support|Regulatory impact considerations|Comparative legal research|Policy and consultation submissions|Implementation guidance','Effective legislation requires precise language and an understanding of how rules will work in practice. We support organisations with legal research, analysis and drafting for legislative and regulatory initiatives. Our work connects policy objectives with a structured review of legal coherence and implementation questions.']
];}
function rzz_register_content(){foreach(['expertise'=>['Expertise','Practice Area','dashicons-portfolio'],'team'=>['Our Team','Team Member','dashicons-groups'],'review'=>['Client Voices','Client Voice','dashicons-format-quote']] as $type=>$data)register_post_type($type,['labels'=>['name'=>$data[0],'singular_name'=>$data[1]],'public'=>$type!=='review','show_ui'=>true,'show_in_rest'=>true,'has_archive'=>$type!=='review','rewrite'=>['slug'=>$type],'menu_icon'=>$data[2],'supports'=>['title','editor','excerpt','thumbnail','page-attributes','revisions']]);}
add_action('init','rzz_register_content');
function rzz_initial_content(){rzz_register_content();
[$approaches,$related]=require get_template_directory().'/inc/service-defaults.php';
foreach(rzz_areas() as $slug=>$area){if(!get_page_by_path($slug,OBJECT,'expertise')){$id=wp_insert_post(['post_type'=>'expertise','post_status'=>'publish','post_title'=>$area[0],'post_name'=>$slug,'post_content'=>$area[3],'post_excerpt'=>$area[1],'menu_order'=>array_search($slug,array_keys(rzz_areas()),true)]);if(!is_wp_error($id)){update_post_meta($id,'rzz_services',str_replace('|',"
",$area[2]));update_post_meta($id,'rzz_meta_description',$area[1]);update_post_meta($id,'rzz_meta_title',$area[0].' in Kosovo | RZZ LAW');update_post_meta($id,'rzz_approach',$approaches[$slug]);update_post_meta($id,'rzz_related',str_replace(',',chr(10),$related[$slug]));}}}
$pages=['home'=>['Home',''],'about'=>['About Us','RZZ LAW is a legal practice based in Kosovo, advising businesses, institutions and individuals. Our approach combines careful legal analysis with a practical understanding of each client’s objectives.'],'contact'=>['Contact Us',''],'insights'=>['Insights',''],'privacy-policy'=>['Privacy Policy','This page is awaiting firm-specific privacy information and review. Please contact the firm for information about how your enquiry and personal data are handled.'],'terms-and-conditions'=>['Terms and Conditions','This page is awaiting firm-specific terms and review. Website information is general and does not replace advice on your particular circumstances. Contacting the firm does not by itself establish an attorney-client relationship.'],'careers'=>['Careers','Interested in joining RZZ LAW? Contact our team to ask about current opportunities.'],'faq'=>['Frequently Asked Questions','']];
$legal=require get_template_directory().'/inc/legal-defaults.php';
foreach($pages as $slug=>$data){$existing=get_page_by_path($slug);if(!$existing)$id=wp_insert_post(['post_type'=>'page','post_status'=>in_array($slug,['careers','faq'])?'draft':'publish','post_title'=>$data[0],'post_name'=>$slug,'post_content'=>$legal[$slug]??$data[1]]);else $id=$existing->ID;if($slug==='home'){update_option('page_on_front',$id);update_option('show_on_front','page');}if($slug==='insights')update_option('page_for_posts',$id);if($slug==='privacy-policy'){update_option('wp_page_for_privacy_policy',$id);if(get_post_status($id)==='draft')wp_update_post(['ID'=>$id,'post_status'=>'publish']);}}
flush_rewrite_rules();}
add_action('after_switch_theme','rzz_initial_content');

function rzz_card_summary($item){
$custom=rzz_meta('card_excerpt',$item->ID);if($custom)return $custom;
$copy=[
'corporate-law'=>'Formation, governance and compliance for businesses.',
'contract-law'=>'Drafting, review and negotiation of contracts.',
'public-procurement'=>'Legal support in all stages of procurement processes.',
'real-estate'=>'Legal assistance in property transactions and development.',
'finance-law'=>'Regulatory, financing and investment advice.',
'insurance-law'=>'Legal services for insurers, intermediaries and policyholders.',
'criminal-law'=>'Legal defence and representation in criminal matters.',
'legislative-consulting'=>'Drafting, analysis and advisory on legislative initiatives.'
];return $copy[$item->post_name]??$item->post_excerpt;
}

function rzz_related_expertise($post,$limit=2){
    $post=get_post($post);if(!$post||$limit<1)return [];
    [, $defaults]=require get_template_directory().'/inc/service-defaults.php';
    $raw=implode("\n",rzz_lines(rzz_meta('related',$post->ID)));
    $slugs=array_values(array_unique(array_filter(array_map('sanitize_title',preg_split('/[\r\n,]+/',$raw)?:[]))));
    $preferred=$slugs?:explode(',',$defaults[$post->post_name]??'');
    $items=[];$exclude=[$post->ID];
    foreach([$preferred,explode(',',$defaults[$post->post_name]??''),[]] as $batch){
        if(count($items)>=$limit)break;
        $query=['post_type'=>'expertise','post_status'=>'publish','numberposts'=>$limit-count($items),'post__not_in'=>$exclude,'orderby'=>'menu_order','order'=>'ASC'];
        $batch=array_values(array_filter($batch));
        if($batch){$query['post_name__in']=$batch;$query['orderby']='post_name__in';}
        foreach(get_posts($query) as $item){if(in_array($item->ID,$exclude,true))continue;$items[]=$item;$exclude[]=$item->ID;}
    }
    return array_slice($items,0,$limit);
}
