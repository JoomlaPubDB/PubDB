<?php
/**
 * @version     CVS: 0.0.5
 * @package     com_pubdb
 * @subpackage  mod_pubdb
 * @author      Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright   2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license     GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */
defined('_JEXEC') or die;
$elements = ModPubdbHelper::getList($params);

?>

<?php if (!empty($elements)) : ?>
    <table class="table">
        <thead>
        <th><tr></tr></th>
        </thead>

        <?php foreach ($elements as $element) : ?>
            <tr>
                <td><?php echo $element; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif;