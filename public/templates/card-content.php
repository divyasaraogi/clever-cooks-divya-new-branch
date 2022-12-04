<section class="card-list">
    <h1><?= $cardList['title'] ?></h1>
    <ul>
    <?php if (isset($cardList['cards'])) {
              foreach($cardList['cards'] as $item) {
    ?>
        <div class="card <?= $cardList['small'] ? 'small': '' ?>" cardid="<?= $item[$cardList['card_id']]?>">
            <img src="<?= $item['photo'] ?>">
            <span><?= $item['name'] ?></span>
        </div>
    <?php     }
          }
    ?>
    </ul>
</section>