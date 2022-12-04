<div class="d-flex-panel bg-7">
    <div class="half-panel-left">
        <img src="assets/ramen.gif">
    </div>
    <div class="half-panel">
        <h1 class="clr-5">Sign up</h1>
        <div>
            <span class="support-txt">Have an account?</span>
            <a class="support-txt" href="login.php">Log in</a>    
        </div>
        <form class="form" action="api.php?req=signup" method="POST">
            <label for="name">Full Name*</label>
            <input type="text" name="name" value="<?= $error['name']?>">
            <label for="email">Email Address*</label>
            <input type="email" name="email" value="<?= $error['email']?>">
            <label for="password">Password*</label>
            <input type="password" name="password">
            <div class="warn">
                <?= $error['error'] ?>
            </div>
            <div class="d-flex-center">
                <button class="btn" type="submit">Get Started</button>
            </div>
        </form>
    </div>
</div>