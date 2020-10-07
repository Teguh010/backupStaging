<?php

/**

 * *

 *  * Created by PhpStorm.

 *  * User: boonkhailim

 *  * Year: 2017

 *

 */



if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class M_pdf {

	function __construct()

    {

        /**

         * Create a new PDF document

         *

         * @param string $mode

         * @param string $format

         * @param int $font_size

         * @param string $font

         * @param int $margin_left

         * @param int $margin_right

         * @param int $margin_top (Margin between content and header, not to be mixed with margin_header - which is document margin)

         * @param int $margin_bottom (Margin between content and footer, not to be mixed with margin_footer - which is document margin)

         * @param int $margin_header

         * @param int $margin_footer

         * @param string $orientation (P, L)

         */



        // include_once APPPATH.'third_party/mpdf/mpdf.php';
        // include_once APPPATH.'third_party/mpdf/src/Mpdf.php';
        
        $path = $_SERVER['DOCUMENT_ROOT'];
        include_once $path . '/staging/application/vendor/autoload.php';

        // if ($params == NULL)

        // {

        //     $param = '"en-GB-x","A4","","",10,10,10,10,3,3';        

        // }

        

        $CI = & get_instance();

        // $CI->mpdf = new mPDF($param);

        $CI->mpdf = new \Mpdf\Mpdf();

        $CI->mpdf->setAutoTopMargin = 'pad';

        $CI->mpdf->setAutoBottomMargin = 'pad';


    }



    function load($param=NULL)

    {

        

        

        

    }

 }