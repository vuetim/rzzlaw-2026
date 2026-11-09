<?php
$approaches=[
'corporate-law'=>'Corporate decisions affect the business long after documents are signed. We connect governance, ownership and compliance questions so that your legal structure supports the way your organisation operates.',
'contract-law'=>'We focus on how an agreement will work in practice: obligations, payment, performance and remedies. Our review helps you understand the consequences of each term before committing.',
'public-procurement'=>'We organise advice around the stages and deadlines of your procurement procedure. Careful document review and a clear record of communications help you make informed procedural decisions.',
'real-estate'=>'Our property work brings ownership records, due diligence and transaction documents into one coordinated review. We explain concerns early so that you can assess them before moving forward.',
'finance-law'=>'We examine financing terms alongside the underlying commercial arrangement. This helps clients understand security, repayment responsibilities and the legal implications of their investment decisions.',
'insurance-law'=>'We review policy wording alongside the facts and supporting records of your claim. Clear communication about coverage and the available resolution options guides the next steps.',
'criminal-law'=>'We prepare each matter with attention to the evidence, procedural stage and client’s individual circumstances. We explain the process and maintain clear, direct communication throughout.',
'legislative-consulting'=>'We combine structured legal research with a practical review of drafting and implementation. Our analysis considers how a proposed rule fits with the wider legal framework.'
];
$related=['corporate-law'=>'contract-law,finance-law','contract-law'=>'corporate-law,real-estate','public-procurement'=>'contract-law,legislative-consulting','real-estate'=>'contract-law,finance-law','finance-law'=>'corporate-law,insurance-law','insurance-law'=>'contract-law,finance-law','criminal-law'=>'insurance-law,legislative-consulting','legislative-consulting'=>'public-procurement,corporate-law'];

return [$approaches,$related];
