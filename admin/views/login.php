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
            echo "<script>window.onload = () => showError('Invalid Login Details!')</script>";
        }
    } else {
        echo "<script>window.onload = () => showError('" . MSG['RECAPTCHA_ERROR'] . "')</script>";
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
    <?php echo Config::IMPORT['header']; ?>
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
                        <?= Utils::renderInput(
                            type: "email",
                            label: "Email",
                            name: "email",
                            id: "email",
                            value: $_POST['email'] ?? '',
                            placeholder: "your@email.com",
                            required: true
                        ) ?>
                    </div>
                    <div class="mb-2">
                        <?= Utils::renderInput(
                            type: "password",
                            label: "Password",
                            name: "password",
                            id: "password",
                            value: $_POST['password'] ?? '',
                            placeholder: "Your Password",
                            required: true,
                            linkRequired: true,
                            linkName: "Forgot password?",
                            linkHref: "forget"
                        ) ?>

                    </div>
                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" <?php echo isset($_POST['saveLogin']) ? 'checked' : ""; ?> name="saveLogin" class="form-check-input" />
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
    <?php echo Config::IMPORT['footer'] . Config::IMPORT['popupjs'] . Config::IMPORT['cloudflare'] . Config::IMPORT['appAdminJs']; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const errorIcon = '<?php echo ICO['info']; ?>';
            const fields = validateRule({
                "#email": [{
                        rule: "required", message: "<?php echo MSG['PE'] . 'Email'; ?>"
                    }, {
                        rule: "email", message: "<?php echo MSG['IV'] . 'Email'; ?>"
                    }, {
                        rule: "minLength", value: 8, message: "<?php echo MSG['IV'] . 'Email'; ?>"
                    }
                ],
                "#password": [{
                        rule: "required", message: "<?php echo MSG['PE'] . 'Password'; ?>"
                    }, {
                        rule: "minLength", value: 6, message: "<?php echo 'Password' . MSG['MINL']; ?>"
                    }
                ]
            }, errorIcon);

            validate("#loginForm", fields, () => {
                document.querySelector("#loginForm").submit();
            });
        });
    </script>