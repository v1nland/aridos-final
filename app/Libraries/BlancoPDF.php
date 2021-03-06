<?php

namespace App\Libraries;

use Elibyy\TCPDF\TCPDF;

if (!defined('PAGE_MARGIN')) define('PAGE_MARGIN',10);

class BlancoPDF extends TCPDF
{

    public $content = '';


    function __construct($size = 'letter')
    {
        parent::__construct('P', 'mm', $size, true, 'UTF-8', false, false);

        $this->SetMargins(PAGE_MARGIN, PAGE_MARGIN, PAGE_MARGIN);

        $this->setHeaderCallback(function () {
            $this->Header();
        });

        $this->setFooterCallback(function () {
            $this->Footer();
        });
    }

    public function Header()
    {

    }

    public function Content()
    {
        $this->addPage();
        $this->SetFont('helvetica', '', 10);
        $this->writeHTML($this->content);
    }

    public function Footer()
    {

    }

    public function Output($name = 'doc.pdf', $dest = 'I')
    {
        $this->Content();
        parent::Output($name, $dest);
    }

}