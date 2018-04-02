<?php



function return_cpsv_public_service_template($service_name){
$cpsv_public_service_template = <<<EOT
{{Public Service
|identifier={{#show: $service_name | ?Service Identifier}}
|name=CPSV $service_name
|description={{#show: $service_name | ?Service Description}}
|keyword=
|sector=
|type=
|language=
|status=
|is_grouped_by=
|requires=
|related=
|has_criterion=
|has_competent_authority={{#show: $service_name | ?Service Has Competent Authority}}
|has_service_provider={{#show: $service_name | ?Service Service Provider}}
|has_service_user={{#show: $service_name | ?Service Service User}}
|has_participation=
|has_input={{#show: $service_name | ?Service Has Main Document}},{{#show: $service_name | ?Service Has Collateral Documents}}
|has_formal_framework={{#show: $service_name | ?Service Has Formal Framework}}
|produces={{#show: $service_name | ?Service Produces}}
|follows=
|spatial_temporal=
|has_contact_point=
|has_channel=
|processing_time={{#show: $service_name | ?Service Processing Time}}
|has_cost={{#show: $service_name | ?Service Has Cost}}
}}
EOT;

return $cpsv_public_service_template;
}

function return_cpsv_agent_template($agent_identifier, $agent_name){
$cpsv_agent_template = <<<EOT
{{Agent
|identifier=$agent_identifier
|name=$agent_name
|type=
|plays_role=
|uses=
|has_address=
}}
EOT;

return $cpsv_agent_template;
}

//function return_cpsv_framework_template(){
//	
//}

function return_cpsv_public_organization_template($organization_identifier, $organization_preferred_label, $organization_spatial){
$cpsv_public_organization_template = <<<EOT
{{Public Organization
|identifier=$organization_identifier
|preferred_label=$organization_preferred_label
|spatial=$organization_spatial
|plays_role=
|uses=
|has_address=
}}
EOT;

return $cpsv_public_organization_template;
}

function return_cpsv_cost_template($cost_identifier, $cost_value){
$cpsv_cost_template = <<<EOT
{{Cost
|identifier=$cost_identifier
|value=$cost_value
|currency=EUR
|description=
|is_defined_by=
}}
EOT;

return $cpsv_cost_template;
}

function return_cpsv_channel_template($channel_identifier, $channel_type, $contact_point){
$cpsv_channel_template = <<<EOT
{{Channel
|identifier=$channel_identifier
|is_owned_by=
|type=$channel_type
|has_contact_point=$contact_point
|processing_time=
|availability=
|has_cost=
|has_input=
}}
EOT;

return $cpsv_channel_template;
}

function return_cpsv_evidence_template($evidence_identifier, $evidence_name){
$cpsv_evidence_template = <<<EOT
{{Evidence
|identifier=$evidence_identifier
|name=$evidence_name
|description=
|type=
|related_documentation=
|language=
}}
EOT;
	
return $cpsv_evidence_template;
}

function return_cpsv_output_template($output_identifier, $output_name){
$cpsv_output_template = <<<EOT
{{Output
|identifier=$output_identifier
|name=$output_name
|description=
|type=
}}
EOT;
	
return $cpsv_output_template;
}

function return_public_public_service_page_sample($service_name){
$public_public_service_page = <<<EOT
{{Υπηρεσία Δημοσίου
|Τίτλος Υπηρεσίας={{#show: $service_name | ?Service Name}}
|Αρμόδια Αρχή={{#show: $service_name | ?Service Has Competent Authority}}
|Παρέχεται Από={{#show: $service_name | ?Service Service Provider}}
|Παρέχεται Σε={{#show: $service_name | ?Service Service User}}
|Νομοθετικό Πλαίσιο={{#show: $service_name | ?Service Has Formal Framework}}
|Εργάσιμες ημέρες κατά προσέγγιση={{#show: $service_name | ?Service Processing Time}}
|Κόστος σε ευρώ={{#show: $service_name | ?Service Has Cost}}
}}

== Περιγραφη Υπηρεσιας == 
{{#show: $service_name | ?Service Description}}
				
=== Νομοθετικό Πλαίσιο === 
{{#show: $service_name | ?Service Has Formal Framework}}

=== Τρόπος Διεκπεραίωσης === 
{{#show: $service_name | ?Service Has Execution Method}}
				
=== Έντυπο που χρησιμοποιείται === ".PHP_EOL.
{{#show: $service_name | ?Service Has Main Document}}
{{#show: $service_name | ?Service Has Main Document Description}}

== Δικαιολογητικά == 
{{#show: $service_name | ?Service Has Collateral Documents}}
EOT;

//{{#ask:
//here to append the steps				
//}}

return $public_public_service_page;	
}
//
//function return_cpsv_cost_template(){
//$cpsv_cost_template = <<<EOT
//
//EOT;
//	
//return $cpsv_cost_template;
//}

//$cps;