<?php
// +--------------------------------------------------------------------------+
// | Image_Graph                                                              |
// +--------------------------------------------------------------------------+
// | Copyright (C) 2003, 2004 Jesper Veggerby                                 |
// | Email         pear.nosey@veggerby.dk                                     |
// | Web           http://pear.veggerby.dk                                    |
// | PEAR          http://pear.php.net/package/Image_Graph                    |
// +--------------------------------------------------------------------------+
// | This library is free software; you can redistribute it and/or            |
// | modify it under the terms of the GNU Lesser General Public               |
// | License as published by the Free Software Foundation; either             |
// | version 2.1 of the License, or (at your option) any later version.       |
// |                                                                          |
// | This library is distributed in the hope that it will be useful,          |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU        |
// | Lesser General Public License for more details.                          |
// |                                                                          |
// | You should have received a copy of the GNU Lesser General Public         |
// | License along with this library; if not, write to the Free Software      |
// | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA |
// +--------------------------------------------------------------------------+

/**
 * Image_Graph - PEAR PHP OO Graph Rendering Utility.
 * 
 * @package Image_Graph
 * @category images
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby <pear.nosey@veggerby.dk>
 * @version $Id$
 */ 

/**
 * Include file Image/Graph.php
 */
require_once 'Image/Graph.php';

/**
 * Class for simple creation of graphs
 *   
 * @author Jesper Veggerby <pear.nosey@veggerby.dk>
 * @package Image_Graph
 */
class Image_Graph_Simple extends Image_Graph 
{
   
    /**
     * Image_Graph_Simple [Constructor]
     *
     * @param int $width The width of the graph in pixels	 
     * @param int $height The height of the graph in pixels	 
     */
    function &Image_Graph_Simple($width, $height, $plotType, $data, $title, $lineColor = 'black', $fillColor = 'white', $font = false)
    {
        parent::Image_Graph($width, $height);
        
        $plotarea =& Image_Graph::factory('plotarea');
        
        $dataset =& Image_Graph::factory('dataset', array($data));
    
        if ($font === false) {    
            $font =& Image_Graph::factory('Image_Graph_Font');
        } elseif (is_string($font)) {
            $font =& Image_Graph::factory('ttf_font', $font);
            $font->setSize(8);
        }
        
        $this->setFont($font);
        
        $this->add(
            Image_Graph::vertical(
                Image_Graph::factory('title', 
                    array(
                        $title, 
                        array('size_rel' => 2)
                    )
                ),
                $plotarea,
                10
            )   
        );

        $plotarea->addNew('line_grid', array(), IMAGE_GRAPH_AXIS_Y);        
        
        $plot =& $plotarea->addNew($plotType, &$dataset);
        $plot->setLineColor($lineColor);
        $plot->setFillColor($fillColor);
        
        $axisX =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
        $axisX->showLabel(
            IMAGE_GRAPH_LABEL_MINIMUM + 
            IMAGE_GRAPH_LABEL_ZERO + 
            IMAGE_GRAPH_LABEL_MAXIMUM
        );
        
    }
    
    /**
     * Factory method to create the Image_Simple_Graph object.
     */
    function &factory($width, $height, $plotType, $data, $title, $lineColor = 'black', $fillColor = 'white', $font = false)
    {
        return Image_Graph::factory('Image_Graph_Simple',
            array(
                $width,
                $height,
                $plotType,
                $data,
                $title,
                $lineColor,
                $fillColor,
                $font
            )
        );
    }           

}
?>