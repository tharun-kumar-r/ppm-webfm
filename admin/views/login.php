<?php
$login = CORE->checkSession();
if (CORE->userLoggedIn()['type'] == 'admin' || $login['sessionSts']) {
    echo "<script>window.location='" . BASEPATH . "home'</script>";
    exit;
}

if (isset($_POST['login'])) {
    if (CORE->checkBot()['sts']) {
        if (CORE->login($_POST['email'], $_POST['password'], isset($_POST['saveLogin']))['sts']) {
            echo "<script>window.location='" . BASEPATH . "home'</script>";
        } else {
            echo "<script>window.onload = function() {showError('Invalid Login Details!.')}</script>";
        }
    } else {
        echo "<script>window.onload = function() {showError('". MSG['RECAPTCHA_ERROR'] ."')}</script>";
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login to Webmaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0/dist/css/tabler.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
    <style>
        .just-validate-error-label {
            margin-top: 4px;
        }
    </style>
</head>

<body class="d-flex flex-column ">
    <div class="d-flex h-100 align-item-center justify-content-center">
        <div class="col-10 col-lg-3 col-xl-3 d-flex flex-column justify-content-center">
            <div class="container container-tight  my-5 px-lg-4">
                <div class="text-center mb-4">
                    <img src="assets/img/icon.svg" style="height:50px">
                </div>

                <form method="post" name="login" id="loginForm" autocomplete="off" novalidate>
                    <input type="hidden" name="login" value="FormLogin">
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input id="email" type="email" name="email" class="form-control" value="<?php echo $_POST['email']; ?>" placeholder="your@email.com" >
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            Password
                            <span class="form-label-description">
                                <a href="./forgot-password.html">I forgot password</a>
                            </span>
                        </label>
                        <input type="password" id="password" name="password" class="form-control" value="<?php echo $_POST['email']; ?>" placeholder="Your password">
                    </div>
                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" <?php echo isset($_POST['saveLogin']) ? 'checked' :""; ?> name="saveLogin" class="form-check-input" />
                            <span class="form-check-label">Remember me on this device</span>
                        </label>
                    </div>

                    <div class="cf-turnstile mb-2 w-90" data-theme="light" data-sitekey="1x00000000000000000000AA"></div>

                    <div class="form-footer mt-0">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/just-validate@latest/dist/just-validate.production.min.js"></script>
    <script src="https://dainty-macaron-bfe024.netlify.app/PopupJs.js"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const validation = new JustValidate("#loginForm");
        const errorIcon = '<?php echo ICO['info']; ?>';

        // Validation rules mapping
        const fields = {
            "#email": [
                { rule: "required", errorMessage: errorIcon + "<?php echo MSG['PE'] . 'Email!'; ?>" },
                { rule: "email", errorMessage: errorIcon + "<?php echo MSG['IV'] . 'Email!'; ?>" }
            ],
            "#password": [
                { rule: "required", errorMessage: errorIcon + "<?php echo MSG['PE'] . 'Password!'; ?>" },
                { rule: "minLength", value: 6, errorMessage: errorIcon + "<?php echo 'Password' . MSG['MINL']; ?>" }
            ]
        };

        // Apply validation dynamically
        Object.entries(fields).forEach(([selector, rules]) => validation.addField(selector, rules));
        validation.onSuccess(() => document.getElementById("loginForm").submit());
    });
</script>
