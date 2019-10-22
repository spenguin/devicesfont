<?php
/**
 * Display the Font Familes
 */
?>
<?php $url  = getThisUrl(); ?>

<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2>Fontseller Fonts</h2>
    <table class="wp-list-table widefat fonts">
        <thead>
            <tr>
                <td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select all</label><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="name" class="manage-column column-name column-primary">Font Family</th>
                <th scope="col" id="description" class="manage-column column-standard">Standard</th>
                <th scope="col" id="description" class="manage-column column-representative">Representative Font</th>	
            </tr>
        </thead>        
        <?php 
            foreach( $fonts as $font ): ?>
                <tr>
                    <td></td>
                    <td><?php echo $font['title']; ?></td>
                    <td><?php echo $font['standard']; ?></td>
                    <td><?php echo $font['repFont']; ?></td>
                </tr>
        <?php endforeach; ?>
    </table>
    <div class="font-families_pagination">
        <?php renderPagination( $pageNo, $pages  );  ?>
    </div>    
</div>