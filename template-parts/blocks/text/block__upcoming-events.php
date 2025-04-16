<?php 
    $fields = $args;

    //$title_group = $fields['_titles'];
    //$image = $fields['image'];


    // Nifty null check
    $field_names = array('subtitle', 'title', 'image', 'subtitle_color', 'text_color');

    foreach($field_names as $field_name) {
        if(isset($fields[$field_name])) {
            $$field_name  = $fields[$field_name];
        } else {
            $$field_name = null;
        }
    }

    $upcoming_events = get_field('event_list', 'option');

    usort($upcoming_events, 'sortByDate');

    $filtered_events = [];

    $threshold_date = new DateTime();
    $threshold_date->modify('-5 days'); // 5 days ago

    foreach($upcoming_events as $event):
        $event_date = DateTime::createFromFormat('d/m/Y', $event['event_date']);

        // If the date is valid and not older than 5 days, keep it
        if ($event_date && $event_date >= $threshold_date) {
            $filtered_events[] = $event;
        }
    endforeach;

?>
<div class="xy-col text-wrapper" data-xy-col="12" data-xy-start="auto">
    <div class="heading-group has-primary-color">
        <?= conditionally_output_field($subtitle, '<h5 class="'.$subtitle_color.'">', '</h5>'); ?>
        <?= conditionally_output_field($title, '<h2 class="'.$text_color.'">', '</h2>'); ?>
    </div>
</div>
<div class="xy-col xy-grid" data-xy-col="12" data-xy-start="auto">
    <div class="xy-col events-image-col"  data-xy-col="xl-5 lg-5 md-12" data-xy-start="auto" >
        <div class="events-image-wrap">
            <?php 
            if(!empty($filtered_events)):
                $event_index = 0;
                foreach($filtered_events as $event):
                    $event_image = $event['image'];
                    $event_title = $event['title'];
                    $classes = 'events-image event-image-with-index'; 
                    
                    if(!empty($event_image)) {
                        echo wp_get_attachment_image($event_image, 'full', 0, array('title'=> $event_title, 'class'=> $classes, 'data-event-index' => $event_index, 'style' => '--_event-index: '. $event_index) );
                    }
                    $event_index++;
                endforeach;
            endif;
            ?>
            <?= $image ?>
        </div>
    </div>
    <div class="xy-col" data-xy-col='xl-6 lg-6 md-12' data-xy-start='xl-7 lg-7 md-auto'>
        <div class="events-list">
            <?php
             if(!empty($filtered_events)):
                $event_index = 0;
                foreach($filtered_events as $event):
                    $title = $event['title'];
                    $desecription = $event['description'];
                    $link = $event['link'];

                    $date =  DateTime::createFromFormat('d/m/Y', $event['event_date']);
                    
                    $day = $date->format('d');
                    $month = $date->format('M');
                    $year = $date->format('Y');
                    ?>
            <div class="event-item" data-event-index="<?= $event_index ?>">
                <div class="event-date has-primary-dark-background-color has-white-color">
                    <div class="event-date__day"><?= $day; ?></div>
                    <div class="event-date__spacer"></div>
                    <div class="event-date__month"><?= $month; ?></div>
                </div>
                <div class="event-text">
                    <div class="event-text__title h5 bold">
                        <?php if(!empty($link)): ?><a href="<?= $link ?>" class="" target="_blank"><?php endif; ?>
                            <?= $title; ?>
                        <?php if(!empty($link)): ?></a><?php endif; ?>
                    </div>
                    <div class="event-text__description">
                        <?= $desecription; ?>
                    </div>
                </div>
            </div>
                    <?php
                    $event_index++;
                endforeach;
            else :
                // If no events are found, display a message
                echo '<p>No upcoming events.</p>';

            endif; ?>
        </div>
    </div>
</div>