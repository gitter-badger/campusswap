<div class="loginBar" name="loginbar"> <?PHP //LOGIN BAR ?>
    <?php
    if($loggedIn){ ?>
    <a href="<?= URL ?>interface/user_manager/user_posts.php" params="lightwindow_type=external"  class="lightwindow">
        <?php
        echo '<i class="fa fa-user fa-lg"></i>&nbsp;' . $liUser . '@' . $liDomain . '</a>';
        if($liLevel=='admin'){
            echo '<a href="admin/index.php"> / <i class="fa fa-flask fa-lg"></i>&nbsp; Admin';
        }
        echo ' / <a href="logout.php"><i class="fa fa-sign-out fa-lg"></i> &nbsp; Logout</a></b>';
        } else { ?>
            <a href="#" onclick="Effect.toggle('login_register', 'appear'); return false;">
                <i class="fa fa-sign-in fa-lg"></i>&nbsp;<b>Login/ Register</b>
            </a>
        <?php } ?>
    </a>
</div>




<div id="login_register" class="loginTop<?= $span2 ?>" style="<?= $display ?>">
    <?php //LOGIN ?>
    <div class="row login_register_inner">
        <div class="col-lg-5 login_section">
            <h3 class="muted">Login</h3>
            <form name="input" action="<?= URL ?>login.php" method="post">
                <input class="tall_text_box input-group input-group-md" type="text" value="College E-Mail Address" name="username" />
                <select class="form-control input-md" name="domain">
                    <?php
                    foreach($DomainsDAO->getAllDomains() as $login_domain_rows){
                        echo '<option value = "' . $login_domain_rows->getDomain() . '">@' . $login_domain_rows->getDomain() . '</option>';
                    }
                    ?>
                </select><br />
                <input class="tall_text_box form-control input-md" type="password" size="30" name="password" value="password" />
                <input type="hidden" name="loginSubmitted" value="TRUE">
                <input class="btn btn-primary btn-md btn-block" type="submit" class="btn" value="Login" />
                <a href="<?= URL ?>recoverPassword.php">Recover Password</a>
            </form>
        </div>
        <?php //REGISTER ?>
        <div class="col-lg-5 register_section">
            <h3 class="muted">Register<small>(Available Upon Release)</small></h3>
            <form name="input" action="<?= URL ?>signup.php" method="post">
                <input class="tall_text_box input-group input-group-md" type="text" value="College E-Mail Address" name="username" />
                <select class="form-control input-md" name="domain">
                    <?php
                    foreach($DomainsDAO->getAllDomains() as $register_domain_rows){
                        echo '<option value = "' . $register_domain_rows->getDomain() . '">@' . $register_domain_rows->getDomain() . '</option>';
                    }

                    ?>
                </select><br />
                <input type="hidden" name="signup" value="TRUE">
                <input class="btn btn-primary btn-md btn-block" type="submit"  value="One Click Signup! (No BS)" />
            </form>
        </div>
    </div>
</div> <?PHP //END WELCOME - LOGIN - SIGNUP ?>