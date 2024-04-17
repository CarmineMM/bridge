<?php

use Core\Translate\Lang;

foreach ($values as $key => $value) : ?>
    <?php if (is_array($value)) : ?>
        <li class="item-config">
            <p class="first-element"><?= $key ?></p>
            <ul class="full-content">
                <?php foreach ($value as $subKey => $subValue) : ?>
                    <li class="item-config">
                        <?php
                        if (is_string($subKey) || is_int($subKey)) {
                            echo "<p class='first-element'>{$subKey}</p>";
                        }
                        if (is_array($subValue)) {
                            $count = count($subValue);
                            echo "<p class='full-content'>Array({$count})</p>";
                        } else if (is_string($subValue) || is_int($subValue) || is_bool($subValue)) {
                            $subValue = is_bool($subValue) ? ($subValue ? 'true' : 'false') : $subValue;
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
            <?php
            if (isset($config)) {
                echo "<p>{$config}.{$key}</p>";
            }
            ?>
        </li>
    <?php endif; ?>
<?php endforeach; ?>

<?php

if (count($values) < 1) {
    $noResultsTranslate = Lang::_get('no-results');
    echo "<li style='text-align: \"center\"'>{$noResultsTranslate}</li>";
}
