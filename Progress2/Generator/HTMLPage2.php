<?php
/**
 * The ActionDisplay class provides a HTML_Page2 form rendering.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   HTML
 * @package    HTML_Progress2
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @copyright  2005-2006 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTML_Progress2
 * @since      File available since Release 2.0.0RC1
 */

require_once 'HTML/Page2.php';

/**
 * The ActionDisplay class provides a HTML_Page2 form rendering.
 *
 * @category   HTML
 * @package    HTML_Progress2
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @copyright  2005-2006 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/HTML_Progress2
 * @since      Class available since Release 2.0.0RC1
 */

class ActionDisplay extends HTML_QuickForm_Action_Display
{
    function _renderForm(&$page)
    {
        $pageName = $page->getAttribute('name');
        $tabPreview = array_slice ($page->controller->_tabs, -2, 1);
        $tab = '  ';

        $p = new HTML_Page2(array(
                 'lineend'  => PHP_EOL,
                 'tab'      => $tab,
                 'doctype'  => 'XHTML 1.0 Strict',
                 'language' => 'en',
                 'cache'    => 'false'
        ));
        $p->disableXmlProlog();
        $p->setTitle('PEAR::HTML_Progress2 - Generator');
        $p->setMetaData('author', 'Laurent Laville');

        $styles = '
body {
  background-color: #7B7B88;
  font-family: Verdana, Arial, helvetica;
  font-size: 10pt;
}

h1 {
  color: #FFC;
  text-align: center;
}

.maintable {
  width: 100%;
  border-width: 0;
  border-style: thin dashed;
  border-color: #D0D0D0;
  background-color: #EEE;
  cellspacing: 2;
  cellspadding: 3;
}

th {
  text-align: center;
  color: #FFC;
  background-color: #AAA;
  white-space: nowrap;
}

input {
  font-family: Verdana, Arial, helvetica;
}

input.flat {
  border-style: solid;
  border-width: 2px 2px 0 2px;
  border-color: #996;
}
';

        // on preview tab, add progress bar javascript and stylesheet
        if ($pageName == $tabPreview[0][0]) {
            $pb = $page->controller->createProgressBar();
            $pb->setTab($tab);

            $p->addStyleDeclaration( $styles . $pb->getStyle() );
            $p->addScriptDeclaration( $pb->getScript() );

            $pbElement =& $page->getElement('progressBar');
            $pbElement->setText($pb->toHtml() . '<br /><br />');
        } else {
            $p->addStyleDeclaration( $styles );
        }

        $formTemplate = "\n<form{attributes}>"
                      . "\n<table style=\"font-size: 8pt;\" class=\"maintable\">"
                      . "\n{content}"
                      . "\n</table>"
                      . "\n</form>";

        $headerTemplate = "\n<tr>"
                        . "\n\t<th colspan=\"2\">"
                        . "\n\t\t{header}"
                        . "\n\t</th>"
                        . "\n</tr>";

        $elementTemplate = "\n\t<tr>"
                         . "\n\t\t<td align=\"right\" valign=\"top\" width=\"30%\">"
                         . "<!-- BEGIN required --><span style=\"color: #ff0000\">*</span><!-- END required -->"
                         . "<b>{label}</b></td>"
                         . "\n\t\t<td valign=\"top\" align=\"left\">"
                         . "<!-- BEGIN error --><span style=\"color: #ff0000\">{error}</span><br /><!-- END error -->"
                         . "\t{element}</td>"
                         . "\n\t</tr>";

        $groupTemplate = "<table><tr>{content}</tr></table>";

        $groupElementTemplate = "<td>{element}<br />"
                              . "<span style=\"font-size:10px;\">"
                              . "<span class=\"label\">{label}</span>"
                              . "</span></td>";

        $renderer =& $page->defaultRenderer();

        $renderer->setFormTemplate($formTemplate);
        $renderer->setHeaderTemplate($headerTemplate);
        $renderer->setElementTemplate($elementTemplate);
        $renderer->setGroupTemplate($groupTemplate, 'name');
        $renderer->setGroupElementTemplate($groupElementTemplate, 'name');

        $page->accept($renderer);

        $p->addBodyContent( $renderer->toHtml() );
        $p->display();
    }
}
?>