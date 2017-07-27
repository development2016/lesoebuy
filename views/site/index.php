<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = 'Dashboard';
?>




<div class="row">
    <div class="col-lg-9 col-md-5">
        <div class="card">
            <div class="card-block">
                <h3 class="card-title">Our Visitors </h3>
                <h6 class="card-subtitle">Different Devices Used to Visit</h6>
                <div id="visitor" style="height:290px; width:100%;"></div>
            </div>
            <div>
                <hr class="m-t-0 m-b-0">
            </div>
            <div class="card-block text-center ">
                <ul class="list-inline m-b-0">
                    <li>
                        <h6 class="text-muted text-info"><i class="fa fa-circle font-10 m-r-10 "></i>Mobile</h6> </li>
                    <li>
                        <h6 class="text-muted  text-primary"><i class="fa fa-circle font-10 m-r-10"></i>Desktop</h6> </li>
                    <li>
                        <h6 class="text-muted  text-success"><i class="fa fa-circle font-10 m-r-10"></i>Tablet</h6> </li>
                </ul>
            </div>
        </div>
    </div>

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



        

