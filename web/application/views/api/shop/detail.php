<?= '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" ?>
<shop>
<status><?= $status ?></status>
<name><?= h($name) ?></name>
<post_code><?= $post_code ?></post_code>
<address><?= h($address . $building) ?></address>
<tel1><?= $tel1 ?></tel1>
<tel2><?= $tel2 ?></tel2>
<campaign><?= h($campaign) ?></campaign>
<campaign_body><?= h($campaign_body) ?></campaign_body>
<hours><?= h($parking_detail) ?></hours>
<holiday><?= h($barrier_free_detail) ?></holiday>

<parking><?= h($parking_detail) ?></parking>
<barrier_free><?= h($barrier_free_detail) ?></barrier_free>
<kids><?= h($kids_detail) ?></kids>
<classes><?= h($classes_detail) ?></classes>
<payment><?= h($payment_detail) ?></payment>
<repair><?= h($repair_detail) ?></repair>
<bookmark><?= (int)$bookmark ?></bookmark>
<review>
<public><?= $review_flag ?></public>
<count><?= $review['cnt'] ?></count>
<score><?= ($review['score_sum']) ? floor($review['score_sum'] / $review['cnt']) : '' ?></score>
</review>
</shop>