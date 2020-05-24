<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */

namespace Piwik\Plugins\BotTracking\Columns;

use Piwik\Plugin\Dimension\VisitDimension;
use Piwik\Tracker\Action;
use Piwik\Tracker\Request;
use Piwik\Tracker\Visitor;

class BotProducer extends VisitDimension
{
    /**
     * This will be the name of the column in the log_visit table if a $columnType is specified.
     * @var string
     */
    protected $columnName = 'bot_producer';

    /**
     * If a columnType is defined, we will create this a column in the MySQL table having this type. Please make sure
     * MySQL will understand this type. Once you change the column type the Piwik platform will notify the user to
     * perform an update which can sometimes take a long time so be careful when choosing the correct column type.
     * @var string
     */
    protected $columnType = "VARCHAR(255)";

    /**
     * The type of the dimension is automatically detected by the columnType. If the type of the dimension is not
     * detected correctly, you may want to adjust the type manually. The configured type will affect how the dimension
     * is formatted in the UI.
     * @var string
     */
    protected $type = self::TYPE_TEXT;

    /**
     * The name of the dimension which will be visible for instance in the UI of a related report and in the mobile app.
     * @return string
     */
    protected $nameSingular = 'BotTracking_BotProducer';

    /**
     * By defining a segment a user will be able to filter their visitors by this column. For instance
     * show all reports only considering users having more than 10 achievement points. If you do not want to define a
     * segment for this dimension, simply leave the name empty.
     */
    protected $segmentName = 'botProducer';

    protected $acceptValues = 'Here you should explain which values are accepted/useful for segments: Any number, for instance 1, 2, 3 , 99';

    /**
     * The onNewVisit method is triggered when a new visitor is detected. This means here you can define an initial
     * value for this user. By returning boolean false no value will be saved. Once the user makes another action the
     * event "onExistingVisit" is executed. That means for each visitor this method is executed once. If you do not want
     * to perform any action on a new visit you can just remove this method.
     *
     * @param Request $request
     * @param Visitor $visitor
     * @param Action|null $action
     * @return mixed|false
     */
    public function onNewVisit(Request $request, Visitor $visitor, $action)
    {
        return $request->getMetadata("BotTracking", "botProducer");
    }


    /**
     * Sometimes you may want to make sure another dimension is executed before your dimension so you can persist
     * a value depending on the value of other dimensions. You can do this by defining an array of dimension names.
     * If you access any value of any other column within your events, you should require them here. Otherwise those
     * values may not be available.
     * @return array
     * public function getRequiredVisitFields()
     * {
     * return array('idsite', 'server_time');
     * }
     */
}
