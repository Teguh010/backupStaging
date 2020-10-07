<?php

class Model_config extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		####### DEFAULT CONFIGURATION #######
		$config['BRANCH_ID'] = 1;
		$config['BRANCH_TAG'] = 'SJ';
		$config['BRANCH_NAME'] = 'SmartJen';
		$config['BRANCH_TITLE'] = 'SmartJen';
		$config['BRANCH_EMAIL'] = 'hello@smartjen.com';

		$config['BRANCH_LOGO'] = 'smart-jen-logo-horizontal.jpg';
		$config['BRANCH_LOGO_PDF'] = 'smartjen-logo-text.jpg';
		$config['BRANCH_HEADER_PDF'] = '<htmlpageheader name="myHeader1" style="display:none">
			<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
			    color: #000000; border-bottom: 1px solid #333"><tr>
			    <td width="33%" align="left">SmartGen - Questions</td>
			    <td width="33%"></td>
			    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
			    </tr>
			</table>
		</htmlpageheader>
		<htmlpageheader name="myHeader2" style="display:none">
			<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
			    color: #000000; border-bottom: 1px solid #333"><tr>
			    <td width="33%" align="left">SmartGen - Answers</td>
			    <td width="33%"></td>
			    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
			    </tr>
			</table>
		</htmlpageheader>';
		$config['BRANCH_HOME_CONTENT'] = 'view_home';
		$config['BRANCH_FOOTER'] = 'include/site_footer';
		$config['BRANCH_CSS'] = '#logo-size { width: 262px !important;height: 101px !important; }';
		$config['BRANCH_REG_FORM'] = 'google_form';
		$config['BRANCH_ADD_USER'] = 1; // 1 = ACTIVE, 0 = NOT-ACTIVE
		$config['BRANCH_USER_PRIVILEGE_ACTIVE'] = 1; // 1 = ACTIVE, 0 = NOT-ACTIVE
		$config['BRANCH_ATS'] = 0; // Admin Tagging System | 1 = ACTIVE, 0 = NOT-ACTIVE
		$config['BRANCH_TTS'] = 1; // Tutor Tagging System | 1 = ACTIVE, 0 = NOT-ACTIVE
		$config['BRANCH_AWG'] = 1; // Admin Worksheet Generation | 1 = ACTIVE, 0 = NOT-ACTIVE
		$config['BRANCH_TID'] = 0; // User Defined TID/LO

		$domainName = $_SERVER['HTTP_HOST']; 
		##### PLEASE CHANGE $localDevName VALUE FOR DEVELOP A BRANCH ####
		### $localDevName empty value will call default configuration or read domain name ###
		### IF CHANGE THE VALUE, PLEASE LOGOUT FIRST BE FOR CONTINUE CHECK ###
		$localDevName = "staging";
		
		##### DEMO #####
		if($domainName=="staging.smartjen.com" || $domainName=="demo.smartjen.com" || (($localDevName=="staging" || $localDevName=="demo") && $localDevName!="")) {
			$config['BRANCH_ID'] = 9;
			$config['BRANCH_TAG'] = 'Prototype';
			$config['BRANCH_NAME'] = 'Prototype';
			$config['BRANCH_ATS'] = 0;
			$config['BRANCH_TTS'] = 1;
			$config['BRANCH_AWG'] = 0;
		}
		##### MAIN #####
		if($domainName=="www.smartjen.com" || $domainName=="smartjen.com" || ($localDevName=="main" && $localDevName!="")) {
			$config['BRANCH_ATS'] = 1;
			/* MAIN USE DEFAULT */
		}
		##### EIMATHS #####
		if($domainName=="eimaths-staging.smartjen.com" || ($localDevName=="eimaths" && $localDevName!="")) {
			$config['BRANCH_ID'] = 14;
			$config['BRANCH_TAG'] = 'eiMaths';
			$config['BRANCH_NAME'] = 'eiMaths';
			$config['BRANCH_TITLE'] = 'eiMaths';
			$config['BRANCH_LOGO'] = 'eimaths_logo.png';
			$config['BRANCH_LOGO_PDF'] = 'eimaths_logo.png';
			$config['BRANCH_HEADER_PDF'] = '<htmlpageheader name="myHeader1" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
				    color: #000000; border-bottom: 1px solid #333"><tr>
				    <td width="33%" align="left">SmartGen - Questions</td>
				    <td width="33%"></td>
				    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
				    </tr>
				</table>
			</htmlpageheader>
			<htmlpageheader name="myHeader2" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
				    color: #000000; border-bottom: 1px solid #333"><tr>
				    <td width="33%" align="left">SmartGen - Answers</td>
				    <td width="33%"></td>
				    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
				    </tr>
				</table>
			</htmlpageheader>';
			$config['BRANCH_CSS'] = '#logo-size { width: 200px !important;height: 85px !important; } #nav-logo-size { max-width: 120px;}';
			$config['BRANCH_REG_FORM'] = 'register_form';
			$config['BRANCH_USER_PRIVILEGE_ACTIVE'] = 0;
			$config['BRANCH_TTS'] = 0;
			$config['BRANCH_ATS'] = 1;
			$config['BRANCH_AWG'] = 1;
		}
		##### ZYD ACADEMY #####
		if($domainName=="zydacademy-staging.smartjen.com" || ($localDevName=="zyd" && $localDevName!="")) {
			$config['BRANCH_ID'] = 4;
			$config['BRANCH_TAG'] = 'ZYD';
			$config['BRANCH_NAME'] = 'ZYD Academy';
			$config['BRANCH_TITLE'] = 'ZYD Academy';
			$config['BRANCH_LOGO'] = 'ZYD_Academy_Horizontal.png';
			$config['BRANCH_LOGO_PDF'] = 'ZYD_Academy_Horizontal.png';
			$config['BRANCH_HEADER_PDF'] = '<htmlpageheader name="myHeader1" style="display: -webkit-box">
			<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
			    color: #000000; border-bottom: 1px solid #333"><tr>
				<td width="33%" align="left"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 20%"></td>
			    <td width="67%" align="right"><img src="'.base_url().'img/ZYD_Academy_Student.png" style="width: 70%"></td>
			    </tr>
			</table>
		</htmlpageheader>
		<htmlpageheader name="myHeader2" style="display: -webkit-box">
			<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
			    color: #000000; border-bottom: 1px solid #333"><tr>
			    <td width="33%" align="left"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 20%"></td>
			    <td width="67%" align="right"><img src="'.base_url().'img/ZYD_Academy_Student.png" style="width: 70%"></td>
			    </tr>
			</table>
		</htmlpageheader>';
			$config['BRANCH_CSS'] = '#logo-size { width: 200px !important;height: 85px !important; } #nav-logo-size { max-width: 120px;}';
			$config['BRANCH_REG_FORM'] = 'register_form';
			$config['BRANCH_USER_PRIVILEGE_ACTIVE'] = 0;
			$config['BRANCH_ATS'] = 1;
			$config['BRANCH_AWG'] = 1;
			$config['BRANCH_TTS'] = 0;
		}
		##### PROLEARN #####
		if($domainName=="prolearn-jurong-staging.smartjen.com" || ($localDevName=="prolearn" && $localDevName!="")) {
			$config['BRANCH_ID'] = 8;
			$config['BRANCH_TAG'] = 'ProLearn';
			$config['BRANCH_NAME'] = 'ProLearn';
			$config['BRANCH_TITLE'] = 'ProLearn';
			$config['BRANCH_LOGO'] = 'ProLearn Logo.png';
			$config['BRANCH_LOGO_PDF'] = 'ProLearn Logo.png';
			$config['BRANCH_HEADER_PDF'] = '<htmlpageheader name="myHeader1" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
				    color: #000000; border-bottom: 1px solid #333"><tr>
				    <td width="33%" align="left">SmartGen - Questions</td>
				    <td width="33%"></td>
				    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
				    </tr>
				</table>
			</htmlpageheader>
			<htmlpageheader name="myHeader2" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
				    color: #000000; border-bottom: 1px solid #333"><tr>
				    <td width="33%" align="left">SmartGen - Answers</td>
				    <td width="33%"></td>
				    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
				    </tr>
				</table>
			</htmlpageheader>';
			$config['BRANCH_CSS'] = '#logo-size { width: 150px;height: 83px; } #nav-logo-size { max-width: 120px;}';
			$config['BRANCH_REG_FORM'] = 'register_form';
			$config['BRANCH_USER_PRIVILEGE_ACTIVE'] = 0;
			$config['BRANCH_ATS'] = 1; // 1 = ACTIVE, 0 = NOT-ACTIVE
			$config['BRANCH_TTS'] = 0; // 1 = ACTIVE, 0 = NOT-ACTIVE
			$config['BRANCH_AWG'] = 1;
		}
		##### BLUETREE #####
		if($domainName=="bluetree-staging.smartjen.com" || ($localDevName=="bluetree" && $localDevName!="")) {
			$config['BRANCH_ID'] = 3;
			$config['BRANCH_TAG'] = 'BT';
			$config['BRANCH_NAME'] = 'BlueTree';
			$config['BRANCH_TITLE'] = 'BlueTree';
			$config['BRANCH_LOGO'] = 'BlueTreeLogo.png';
			$config['BRANCH_LOGO_PDF'] = 'BlueTreeLogo.png';
			$config['BRANCH_HEADER_PDF'] = '<htmlpageheader name="myHeader1" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
				    color: #000000; border-bottom: 1px solid #333"><tr>
				    <td width="33%" align="left">SmartGen - Questions</td>
				    <td width="33%"></td>
				    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
				    </tr>
				</table>
			</htmlpageheader>
			<htmlpageheader name="myHeader2" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
				    color: #000000; border-bottom: 1px solid #333"><tr>
				    <td width="33%" align="left">SmartGen - Answers</td>
				    <td width="33%"></td>
				    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
				    </tr>
				</table>
			</htmlpageheader>';
			$config['BRANCH_CSS'] = '#logo-size { width: auto;height: 101px; } #nav-logo-size { max-width: 145px;margin-top:-18px;margin-left: 5px;}';
			$config['BRANCH_REG_FORM'] = 'register_form';
			$config['BRANCH_USER_PRIVILEGE_ACTIVE'] = 0;
			$config['BRANCH_ATS'] = 1; // 1 = ACTIVE, 0 = NOT-ACTIVE
			$config['BRANCH_TTS'] = 0; // 1 = ACTIVE, 0 = NOT-ACTIVE
			$config['BRANCH_AWG'] = 1;
		}
		##### BLUETREE VN #####
		if($domainName=="bluetreevn-staging.smartjen.com" || ($localDevName=="bluetreevn" && $localDevName!="")) {
			$config['BRANCH_ID'] = 10;
			$config['BRANCH_TAG'] = 'BlueTree VN';
			$config['BRANCH_NAME'] = 'BlueTree VN';
			$config['BRANCH_TITLE'] = 'BlueTree VN';
			$config['BRANCH_LOGO'] = 'BlueTreeLogo.png';
			$config['BRANCH_LOGO_PDF'] = 'BlueTreeLogo.png';
			$config['BRANCH_HEADER_PDF'] = '<htmlpageheader name="myHeader1" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
				    color: #000000; border-bottom: 1px solid #333"><tr>
				    <td width="33%" align="left">SmartGen - Questions</td>
				    <td width="33%"></td>
				    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
				    </tr>
				</table>
			</htmlpageheader>
			<htmlpageheader name="myHeader2" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
				    color: #000000; border-bottom: 1px solid #333"><tr>
				    <td width="33%" align="left">SmartGen - Answers</td>
				    <td width="33%"></td>
				    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
				    </tr>
				</table>
			</htmlpageheader>';
			$config['BRANCH_CSS'] = '#logo-size { width: auto;height: 101px; } #nav-logo-size { max-width: 145px;margin-top:-18px;margin-left: 5px;}';
			$config['BRANCH_REG_FORM'] = 'register_form';
			$config['BRANCH_USER_PRIVILEGE_ACTIVE'] = 0;
			$config['BRANCH_ATS'] = 1; // 1 = ACTIVE, 0 = NOT-ACTIVE
			$config['BRANCH_TTS'] = 0; // 1 = ACTIVE, 0 = NOT-ACTIVE
			$config['BRANCH_AWG'] = 1;
		}
		##### BLUETREE HK #####
		if($domainName=="bluetreehk-staging.smartjen.com" || ($localDevName=="bluetreehk" && $localDevName!="")) {
			$config['BRANCH_ID'] = 11;
			$config['BRANCH_TAG'] = 'BlueTree HK';
			$config['BRANCH_NAME'] = 'BlueTree HK';
			$config['BRANCH_TITLE'] = 'BlueTree HK';
			$config['BRANCH_LOGO'] = 'BlueTreeLogo.png';
			$config['BRANCH_LOGO_PDF'] = 'BlueTreeLogo.png';
			$config['BRANCH_HEADER_PDF'] = '<htmlpageheader name="myHeader1" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
				    color: #000000; border-bottom: 1px solid #333"><tr>
				    <td width="33%" align="left">SmartGen - Questions</td>
				    <td width="33%"></td>
				    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
				    </tr>
				</table>
			</htmlpageheader>
			<htmlpageheader name="myHeader2" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
				    color: #000000; border-bottom: 1px solid #333"><tr>
				    <td width="33%" align="left">SmartGen - Answers</td>
				    <td width="33%"></td>
				    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
				    </tr>
				</table>
			</htmlpageheader>';
			$config['BRANCH_CSS'] = '#logo-size { width: auto;height: 101px; } #nav-logo-size { max-width: 145px;margin-top:-18px;margin-left: 5px;}';
			$config['BRANCH_REG_FORM'] = 'register_form';
			$config['BRANCH_USER_PRIVILEGE_ACTIVE'] = 0;
			$config['BRANCH_ATS'] = 1; // 1 = ACTIVE, 0 = NOT-ACTIVE
			$config['BRANCH_TTS'] = 0; // 1 = ACTIVE, 0 = NOT-ACTIVE
			$config['BRANCH_AWG'] = 1;
		}
		##### KRTCJW #####
		if($domainName=="krtcjw-staging.smartjen.com" || ($localDevName=="krtcjw" && $localDevName!="")) {
			$config['BRANCH_ID'] = 2;
			$config['BRANCH_TAG'] = 'KRTCJW';
			$config['BRANCH_NAME'] = 'KRTCJW';
			$config['BRANCH_TITLE'] = 'KRTCJW';
			$config['BRANCH_HEADER_PDF'] = '<htmlpageheader name="myHeader1" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
				    color: #000000; border-bottom: 1px solid #333"><tr>
				    <td width="33%" align="left">SmartGen - Questions</td>
				    <td width="33%"></td>
				    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
				    </tr>
				</table>
			</htmlpageheader>
			<htmlpageheader name="myHeader2" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
				    color: #000000; border-bottom: 1px solid #333"><tr>
				    <td width="33%" align="left">SmartGen - Answers</td>
				    <td width="33%"></td>
				    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
				    </tr>
				</table>
			</htmlpageheader>';
			$config['BRANCH_REG_FORM'] = 'register_form';
			$config['BRANCH_USER_PRIVILEGE_ACTIVE'] = 0;
			$config['BRANCH_ATS'] = 1; // 1 = ACTIVE, 0 = NOT-ACTIVE
			$config['BRANCH_TTS'] = 0; // 1 = ACTIVE, 0 = NOT-ACTIVE
			$config['BRANCH_AWG'] = 1;
		}
		##### EP #####
		if($domainName=="engageplus-staging.smartjen.com" || ($localDevName=="engageplus" && $localDevName!="")) {
			$config['BRANCH_ID'] = 13;
			$config['BRANCH_TAG'] = 'Engage Plus';
			$config['BRANCH_NAME'] = 'Engage Plus';
			$config['BRANCH_TITLE'] = 'Engage Plus';
			$config['BRANCH_LOGO'] = 'EngagePlus.png';
			$config['BRANCH_LOGO_PDF'] = 'EngagePlus.png';
			$config['BRANCH_HEADER_PDF'] = '<htmlpageheader name="myHeader1" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
				    color: #000000; border-bottom: 1px solid #333"><tr>
				    <td width="33%" align="left">SmartGen - Questions</td>
				    <td width="33%"></td>
				    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
				    </tr>
				</table>
			</htmlpageheader>
			<htmlpageheader name="myHeader2" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
				    color: #000000; border-bottom: 1px solid #333"><tr>
				    <td width="33%" align="left">SmartGen - Answers</td>
				    <td width="33%"></td>
				    <td width="33%" align="right"><img src="'.base_url().'img/'.$config['BRANCH_LOGO_PDF'].'" style="width: 5cm"></td>
				    </tr>
				</table>
			</htmlpageheader>';
			$config['BRANCH_CSS'] = '#logo-size { width: auto;height: 101px; } #nav-logo-size { max-width: 200px;margin-top:-18px;margin-left: 5px;}';
			$config['BRANCH_REG_FORM'] = 'register_form';
			$config['BRANCH_USER_PRIVILEGE_ACTIVE'] = 0; // 1 = ACTIVE, 0 = NOT-ACTIVE
			$config['BRANCH_ATS'] = 1; // 1 = ACTIVE, 0 = NOT-ACTIVE
			$config['BRANCH_TTS'] = 0; // 1 = ACTIVE, 0 = NOT-ACTIVE
			$config['BRANCH_AWG'] = 0;
			$config['BRANCH_TID'] = 1;
		}
		// array_debug($config); exit;
		/* SMARTJEN BRANCH CONSTANT VARIABLE */
		defined('BRANCH_ID') OR define('BRANCH_ID',$config['BRANCH_ID']);
		defined('BRANCH_TAG') OR define('BRANCH_TAG',$config['BRANCH_TAG']);
		defined('BRANCH_NAME') OR define('BRANCH_NAME',$config['BRANCH_NAME']);
		defined('BRANCH_TITLE') OR define('BRANCH_TITLE',$config['BRANCH_TITLE']);
		defined('BRANCH_EMAIL') OR define('BRANCH_EMAIL',$config['BRANCH_EMAIL']);

		defined('BRANCH_LOGO') OR define('BRANCH_LOGO',$config['BRANCH_LOGO']);
		defined('BRANCH_LOGO_PDF') OR define('BRANCH_LOGO_PDF',$config['BRANCH_LOGO_PDF']);
		defined('BRANCH_HEADER_PDF') OR define('BRANCH_HEADER_PDF',$config['BRANCH_HEADER_PDF']);
		defined('BRANCH_HOME_CONTENT') OR define('BRANCH_HOME_CONTENT',$config['BRANCH_HOME_CONTENT']);
		defined('BRANCH_FOOTER') OR define('BRANCH_FOOTER',$config['BRANCH_FOOTER']);
		defined('BRANCH_CSS') OR define('BRANCH_CSS',$config['BRANCH_CSS']);
		defined('BRANCH_ADD_USER') OR define('BRANCH_ADD_USER',$config['BRANCH_ADD_USER']);
		defined('BRANCH_REG_FORM') OR define('BRANCH_REG_FORM',$config['BRANCH_REG_FORM']);
		defined('BRANCH_USER_PRIVILEGE_ACTIVE') OR define('BRANCH_USER_PRIVILEGE_ACTIVE',$config['BRANCH_USER_PRIVILEGE_ACTIVE']);
		defined('BRANCH_ATS') OR define('BRANCH_ATS',$config['BRANCH_ATS']);
		defined('BRANCH_TTS') OR define('BRANCH_TTS',$config['BRANCH_TTS']);
		defined('BRANCH_AWG') OR define('BRANCH_AWG',$config['BRANCH_AWG']);
		defined('BRANCH_TID') OR define('BRANCH_TID',$config['BRANCH_TID']);
		
	}
}
?>