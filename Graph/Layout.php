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
 * @subpackage Layout     
 * @category images
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby <pear.nosey@veggerby.dk>
 * @version $Id$
 */ 

/**
 * Include file Image/Graph/Plotarea/Element.php
 */
require_once 'Image/Graph/Plotarea/Element.php';

/**
 * Defines an area of the graph that can be layout'ed.
 * 
 * Any class that extends this abstract class can be used within a layout on the canvas.
 *  
 * @author Jesper Veggerby <pear.nosey@veggerby.dk>
 * @package Image_Graph
 * @subpackage Layout
 * @abstract
 */
class Image_Graph_Layout extends Image_Graph_Plotarea_Element 
{
    
    /**
     * Has the coordinates already been updated?
     * @var bool
     * @access private
     */
    var $_updated = false; 

    /**
     * Alignment of the area for each vertice (left, top, right, bottom)
     * @var array
     * @access private
     */
    var $_alignSize = array ('left' => 0, 'top' => 0, 'right' => 0, 'bottom' => 0);

    /**
     * Image_Graph_Layout [Constructor]
     */
    function &Image_Graph_Layout()
    {
        parent::Image_Graph_Element();
        $this->_padding = 2;
    }

    /**
     * Resets the elements
     *
     * @access private
     */
    function _reset()   
    {
        parent::_reset();
        $this->_updated = false;
    }
    
    /**
     * (Add basic description here)
     *
     * @since 0.3.0dev2
     * @access private
     */
    function _calcEdgeOffset($alignSize, $offset, $total, $multiplier) {
        if ($alignSize['unit'] == 'percentage') {
            return $offset + $multiplier * ($total * $alignSize['value'] / 100);
        } elseif ($alignSize['unit'] == 'pixels') {
            if (($alignSize['value'] == 'auto_part1') || ($alignSize['value'] == 'auto_part2')) {
                $alignSize['value'] = $multiplier * $this->_parent->_getAbsolute($alignSize['value']);                                   
            }
            if ($alignSize['value'] < 0) {
                return $offset + $multiplier * ($total + $alignSize['value']);
            } else {
                return $offset + $multiplier * $alignSize['value'];
            }
        }
        return $offset;
    }
            
    /**
     * Calculate the edges
     *
     * @access private
     */
    function _calcEdges()
    {
        if ((is_array($this->_alignSize)) && (!$this->_updated)) {
            $left = $this->_calcEdgeOffset(
                $this->_alignSize['left'],
                $this->_parent->_fillLeft(),
                $this->_parent->_fillWidth(),
                +1
            );
            $top = $this->_calcEdgeOffset(
                $this->_alignSize['top'],
                $this->_parent->_fillTop(),
                $this->_parent->_fillHeight(),
                +1
            );
            $right = $this->_calcEdgeOffset(
                $this->_alignSize['right'],
                $this->_parent->_fillRight(),
                $this->_parent->_fillWidth(),
                -1
            );
            $bottom = $this->_calcEdgeOffset(
                $this->_alignSize['bottom'],
                $this->_parent->_fillBottom(),
                $this->_parent->_fillHeight(),
                -1
            );
                
/*            $left = 
                $this->_parent->_fillLeft() + ($this->_alignSize['left'] <= 1 ? 
                    $this->_parent->_fillWidth() * $this->_alignSize['left'] : 
                    $this->_alignSize['left']
                );
                
            $top = $this->_parent->_fillTop() + ($this->_alignSize['top'] <= 1 ? 
                $this->_parent->_fillHeight() * $this->_alignSize['top'] : 
                $this->_alignSize['top']
            );
            
            $right = $this->_parent->_fillRight() - ($this->_alignSize['right'] <= 1 ? 
                $this->_parent->_fillWidth() * $this->_alignSize['right'] : 
                $this->_alignSize['right']
            );
            
            $bottom = $this->_parent->_fillBottom() - ($this->_alignSize['bottom'] <= 1 ? 
                $this->_parent->_fillHeight() * $this->_alignSize['bottom'] : 
                $this->_alignSize['bottom']
            );*/
            
            $this->_setCoords(
                $left + $this->_padding, 
                $top + $this->_padding, 
                $right - $this->_padding, 
                $bottom - $this->_padding
            );
        }
    }

    /**
     * Update coordinates
     *
     * @access private
     */
    function _updateCoords()
    {
        $this->_calcEdges();
        parent::_updateCoords();
    }

    /**
     * Pushes an edge of area a specific distance 'into' the canvas
     *
     * @param int $edge The edge of the canvas to align relative to
     * @param int $size The number of pixels or the percentage of the canvas total size to occupy relative to the selected alignment edge
     * @access private
     */
    function _push($edge, $size = '100%')
    {
        if (ereg("([0-9]*)\%", $size, $result)) {
            $this->_alignSize[$edge] = array(
                'value' => min(100, max(0, $result[1])),
                'unit' => 'percentage'
            );
        } else {
            $this->_alignSize[$edge] = array(
                'value' => $size,
                'unit' => 'pixels'                
            );
        }
    }

    /**
     * Sets the coordinates of the element   
     *
     * @param int $left The leftmost pixel of the element on the canvas 
     * @param int $top The topmost pixel of the element on the canvas 
     * @param int $right The rightmost pixel of the element on the canvas 
     * @param int $bottom The bottommost pixel of the element on the canvas 
     * @access private
     */
    function _setCoords($left, $top, $right, $bottom)
    {
        parent::_setCoords($left, $top, $right, $bottom);
        $this->_updated = true;
    }

    /**
     * Returns the calculated "auto" size   
     *
     * @return int The calculated auto size 
     * @access private
     */
    function _getAutoSize()
    {
        return false;
    }

}

?>