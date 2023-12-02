<?php
  $title = get_field('title');
  $desc = get_field('desc');
  $donation = get_field('donation');
  $donation_title = $donation['title'];
  $donation_total = $donation['total_donations_needed'];
  $donation_desc = $donation['description'];

  $formatted_total = '$**,***.**';

  $formatted_donation_total = format_amount($donation_total);
  

?>

<section class="donation">
  <div class="container">
    <?php if ($title) :?>
      <h1><?= $title ?></h1>
    <?php endif; ?>

    <?php if ($desc) :?>
      <p><?= $desc ?></p>
    <?php endif; ?>

    <?php if ($donation) :?>
      <div class="donation-form-wrapper">

        <div class="display-donation">

          <?php if ($donation_title) :?>
            <h3 class="donation__title">
              <?= $donation_title ?>
            </h3>
          <?php endif; ?>

          <?php if ($donation_total) :?>
            <p class="donation__amount_over_total">
                <span class="given-donation"  id="dynamic-donation-content"><?= $formatted_total ?></span>
                <span class="total-donation-needed">of <?= $formatted_donation_total ?> raised</span>
            </p>
          <?php endif; ?>

          <div id="progress-bar" class="progress-bar" data-progress-val="<?= $formatted_total ?>" data-progress-total="<?= $donation_total ?>"></div>

          <div class="amount">
            <label for="donate-amount">$</label>
            <input type="text" name="donate-amount" id="donate-amount" value="10,000.00">
          </div>

          <?php if ($donation_desc) :?>
            <p class="donation__desc">
              <?= $donation_desc ?>
            </p>
          <?php endif; ?>

        </div>
        
        <div class="form-donation">
          <form id="donation-form" method="post">
            
            <div class="method-wrapper">
              <h2 class="title">Select payment method</h2>
              <hr>
              <div class="methods">
                <div class="method-group">
                  <input type="radio" name="payment-method" id="paypal" value="paypal" checked>
                  <label for="paypal">Paypal</label>
                </div>
                <div class="method-group">
                  <input type="radio" name="payment-method" id="offline" value="offline">
                  <label for="offline">Offline donation</label>
                </div>
              </div>
            </div>

            <div class="user-wrapper">
              <h2 class="title">Personal info</h2>
              <hr>
              <div class="group">
                <div class="even-columns">
                  <input name="firstName" id="firstName" type="text" class="form-control" placeholder="First name*" aria-label="First name" required>
                </div>
                <div class="even-columns">
                  <input name="lastName" id="lastName"  type="text" class="form-control" placeholder="Last name*" aria-label="Last name" required>
                </div>
              </div>
              <div class="group">
                <div class="even-columns">
                  <input name="email" id="email"  type="email" class="form-control" placeholder="Email*" autocomplete="email" aria-label="Email" required>
                </div>
                <div class="even-columns">
                  <input name="phone" id="phone" type="tel" class="form-control" placeholder="Phone*" autocomplete="phone" aria-label="Phone" required>
                </div>
              </div>
              <div class="group">
                <div class="col">
                  <label for="donated-amount">Donation total:</label>
                  <input name="donated-amount" id="donated-amount"  type="text" class="form-control" aria-label="donation" value="$10,000.00" required>
                </div>
              </div>
              <div class="group">
                <button type="submit" name="donate-now">DONATE NOW</button>
              </div>
            </div>
          </form>
        </div>
        
      </div>
    <?php endif; ?>
  </div>
</section>