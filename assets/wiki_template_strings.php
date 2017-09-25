<?php



function return_cpsv_public_service_template($service_identifier, $service_name, $service_description, $competent_authority, $formal_framework, $output,$completion_time, $cost){
$cpsv_public_service_template = <<<EOT
{{Public Service
|identifier=$service_identifier
|name=$service_name
|description=$service_description
|keyword=
|sector=
|type=
|language=
|status=
|is_grouped_by=
|requires=
|related=
|has_criterion=
|has_competent_authority=$competent_authority
|has_service_provider=
|has_service_user=
|has_participation=
|has_input=
|has_formal_framework=$formal_framework
|produces=$output
|follows=
|spatial_temporal=
|has_contact_point=
|has_channel=
|processing_time=$completion_time
|has_cost=$cost
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
//
//function return_cpsv_cost_template(){
//$cpsv_cost_template = <<<EOT
//
//EOT;
//	
//return $cpsv_cost_template;
//}

//$cps;