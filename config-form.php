<?php $view = get_view(); 
$collections = get_records('Collection', array(), 1000);
set_loop_records('collections', $collections);

$items = get_records('Item', array(), 30000);
$all_ids = array();
set_loop_records('items', $items);
foreach (loop('items') as $item): 
$idnums = $item->id;
$all_ids[] = $idnums;
endforeach;
$last_record = max($all_ids);
$first_record = min($all_ids);
$part_size = 1500;
$limit = $last_record;

for($i=$first_record;$i<=$limit;$i=$i+$part_size)
{
    $j = $i + $part_size;
    if($j > $limit) {
        $j = $limit;    
    }
    $resultArray[] = $i.','.$j;
}
?>


<div id="csv-export-settings">
<h2><?php echo __('Element Sets to include'); ?></h2>

    <div class="field">
        <div class="inputs five columns omega">
            <p class="explanation">
                <?php echo __('Check the box below to include a given element set in exports.'); ?>
            </p>
            <ul class="checkboxes">
                <?php foreach($elementSetsAll as $elementSet):?>
                  <li style="list-style-type: none">
                    <?php echo $view->formCheckbox("elementSets[{$elementSet->id}]", null, array('checked' => array_key_exists($elementSet->id, $settings['elementSets']))); ?>
                    <?php echo __($elementSet->name); ?>
                  </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>


<div class="field">
    <h3>CSV file download links for all items. (grouped in 1,500 record chunks)</h3>
    <ol>
        
<?php 
foreach ($resultArray as $value) {
$value = explode(",",$value);
$diff = 1;
$range1 = $value[0];
$range2 = $value[1] - $diff;
echo "<li><a id=\"start_$range1\" href=\"/admin/csv-export/export/csv?search=&advanced%5B0%5D%5Bjoiner%5D=and&advanced%5B0%5D%5Belement_id%5D=&advanced%5B0%5D%5Btype%5D=&advanced%5B0%5D%5Bterms%5D=&range=" . $range1 . "-" . $range2 . "&collection=&type=&user=&tags=&public=&featured=&submit_search=Search+for+items&hits=0\" onclick=\"clickInline$range1()\" >record set " . $range1 . "-" . $range2 . "</a></li>";

echo "<script>var onclickHeadline$range1 = document.getElementById(\"start_$range1\");function clickInline$range1() {    
    start_$range1.innerHTML = \"CLICKED!\";}</script>";
}
echo "<li><a id=\"onclickHeadline\" href=\"/admin/csv-export/export/csv?search=&advanced%5B0%5D%5Bjoiner%5D=and&advanced%5B0%5D%5Belement_id%5D=&advanced%5B0%5D%5Btype%5D=&advanced%5B0%5D%5Bterms%5D=&range=" . $last_record . "&collection=&type=&user=&tags=&public=&featured=&submit_search=Search+for+items&hits=0\">last record " . $last_record . "</a></li>";

echo "<script>var onclickHeadline = document.getElementById(\"onclickHeadline\");function clickInline() {onclickHeadline.innerHTML = \"CLICKED!\";}</script> ";

?>
        
    </ol>
        </div>

<div class="field">
    <h3>CSV file download links for items by Project (XML download for Project record)</h3>
    <ol>

<?php foreach (loop('collections') as $collection): 
$plink = link_to_collection();
$xlink = link_to_collection();
  
$xlink = str_replace("\">","?output=omeka-xml\">",$xlink);

$plink = str_replace("/admin/collections/show/","/admin/csv-export/export/csv?search=&advanced%5B0%5D%5Bjoiner%5D=and&advanced%5B0%5D%5Belement_id%5D=&advanced%5B0%5D%5Btype%5D=&advanced%5B0%5D%5Bterms%5D=&range=&collection=",$plink);

$plink = str_replace("\">","&type=&user=&tags=&public=&featured=&submit_search=Search+for+items&hits=0\">",$plink);

?>
 


    <li><?php echo $plink; ?>&nbsp;( <?php echo $xlink; ?> )</li>
    
    <?php endforeach; ?>
    </ol>
    
</div>
