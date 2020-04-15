<?php
/**
 * @version     CVS: 0.0.3
 * @package     com_pubdb
 * @subpackage  mod_pubdb
 * @author      Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright   2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
defined('_JEXEC') or die;
$elements = ModPubdbHelper::getList($params);

$tableField = explode(':', $params->get('field'));
$table_name = !empty($tableField[0]) ? $tableField[0] : '';
$field_name = !empty($tableField[1]) ? $tableField[1] : '';
?>

<?php if (!empty($elements)) : ?>
	<table class="table">
		<?php foreach ($elements as $element) : ?>
			<tr>
				<th><?php echo ModPubdbHelper::renderTranslatableHeader($table_name, $field_name); ?></th>
				<td><?php echo ModPubdbHelper::renderElement(
						$table_name, $params->get('field'), $element->{$field_name}
					); ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif;
