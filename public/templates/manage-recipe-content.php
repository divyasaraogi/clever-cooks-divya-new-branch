<div class="manage">
    <div class="d-flex-between">
        <h1>Manage Recipes</h1>
        <button class="btn-h1" onclick="addRecipe()">New Recipe</button>
    </div>
    <div class="modal">
        <div class="modal-content">
            <form name="rec" class="modal-body" action="api.php?req=rec" method="POST">
                <span></span>
                <fieldset>
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name">
                </fieldset>
                <fieldset>
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title">
                </fieldset>
                <div class="d-flex-start flex-gap">
                    <fieldset class="col-6">
                        <label for="cook-time">Cook Time</label>
                        <input type="text" id="cook-time" name="cook-time">
                    </fieldset>
                    <fieldset>
                        <label for="cal-low">Calorie Range</label>
                        <div class="d-flex-between">
                            <input class="col-6" type="text" id="cal-low" name="cal-low">
                            <input class="col-6" type="text" id="cal-high" name="cal-high">
                        </div>
                    </fieldset>
                </div>
                <div class="d-flex-start flex-gap">
                    <fieldset class="d-flex-between flex-col col-6">
                        <label for="diet">Diet</label>
                        <select id="diet" name="diet">
                        <?php foreach ($filters['diet'] as $diet) {   ?>
                            <option value="<?= $diet['tag_id'] ?>"><?= ucfirst($diet['tag_name']) ?></option>
                        <?php } ?>
                        </select>
                    </fieldset>
                    <fieldset class="d-flex-between flex-col col-6">
                        <label for="cuisine">Cuisine</label>
                        <select id="cuisine" name="cuisine">
                        <?php foreach ($filters['cuisine'] as $cuisine) {   ?>
                            <option value="<?= $cuisine['tag_id'] ?>"><?= ucfirst($cuisine['tag_name']) ?></option>
                        <?php } ?>
                        </select>
                    </fieldset>
                </div>
                <fieldset id="ingSet">
                    <div class="d-flex-center-between">
                        <label>Ingredients</label>
                        <select class="col-4" id="ingCats" name="ingCats">
                        <?php foreach ($ingCats as $ingCats) {   ?>
                            <option value="<?= $ingCats ?>"><?= ucfirst($ingCats) ?></option>
                        <?php } ?>
                        </select>
                        <select class="col-4" id="ingSelect" name="ingSelect">
                        </select>
                        <button id="ingAddBtn" class="btn">Add</button>
                    </div>
                </fieldset>
                <fieldset>
                    <label for="steps">Instructions</label>
                    <textarea id="steps" name="steps" rows="10"></textarea>
                </fieldset>
                <fieldset>
                    <label for="photo">Photo</label>
                    <input type="file" id="photo" name="photo"> 
                </fieldset>
                <input type="hidden" name="recipe" value="">
                <div class="modal-footer d-flex-center flex-gap">
                    <input class="btn" type="submit" value="Save">
                    <button class="bg-6 btn-secondary d-none" onclick="deleteRecipe(event)">Delete</button>
                    <button class="bg-6 btn-secondary" onclick="modal.hide(event)">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <div class="w-100">
        <?php include 'search-control.php'?>
    </div>
    <?php include 'card-content.php' ?>
</div>