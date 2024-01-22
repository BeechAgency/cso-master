<?php 
    $fields = $args;
    $tabs = get_sub_field('_tabs');
?>
<div class="xy-col text-wrapper" data-xy-col="12">
    <div class="heading-group">
        <?= conditionally_output_field($fields['subtitle'], '<h5>', '</h5>'); ?>
        <?= conditionally_output_field($fields['title'], '<h2>', '</h2>'); ?>
        <?= apply_filters('the_content', $fields['content']); ?>
    </div>
</div>
<div class="xy-col xy-grid tab-wrapper" data-xy-col="12" data-tab-group="<?= 'tab_content_'.$fields['c']?>">
    <ul class="xy-col tab-header" data-xy-col="xl-4 lg-4 md-5 sm-auto">
        <?php 
            $i = 0;
            foreach($tabs as $tab) : 
        ?>
        <li class="tab-header__item <?= $i === 0 ? 'active' : '' ?>" id="<?= 'tab_item_'.$fields['c'].'__'.$i;?>" data-tab-id="<?= 'tab_content_'.$fields['c'].'__'.$i;?>">
            <a href="#<?= 'tab_content_'.$fields['c'].'__'.$i;?>">
                <?= $tab['title'] ?> 
                <svg xmlns="http://www.w3.org/2000/svg" width="15.427" height="28.379" viewBox="0 0 15.427 28.379">
                    <g id="Arrow_2_right" data-name="Arrow 2 right" transform="translate(0.528 0.533)">
                        <path id="Path_402" data-name="Path 402" d="M546.6,278l13.831,13.713-13.831,13.6" transform="translate(-546.602 -278)" fill="none" stroke="#000" stroke-width="1.5"/>
                    </g>
                </svg>
            </a>
        </li>
        <?php $i++;
            endforeach; ?>
    </ul>

    <div class="xy-col tab-holder" data-xy-col="xl-8 lg-8 md-7 sm-auto" data-xy-start="xl-5 lg-5 md-6 sm-auto">
    <?php 
        $i = 0;
        foreach($tabs as $tab) : 
        ?>
        <div class="tab-content <?= $i === 0 ? 'active' : '' ?>" id="<?= 'tab_content_'.$fields['c'].'__'.$i;?>" data-item-tab="<?= 'tab_item_'.$fields['c'].'__'.$i;?>">
            <?= apply_filters('the_content', $tab['content']) ?>
            <?= wp_get_attachment_image($tab['image'],'full'); ?>
        </div>
    <?php 
        $i++;
        endforeach; ?>
</div>