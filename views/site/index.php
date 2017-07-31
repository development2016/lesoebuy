<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = 'Dashboard';
?>




<div class="row">


    <div class="col-lg-4 col-xlg-3">
        <!-- Column -->
        <div class="card earning-widget">
            <div class="card-header">

                <h4 class="card-title m-b-0">List Staff</h4>
            </div>
            <div class="card-block b-t collapse show">
                <table class="table v-middle no-border">
                    <tbody>
                    <?php foreach ($user as $key => $value) { ?>
                        <tr>
                            <td><?= $value['account_name'] ?></td>

                            <?php if ($value['status_login'] == 1) { ?>
                                <td align="right"><span class="label label-light-success">Online</span></td>
                                
                            <?php } elseif ($value['status_login'] == 2) { ?>
                                <td align="right"><span class="label label-light-warning">Away</span></td>

                            <?php } elseif ($value['status_login'] == 3) { ?>
                                <td align="right"><span class="label label-light-primary">Idle</span></td>

                            <?php } elseif ($value['status_login'] == 4) { ?>
                                <td align="right"><span class="label label-light-info">Busy</span></td>

                            <?php } elseif ($value['status_login'] == 0) { ?>
                                <td align="right"><span class="label label-light-danger">Offline</span></td>
                     
                            <?php } ?>
                            
                        </tr>
                    <?php } ?>



                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>



        

