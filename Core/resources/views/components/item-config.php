<?php foreach ($values as $key => $value) : ?>
    <?php if (is_array($value)) : ?>
    <?php else : ?>
        <li class="item-config">
            <p class="first-element"><?= $key ?></p>
            <p class="medium-content"><?= is_bool($value) ? ($value ? 'true' : 'false') : $value ?></p>
            <p><?= $config ?>.<?= $key ?></p>
        </li>
    <?php endif; ?>
<?php endforeach; ?>