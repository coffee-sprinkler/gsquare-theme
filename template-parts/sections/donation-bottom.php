<?php
  $bottom = get_field('bottom');
  $image = $bottom['image'];
  $top_text = $bottom['top_text'];
  $bottom_text = $bottom['bottom_text'];

?>

<section class="bottom">
  <div class="container">
    <div class="two-column">
      <div class="col">

        <?php if ($image): ?>
          <img src="<?= $image['url'] ?>" alt="<?= $image['alt'] ?>">
        <?php endif; ?>

      </div>
      <div class="col">
        <div class="quotation-wrapper">

          <?php if ($top_text): ?>
            <h4><?= $top_text ?></h4>
          <?php endif; ?>

          <?php if ($bottom_text): ?>
            <p><?= $bottom_text ?></p>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</section>