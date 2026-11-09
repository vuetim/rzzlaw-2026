<?php
add_action('admin_post_nopriv_rzz_contact','rzz_handle_contact');add_action('admin_post_rzz_contact','rzz_handle_contact');
function rzz_handle_contact(){
$back=wp_get_referer()?:home_url('/contact/');$back=remove_query_arg('enquiry',$back);$finish=function($status)use($back){wp_safe_redirect(add_query_arg('enquiry',$status,$back).'#contact-form');exit;};
foreach(['rzz_nonce','full_name','email','phone','message','practice_area','privacy','website'] as $field)if(isset($_POST[$field])&&!is_string($_POST[$field]))$finish('invalid');
if(!isset($_POST['rzz_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rzz_nonce'])),'rzz_contact'))$finish('expired');
if(!empty($_POST['website']))$finish('error');
$key='rzz_form_'.hash_hmac('sha256',$_SERVER['REMOTE_ADDR']??'',wp_salt());if(get_transient($key))$finish('rate');
$name=sanitize_text_field(wp_unslash($_POST['full_name']??''));$email=sanitize_email(wp_unslash($_POST['email']??''));$phone=sanitize_text_field(wp_unslash($_POST['phone']??''));$message=sanitize_textarea_field(wp_unslash($_POST['message']??''));$area=sanitize_text_field(wp_unslash($_POST['practice_area']??''));
if(!$name||!is_email($email)||strlen($message)<10||strlen($message)>10000||empty($_POST['privacy']))$finish('invalid');
set_transient($key,1,60);$to=sanitize_email(rzz_setting('contact_recipient','info@rzzlaw.com'));if(!$to)$finish('error');
$sent=wp_mail($to,'Website enquiry · '.get_bloginfo('name'),"Name: $name
Email: $email
Phone: $phone
Practice area: $area

$message",['Reply-To: '.$email]);$finish($sent?'sent':'error');
}
