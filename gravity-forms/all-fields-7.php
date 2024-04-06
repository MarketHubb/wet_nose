<?php

// Most email clients do not support <style> blocks. We'll define our styles here and output them inline in the markup below.
$styles = array(
	'ul'         => 'border-top: 1px solid #eee;margin:0;padding:0;',
	'li'         => 'border-bottom: 1px solid #eee; padding: 10px 10px 15px; margin: 0; list-style-type: none; overflow: hidden;',
	'span'       => 'vertical-align: top; display: block; margin-left:30%;',
	'span.label' => 'float: left; vertical-align: top; font-weight: bold; width: 30%;'
);

// Make a back-up of the styles we've just defined. This allows us to make temporary changes to the styles below and then
// reset the styles for the next item.
$reset_styles = $styles;
?>
<?php
function extractFontText($text)
{
	$result = array();
	$pattern = '/<font[^>]*>(.*?)<\/font>/si';

	preg_match_all($pattern, $text, $matches);

	if (isset($matches[1])) {
		$result = array_map(function ($item) {
			return html_entity_decode(strip_tags(trim($item)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
		}, $matches[1]);
	}

	$remove_labels_array = ["My dogs name is:", "Recipes Clean"];

	// Remove items from the array
	$filteredArray = array_filter($result, function ($item) use ($remove_labels_array) {
		return !in_array($item, $remove_labels_array);
	});

	// Reset the array keys
	$resultArray = array_values($filteredArray);

	return $resultArray;
}
$items_clean = extractFontText($items[0]['value']);
?>
<?php if (isset($items_clean) && !empty($items_clean)) { ?>
	<div class="container mx-auto">
		<div class="grid grid-cols-<?php echo (count($items_clean) / 2); ?>">
			<?php $i = 0; ?>
			<?php while ($i <= count($items_clean)) { ?>
				<div>
					<p class="font-bold text-base text-inputLabel dog-name"><?php echo $items_clean[$i]; ?></p>
					<p class="text-base text-inputLabel"><?php echo $items_clean[$i + 1]; ?></p>
				</div>
				<?php $i += 2; ?>
			<?php } ?>
		</div>
	</div>
<?php } else { ?>

<ul class="gf-all-fields" style="<?php echo $styles['ul']; ?>">
	<?php foreach ($items as $item) :

		// Get field object for use in template.
		$field = isset($item['field']) ? $item['field'] : new GF_Field();

		// Don't show pricing fields (just like GF default {all_fields}).
		if (GFCommon::is_pricing_field($field)) {
			continue;
		}

		// Change the style a bit for Section fields.
		if ($field->get_input_type() == 'section') {
			$styles['li'] .= 'background-color:#f7f7f7; padding-bottom: 10px;';
		}

		// Add the field type as a CSS class for every field to make styling specific elements easier.
		$css_class = isset($field) ? 'field-type-' . $field->type : '';

	?>

		<li class="<?php echo $css_class; ?>" style="<?php echo $styles['li']; ?>">
			<span style="<?php echo $styles['span.label']; ?>"><?php echo $item['label']; ?></span>
			<span style="<?php echo $styles['span']; ?>"><?php echo $item['value']; ?></span>
		</li>

	<?php
		// Reset any temporary changes we made to our core styles.
		$styles = $reset_styles;
	endforeach; ?>
</ul>
<?php } ?>