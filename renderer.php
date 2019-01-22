<?php

///////////////////////////////////////////////////////////////////////////
//                                                                       //
// This file is part of Moodle - http://moodle.org/                      //
// Moodle - Modular Object-Oriented Dynamic Learning Environment         //
//                                                                       //
// Moodle is free software: you can redistribute it and/or modify        //
// it under the terms of the GNU General Public License as published by  //
// the Free Software Foundation, either version 3 of the License, or     //
// (at your option) any later version.                                   //
//                                                                       //
// Moodle is distributed in the hope that it will be useful,             //
// but WITHOUT ANY WARRANTY; without even the implied warranty of        //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         //
// GNU General Public License for more details.                          //
//                                                                       //
// You should have received a copy of the GNU General Public License     //
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.       //
//                                                                       //

use block_newblock\constants;
///////////////////////////////////////////////////////////////////////////

/**
 * Block newblock renderer.
 * @package   block_newblock
 * @copyright 2016 Justin Hunt (poodllsupport@gmail.com)
 * @author    Justin Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_newblock_renderer extends plugin_renderer_base {

    //In this function we prepare and display the content that goes in the block
    function fetch_block_content($blockid, $localconfig, $courseid){
        global $USER;


        //show our intro text
        $content = '';
        $content .= '<br />' . get_string('welcomeuser', constants::M_COMP,$USER) . '<br />';

        //show "sometext"  from our settings
        $content .= '<br />' . $localconfig->sometext . '<br />';

        //show our link to the view page
        $link = new moodle_url('/blocks/newblock/view.php',array('blockid'=>$blockid,'courseid'=>$courseid));
        $content .= html_writer::link($link, get_string('gotoviewpage', constants::M_COMP));
        return $content;
    }

    //In this function we prepare and display the content for the page
    function display_view_page($blockid, $courseid){
        global $USER;

        $content = '';
        $content .= '<br />' . get_string('welcomeuser', constants::M_COMP,$USER) . '<br />';
        $content .= $this->fetch_dosomething_button($blockid,$courseid);
        $content .= $this->fetch_triggeralert_button();

        //a page must have a header
        echo $this->output->header();
        //and of course our page content
        echo $content;
        //a page must have a footer
        echo $this->output->footer();
    }

    function fetch_dosomething_button($blockid, $courseid){
        //single button is a Moodle helper class that creates simple form with a single button for you
        $triggerbutton = new single_button(
            new moodle_url('/blocks/newblock/view.php',array('blockid'=>$blockid,'courseid'=>$courseid,'dosomething'=>1)),
            get_string('dosomething', constants::M_COMP), 'get');

        return html_writer::div( $this->render($triggerbutton),'block_newblock_triggerbutton');
    }
    function fetch_triggeralert_button(){
        //these are attributes for a simple html button.
        $attributes = array();
        $attributes['type']='button';
        $attributes['id']=html_writer::random_id('block_newblock_');
        $attributes['class']='block_newblock_triggerbutton';
        $button = html_writer::tag('button',get_string('triggeralert', constants::M_COMP),$attributes);

        //we attach an event to it. The event comes from a JS AMD module also in this plugin
        $opts=array('buttonid' => $attributes['id']);
        $this->page->requires->js_call_amd("block_newblock/triggeralert", 'init', array($opts));

        //we want to make our language strings available to our JS button too
        //strings for JS
        $this->page->requires->strings_for_js(array(
            'triggeralert_message'
        ),
            constants::M_COMP);

        //finally return our button for display
        return $button;
    }

}//end of class
