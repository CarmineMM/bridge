<?php foreach ($values as $key => $value) : ?>
    <?php if (is_array($value)) : ?>
        <li class="item-config">
            <p class="first-element"><?= $key ?></p>
            <ul class="full-content">
                <?php foreach ($value as $subKey => $subValue) : ?>
                    <li class="item-config">
                        <?php
                        if (is_string($subKey)) {
                            echo "<p class='first-element'>{$subKey}</p>";
                        }
                        if (is_array($subValue)) {
                            $count = count($subValue);
                            echo "<p class='full-content'>Array({$count})</p>";
                        } else if (is_string($subValue)) {
                            echo "<p class='medium-content'>{$subValue}</p>";
                        }
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php else : ?>
        <li class="item-config">
            <p class="first-element"><?= $key ?></p>
            <p class="medium-content"><?= is_bool($value) ? ($value ? 'true' : 'false') : $value ?></p>
            <p><?= $config ?>.<?= $key ?></p>
        </li>
    <?php endif; ?>
<?php endforeach; ?>