<div class="manage">
    <div class="d-flex-between">
        <h1>Manage Ingredients</h1>
        <button class="btn-h1" onclick="addIng()">New Ingredient</button>
    </div>
    <div class="modal">
        <div class="modal-content">
            <form name='ing' class="modal-body" action="api.php?req=ing" method="POST">
                <span></span>
                <label for="grp">Grouping</label>
                <select id="cat" name="cat">
                <?php foreach ($ings as $cat => $list) {   ?>
                    <option value="<?= $cat ?>"><?= $cat ?></option>
                <?php } ?>
                </select>
                <input type="text" id="grp" name="grp">
                <label for="ing">Ingredient</label>
                <input type="text" id="ing" name="ing">
                <input type="hidden" id="ingId" name="ingId" value="">
                <div class="modal-footer d-flex-between w-100">
                    <input class="btn" type="submit" value="Save">
                    <button class="bg-6 btn-secondary d-none" onclick="deleteIng(event)">Delete</button>
                    <button class="bg-6 btn-secondary" onclick="modal.hide(event)">Cancel</button>
                </div>
            </form>
        </div>
    </div>
<?php foreach ($ings as $cat => $list) {   ?>
    <div class="content <?= str_replace(' ', '-', $cat) ?>">
        <h3><?= $cat ?></h3>
        <ul class="filter-items">
        <?php foreach ($list as $item) { ?>
            <li>
                <button id="<?= $item['part_id'] ?>" name="<?= $item['part_name'] ?>" grp="<?= $item['part_grp'] ?>" onclick="editIng(this)">
                    <?= $item['part_name'] ?>
                </button>
            </li>
        <?php } ?>
        </ul>
    </div>
<?php } ?>
</div>