<?php 
function getGlobalID()
{
    $id = get_option('maep_lang');
	$lang_id = array(
		'EBAY-AT'  =>  '16',
		'EBAY-AU'  =>  '15',
		'EBAY-CH'  =>  '193',
		'EBAY-DE'  =>  '77',
		'EBAY-ENCA'  =>  '2',
		'EBAY-ES'  =>  '186',
		'EBAY-FR'  =>  '71',
		'EBAY-FRBE'  =>  '23',
		'EBAY-FRCA'  =>  '210',
		'EBAY-GB'  =>  '3',
		'EBAY-HK'  =>  '201',
		'EBAY-IE'  =>  '205',
		'EBAY-IN'  =>  '203',
		'EBAY-IT'  =>  '101',
		'EBAY-MOTOR'  =>  '100',
		'EBAY-MY'  =>  '207',
		'EBAY-NL'  =>  '146',
		'EBAY-NLBE'  =>  '123',
		'EBAY-PH'  =>  '211',
		'EBAY-PL'  =>  '212',
		'EBAY-SG'  =>  '216',
		'EBAY-US'  =>  '0'
	);
	$global_id = array_search($id, $lang_id);
	return $global_id;
}

function getCompain()
{
    $id = get_option('maep_lang');
    $lang_id = array(
        '3'  =>  '16',
        '4'  =>  '15',
        '14'  =>  '193',
        '11'  =>  '77',
        '10'  =>  '71',
        '2'  =>  '205',
        '12'  =>  '101',
        '16'  =>  '146',
        '1'  =>  '0',
        '15'  =>  '3',
        '5'  =>  '23',
        '7'  =>  '2',
    );
    $global_id = array_search($id, $lang_id);
    return $global_id;
}
