<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <https://www.gnu.org/licenses/>.

/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2026 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */

defined('MOODLE_INTERNAL') || die;
use local_moon\library\helper\style;
use local_moon\library\helper\sub_form;
$params = $this->params;
$element = $this;
$style = $this->style;

$shape          = $params->get('shape_style', '');
$color          =   style::get_color($params->get('shape_color', ''));
$element->style->child('.wave_fill')->add_css('fill', $color['light']);
$element->style_dark->child('.wave_fill')->add_css('fill', $color['dark']);

$element->style->child('.wave_stroke')->add_css('stroke', $color['light']);
$element->style_dark->child('.wave_stroke')->add_css('stroke', $color['dark']);

if($shape){
?>
    <div class="shape-wrap uk-flex">
    <?php if($shape=='wave'){
        ?>
        <div class="tz-shape-wave uk-width-1-1 <?php echo $shape;?>">
            <svg class="wave1" xmlns="http://www.w3.org/2000/svg" width="1920" height="160" viewBox="0 0 1920 160">
                <path class="wave_fill" id="tzPath_6" data-name="Path 6" d="M1920,1080H0V905.767s118.76,81.6,216.68,0,339.84,74.4,463.68,0,339.84,50.4,429.12,0,336.96,76.8,426.24,0,384.28,0,384.28,0Z" transform="translate(0 -871.634)"/>
            </svg>
        </div>
        <?php
    }
    if($shape=='wave2'){
        ?>
        <div class="tz-shape-wave uk-width-1-1  <?php echo $shape;?>">
            <svg class="wave1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1950" height="150" viewBox="0 0 1950 150">
                <defs>
                    <filter id="tzPath_9" x="0" y="0" width="2040.667" height="150" filterUnits="userSpaceOnUse">
                        <feOffset dx="-10" dy="-10" input="SourceAlpha"/>
                        <feGaussianBlur stdDeviation="5" result="blur"/>
                        <feFlood flood-color="#3d5eaa" flood-opacity="0.051"/>
                        <feComposite operator="in" in2="blur"/>
                        <feComposite in="SourceGraphic"/>
                    </filter>
                </defs>
                <g id="tzGroup_110" data-name="Group 110" transform="translate(-210 -243)">
                    <g transform="matrix(1, 0, 0, 1, 163.88, 242.99)" filter="url(#tzPath_9)">
                        <path class="wave_fill" id="tzPath_9-2" data-name="Path 9" d="M1371.124-170.52c0-69.5-96.376-124.81-221.216-118.771-106.982,5.176-122.313,53.17-229.448,53.632-102.817.443-123.258-43.612-229.169-42.162-98.426,1.347-108.255,39.772-206.6,41.352-110.235,1.771-137.384-45.874-236.853-39.4-87.783,5.715-96.56,44.772-175.1,44.921-88.971.169-108.758-49.886-193.008-49.3-89.63.624-112.621,57.592-195.415,55.836-79.573-1.688-96.016-55.11-177.981-61.474C-583.2-292.836-636.412-210.1-639.543-183.853c261.333,26.667,240.508,5.333,1182.667,13.333C764.4-173.186,1115.057-171.187,1371.124-170.52Z" transform="translate(664.54 314.74)" fill="#fff"/>
                    </g>
                </g>
            </svg>

        </div>
        <?php
    }
    if($shape=='wave3'){
        ?>
        <div class="tz-shape-wave uk-width-1-1  <?php echo $shape;?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="78.194" height="8" viewBox="0 0 78.194 8">
                <path class="wave_stroke" id="Path_11" data-name="Path 11" d="M94.276,428.429c4.178,0,4.178,5,8.356,5s4.178-5,8.356-5,4.178,5,8.355,5,4.178-5,8.355-5,4.178,5,8.355,5,4.177-5,8.355-5,4.177,5,8.354,5,4.177-5,8.353-5,4.177,5,8.355,5" transform="translate(-92.776 -426.929)" fill="none" stroke="#3bc65b" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3"/>
            </svg>
        </div>
        <?php
    }
}
?>
    </div>
<?php