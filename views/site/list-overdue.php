<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\LookupCountry;
use app\models\LookupState;
use dosamigos\datepicker\DatePicker;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Overdue';


?>
<h2><?= Html::encode($this->title) ?></h2>
<div class="row" style="max-height: 300px;overflow-y: scroll;">
<div class="col-lg-12 col-xs-12 col-sm-12">


    <?php $i=0; foreach ($listoverdue as $key => $value) { $i++;?>


    <blockquote>
        <p><b>Project No : </b><?php echo $value['project_no']; ?></p>
        <p><b>Title : </b><?php echo $value['title']; ?></p>
        <p><b>Due Date : </b><span style="color: #ff0000;"><?php echo $value['due_date']; ?></span></p>
        <p><b>PR No : </b>
        <?php foreach ($value['sellers'] as $key => $value2) { ?>

            <?php if ($value['request_role'] == 'Buyer') { ?>
            <!-- ROLE BUYER -->
                        <!-- START APPROVAL -->
                    <?php if ($value2['approver'] == 'normal') { ?>
                        <!-- REQUEST APPROVAL -->
                        <?php if ($value2['status'] == 'Request Approval') { ?>

                            <?= Html::a($value['sellers'][0]['purchase_requisition_no'], ['request/direct-purchase-requisition-approve',
                              'project'=>(string)$value['_id'],
                              'seller'=>$value2['seller'],
                              'buyer'=>$value['buyers'][0]['buyer'],
                              'approver' => $value2['approver'],
                              ],['class'=>'mytooltip','title'=>'Purchase Requisition']) ?>


                        <?php } ?>

                    <?php } elseif ($value2['approver'] == 'level') { ?>

                        <?php foreach ($value2['approval'] as $key => $app) { ?>

                            <?php if ($app['approval'] == $user->account_name) { ?>


                                <?php if ($app['status'] == 'Waiting Approval') { ?>


                                <?= Html::a($value['sellers'][0]['purchase_requisition_no'], ['request/direct-purchase-requisition-approve',
                                  'project'=>(string)$value['_id'],
                                  'seller'=>$value2['seller'],
                                  'buyer'=>$value['buyers'][0]['buyer'],
                                  'approver' => $value2['approver'],
                                  ],['class'=>'mytooltip','title'=>'Purchase Requisition']) ?>


                                <?php } else { ?>

                                    <?php echo 'Waiting <span style="color: #ff0000;">'.$value2['approver_level'].'</span> To Approve'; ?>


                                <?php } ?>


                            <?php } ?>

                        <?php } ?>



                    <?php } ?>







            <?php } elseif ($value['request_role'] == 'User') { ?> <!-- ENN BUYER -->


                    <?php if ($value2['approver'] == 'normal') { ?>

                        <?php if ($value2['status'] == 'Request Approval') { ?>


                            <?= Html::a($value['sellers'][0]['purchase_requisition_no'], ['request/direct-purchase-requisition-approve',
                              'project'=>(string)$value['_id'],
                              'seller'=>$value2['seller'],
                              'buyer'=>$value['buyers'][0]['buyer'],
                              'approver' => $value2['approver'],
                              ],['class'=>'mytooltip','title'=>'Purchase Requisition']) ?>

                        <?php } elseif ($value2['status'] == 'Request Approval Next') { ?>

                            <?= Html::a($value['sellers'][0]['purchase_requisition_no'], ['request/direct-purchase-requisition-approve-next',
                              'project'=>(string)$value['_id'],
                              'seller'=>$value2['seller'],
                              'buyer'=>$value['buyers'][0]['buyer'],
                              'approver' => $value2['approver'],
                              ],['class'=>'mytooltip','title'=>'Purchase Requisition']) ?>

                        <?php } ?>

                    <?php } elseif ($value2['approver'] == 'level') { ?>

                        <?php if ($value2['status'] == 'Request Approval') { ?>

                            <?php foreach ($value2['approval'] as $key => $app) { ?>

                                <?php if ($app['approval'] == $user->account_name) { ?>

                                    <?php if ($app['status'] == 'Waiting Approval') { ?>

                                    <?= Html::a($value['sellers'][0]['purchase_requisition_no'], ['request/direct-purchase-requisition-approve',
                                      'project'=>(string)$value['_id'],
                                      'seller'=>$value2['seller'],
                                      'buyer'=>$value['buyers'][0]['buyer'],
                                      'approver' => $value2['approver'],
                                      ],['class'=>'mytooltip','title'=>'Purchase Requisition']) ?>


                                    <?php } else { ?>

                                        <?php echo 'Waiting <span style="color: #ff0000;">'.$value2['approver_level'].'</span> To Approve'; ?>


                                    <?php } ?>


                                <?php } ?>

                            <?php } ?>

                        <?php } elseif ($value2['status'] == 'Request Approval Next') { ?>

                            <?php foreach ($value2['approval'] as $key => $app) { ?>

                                <?php if ($app['approval'] == $user->account_name) { ?>

                                    <?= Html::a($value['sellers'][0]['purchase_requisition_no'], ['request/direct-purchase-requisition-approve-next',
                                      'project'=>(string)$value['_id'],
                                      'seller'=>$value2['seller'],
                                      'buyer'=>$value['buyers'][0]['buyer'],
                                      'approver' => $value2['approver'],
                                      ],['class'=>'mytooltip','title'=>'Purchase Requisition']) ?>


                                <?php } ?>

                            <?php } ?>

                        <?php } ?>


                    <?php } ?>




            <?php } elseif ($value['request_role'] == 'BuyerUser') { ?>


                    <?php if ($value2['approver'] == 'normal') { ?>
                                                                <!-- REQUEST APPROVAL -->
                        <?php if ($value2['status'] == 'Request Approval') { ?>

                            <?= Html::a($value['sellers'][0]['purchase_requisition_no'], ['request/direct-purchase-requisition-approve',
                                  'project'=>(string)$value['_id'],
                                  'seller'=>$value2['seller'],
                                  'buyer'=>$value['buyers'][0]['buyer'],
                                  'approver' => $value2['approver'],
                                  ],['class'=>'mytooltip','title'=>'Purchase Requisition']) ?>

                        <?php } elseif ($value2['status'] == 'Request Approval Next') { ?>


                            <?= Html::a($value['sellers'][0]['purchase_requisition_no'], ['request/direct-purchase-requisition-approve-next',
                              'project'=>(string)$value['_id'],
                              'seller'=>$value2['seller'],
                              'buyer'=>$value['buyers'][0]['buyer'],
                              'approver' => $value2['approver'],
                              ],['class'=>'mytooltip','title'=>'Purchase Requisition']) ?>


                        <?php } ?>





                    <?php } elseif ($value2['approver'] == 'level') { ?>


                        <?php if ($value2['status'] == 'Request Approval') { ?>

                            <?php foreach ($value2['approval'] as $key => $app) { ?>

                                <?php if ($app['approval'] == $user->account_name) { ?>


                                    <?php if ($app['status'] == 'Waiting Approval') { ?>

                                        <?= Html::a($value['sellers'][0]['purchase_requisition_no'], ['request/direct-purchase-requisition-approve',
                                          'project'=>(string)$value['_id'],
                                          'seller'=>$value2['seller'],
                                          'buyer'=>$value['buyers'][0]['buyer'],
                                          'approver' => $value2['approver'],
                                          ],['class'=>'mytooltip','title'=>'Purchase Requisition']) ?>


                                    <?php } else { ?>

                                        <?php echo 'Waiting <span style="color: #ff0000;">'.$value2['approver_level'].'</span> To Approve'; ?>


                                    <?php } ?>


                                <?php } ?>


                            <?php } ?>


                         <?php } elseif ($value2['status'] == 'Request Approval Next') { ?>


                            <?php foreach ($value2['approval'] as $key => $app) { ?>

                                <?php if ($app['approval'] == $user->account_name) { ?>

                                    <?= Html::a($value['sellers'][0]['purchase_requisition_no'], ['request/direct-purchase-requisition-approve-next',
                                      'project'=>(string)$value['_id'],
                                      'seller'=>$value2['seller'],
                                      'buyer'=>$value['buyers'][0]['buyer'],
                                      'approver' => $value2['approver'],
                                      ],['class'=>'mytooltip','title'=>'Purchase Requisition']) ?>

                                <?php } ?>

                            <?php } ?>


                         <?php } ?>


                    <?php } ?>




            <?php } ?>

        <?php } ?>
        </p>
    </blockquote>





    <?php } ?>


</div>