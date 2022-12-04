<div class="d-flex-panel bg-7">
    <div class="half-panel-left">
        <img src="assets/login-img.png">
    </div>
    <div class="half-panel">
        <h1 class="clr-5">Log In</h1>
        <div>
            <span class="support-txt">Don't an account?</span>
            <a class="support-txt" href="signup.php">Sign up</a>    
        </div>
        <form class="form" action="api.php?req=login" method="POST">
            <label for="email">Email Address*</label>
            <input type="email" name="email" value="<?= $error['email']?>">
            <label for="password">Password*</label>
            <input type="password" name="password">
            <div class="warn">
                <?= $error['error'] ?>
            </div>
            <div class="d-flex-center">
                <button class="btn" type="submit">Log In</button>
            </div>
        </form>
    </div>
</div>