<header class="row">
    <ul class="nav">
        <li class="nav-item">
            <a id="homeImage" class="nav-link" href="<?php echo site_url('home/index'); ?>" role="button"
               aria-haspopup="true"
               aria-expanded="false">
                <?php echo image("mtg_logo.png", 'title="MTG logo" alt="MTG logo" class="homeIcon"'); ?>
            </a>
        </li>
    </ul>
    <?php if ($user == null) { ?>
        <ul class="nav ml-auto">
            <li class="nav-item dropdown ">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                   aria-expanded="false">LOGIN</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-user="axel"
                       data-target="#loginModal">Axel</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-user="stefanie"
                       data-target="#loginModal">Stefanie</a>
                </div>
            </li>
        </ul>
    <?php }
    else {
        $userImage = $user->name . "_square.jpg";
        ?>
        <ul class="nav mr-auto">

            <li class="nav-item">
                <a id="addCardsButton" class="nav-link" href="<?php echo site_url('home/addCards'); ?>" role="button"
                   aria-haspopup="true"
                   aria-expanded="false">ADD CARDS</a>
            </li>
            <?php if($user->level == 5){ ?>
                <li class="nav-item">
                    <a id="addCardsButton" class="nav-link" href="<?php echo site_url('admin/index'); ?>" role="button"
                       aria-haspopup="true"
                       aria-expanded="false">SETTINGS</a>
                </li>
            <?php } ?>

        </ul>
        <ul class="nav ml-auto">
            <li class="nav-item">
                <a id="logoutButton" class="nav-link" href="<?php echo site_url('home/logout'); ?>" role="button"
                   aria-haspopup="true"
                   aria-expanded="false">
                    <?php echo image($userImage, 'title="Logout" alt="' . $user->name . '" class="userIcon"'); ?>
                </a>
            </li>
        </ul>
    <?php } ?>
</header>

<!-- Modal -->
<div class="modal fade bd-example-modal-sm" id="loginModal" tabindex="-1" role="dialog"
     aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <!--            <div class="modal-header">-->
            <!--                <h5 class="modal-title" id="exampleModalLabel">Password</h5>-->
            <!--            </div>-->
            <!--            <h5 class="modal-title" id="exampleModalLabel">Password</h5>-->
            <div class="modal-body">
                <div>
                    <?php
                    $dataOpen = array(
                        'id' => 'form_login',
                        'name' => 'form_login',
                        'role' => 'form',
                        'data-toggle' => 'validator');

                    $dataSubmit = array(
                        'id' => 'submit_login',
                        'name' => 'submit_login',
                        'type' => 'submit',
                        'value' => 'LOGIN',
                        'class' => 'btn btn-primary size customInputSubmit');

                    echo form_open('home/login', $dataOpen);

                    echo '<div>';
                    echo form_input(array(
                            'id' => 'password',
                            'name' => 'password',
                            'type' => 'password',
                            'class' => 'form-control customInput',
                            'style' => 'text-align:center',
                            'placeholder' => 'PASSWORD',
                            'onfocus' => "this.placeholder = ''",
                            'onblur' => "this.placeholder = 'PASSWORD'",
                            'required' => 'required')) . "\n";

                    echo '</div>';

                    echo '<div hidden>';
                    echo '<input hidden id="user" name="user"  type="password" class="form-control">';
                    echo '</div>';

                    echo '<div id="div_submitButton">';
                    echo form_submit($dataSubmit) . "\n";
                    echo '</div>';

                    echo form_close();
                    ?>
                </div>
            </div>
            <!--<div class="modal-footer"></div>-->
        </div>
    </div>
</div>

<script>
    $('#loginModal').on('shown.bs.modal', function (event) {
        $('#password').trigger('focus');
        var button = $(event.relatedTarget); // Button that triggered the modal
        var user = $(event.relatedTarget).data('user'); // Extract info from data-* attributes
        $('#user').val(user); //set value in hidden input in the modal when clicked on de <a>-tag to open the modal
    });
</script>