<?php
function rzz_faqs(){return [
['How can I arrange an initial consultation?','Use the contact form to tell us briefly what you need help with. Our team will respond to discuss the appropriate next steps and arrange a consultation.'],
['Which areas of law do you cover?','Our practice areas include Corporate Law, Contract Law, Public Procurement, Real Estate, Finance Law, Insurance Law, Criminal Law and Legislative Consulting. Explore Expertise for details.'],
['What should I include in my first enquiry?','Please provide your name, contact details and a brief outline of your matter. Avoid sending sensitive documents until we confirm the appropriate way to share them.'],
['How are fees agreed?','Contact our team to discuss the scope of your matter and the proposed fee arrangements before work begins.'],
['Can you assist clients outside Kosovo?','Send us an outline of your circumstances and the jurisdictions involved. We will discuss whether and how our team can assist with your particular matter.']
];}

function rzz_faq_controls(){ $controls=[]; foreach(rzz_faqs() as $i=>$item){$controls['faq_q_'.$i]=['Question '.($i+1),$item[0],'text'];$controls['faq_a_'.$i]=['Answer '.($i+1),$item[1],'textarea'];} return $controls; }
