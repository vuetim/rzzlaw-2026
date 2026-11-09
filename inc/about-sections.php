<?php
function rzz_about_sections(){return [
'guides'=>['label'=>'What Guides Us','title'=>'Why Choose Us','items'=>[
['Honest, Expert Lawyers','Our firm was created by like minded individuals that believe that honesty is essential to building long lasting relationships.'],
['Integrated Legal Services','Each of our experts are accustomed to working in multidisciplinary teams so that our clients can make comprehensive decisions and ensure a positive outcome.'],
['Quality Legal Advice','The RZZ Law team is experienced in a variety of fields and we work together when providing advice to our clients.']]],
'practice'=>['label'=>'How We Work','title'=>'Our Practice','items'=>[
['Legal Consultation','Our firm is comprised of lawyers and legal experts, and we provide legal advice and representation for our clients.'],
['Business Consultation','We assist our clients on their business ventures so that they can adequately measure their risks.'],
['Administration Services','Our firm can provide administration services to businesses so that they can have a presence without being here.']]],
'philosophy'=>['label'=>'What We Believe','title'=>'Our Philosophy','items'=>[
['Respect the Law','Our laws exist for a reason and neither greed nor convenience shall push us to destroy that reason.'],
['Respect the Client','Each client has a purpose, and we shall help them achieve it in the best way possible.'],
['Be Honest and Compassionate','Be clear and honest when communicating internally and externally. Try to understand each person you communicate with.']]]
];}
function rzz_about_section_controls($id){$data=rzz_about_sections()[$id];$prefix='about_'.$id.'_';$fields=[$prefix.'label'=>['Eyebrow',$data['label'],'text'],$prefix.'title'=>['Heading',$data['title'],'text']];foreach($data['items'] as $i=>$item){$fields[$prefix.$i.'_title']=['Item '.($i+1).' title',$item[0],'text'];$fields[$prefix.$i.'_text']=['Item '.($i+1).' text',$item[1],'textarea'];}return $fields;}
