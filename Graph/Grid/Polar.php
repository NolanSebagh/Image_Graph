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
 * @subpackage Grid
 * @category images
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby <pear.nosey@veggerby.dk>
 * @version $Id$
 * @since 0.3.0dev2
 */

/**
 * Include file Image/Graph/Grid.php
 */
require_once 'Image/Graph/Grid.php';

/**
 * Display a line grid on the plotarea.
 *
 * {@link Image_Graph_Grid}
 *
 * @author Jesper Veggerby <pear.nosey@veggerby.dk>
 * @package Image_Graph
 * @subpackage Grid
 * @since 0.3.0dev2
 */
class Image_Graph_Grid_Polar extends Image_Graph_Grid
{

    /**
     * GridLines [Constructor]
     */
    function &Image_Graph_Grid_Polar()
    {
        parent::Image_Graph_Grid();
        $this->_lineStyle = 'lightgrey';
    }

    /**
     * Output the grid
     *
     * @return bool Was the output 'good' (true) or 'bad' (false).
     * @access private
     */
    function _done()
    {
        if (parent::_done() === false) {
            return false;
        }

        if (!$this->_primaryAxis) {
            return false;
        }

        $this->_driver->startGroup(get_class($this));
        
        $value = false;

        $p0 = array ('X' => '#min#', 'Y' => '#min#');
        if ($this->_primaryAxis->_type == IMAGE_GRAPH_AXIS_Y) {
            $p1 = array ('X' => '#min#', 'Y' => '#max#');
            $r0 = abs($this->_pointY($p1) - $this->_pointY($p0));
        } else {
            $p1 = array ('X' => '#max#', 'Y' => '#min#');
            $r0 = abs($this->_pointX($p1) - $this->_pointX($p0));
        }

        $cx = $this->_pointX($p0);
        $cy = $this->_pointY($p0);

        $span = $this->_primaryAxis->_axisSpan;

        while (($value = $this->_primaryAxis->_getNextLabel($value)) !== false) {
            $r = $r0 * ($value - $this->_primaryAxis->_getMinimum()) / $span;

            $this->_getLineStyle();
            $this->_driver->ellipse($cx, $cy, $r, $r);
        }
        
        $this->_driver->endGroup();
        
        return true;
    }

}
?>