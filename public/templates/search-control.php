<div class="search">
    <div class="filter">
    <?php 
          ksort($filters);
          foreach ($filters as $key => $item) {  
    ?>
        <button cat="<?= $key ?>"><?= $key ?></button>
    <?php } ?>
        <button cat="cook-time">cook time</button>
        <button cat="calories">calories</button>
    </div>
<?php foreach ($filters as $key => $item) {  ?>
    <div class="filter-items <?= $key ?> collapse">
    <?php foreach ($item as $row) {  
            $field = $row['tag_name'];
            if (!isset($field)) {
                $field = $row['part_name'];
            }
    ?>
        <button val="<?= $field ?>"><i class="fa fa-plus" aria-hidden="true"></i><?= $field ?></button>
    <?php } ?>
    </div>
<?php } ?>
    <div class="filter-items cook-time collapse">
        <button val="5m"><i class="fa fa-plus" aria-hidden="true"></i>5 min</button>
        <button val="10m"><i class="fa fa-plus" aria-hidden="true"></i>10 min</button>
        <button val="15m"><i class="fa fa-plus" aria-hidden="true"></i>15 min</button>
        <button val="20m"><i class="fa fa-plus" aria-hidden="true"></i>20 min</button>
        <button val="30m"><i class="fa fa-plus" aria-hidden="true"></i>30 min</button>
        <button val="60m"><i class="fa fa-plus" aria-hidden="true"></i>1 hour</button>
        <button val="120m"><i class="fa fa-plus" aria-hidden="true"></i>2 hours</button>
    </div>
    <div class="filter-items calories collapse">
        <button val="100cal"><i class="fa fa-plus" aria-hidden="true"></i>100 cal</button>
        <button val="300cal"><i class="fa fa-plus" aria-hidden="true"></i>300 cal</button>
        <button val="500cal"><i class="fa fa-plus" aria-hidden="true"></i>500 cal</button>
        <button val="1000cal"><i class="fa fa-plus" aria-hidden="true"></i>1 kcal</button>
        <button val="1500cal"><i class="fa fa-plus" aria-hidden="true"></i>1.5 kcal</button>
        <button val="2000cal"><i class="fa fa-plus" aria-hidden="true"></i>2 kcal</button>
        <button val="3000cal"><i class="fa fa-plus" aria-hidden="true"></i>3 kcal</button>
    </div>
    <div class="search-bar">
        <i class="icon fa fa-search" aria-hidden="true" onclick="startSearch(this)"></i>
        <input placeholder="Search for ingredients / recipes" type="text" id="search-bar">
    </div>
</div>