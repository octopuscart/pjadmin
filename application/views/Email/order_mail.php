<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Order No#</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <style>
            .carttable{
                border-color: #fff;
            }

            .carttable td{
                padding: 5px 10px;
                border-color: #9E9E9E;
            }
            .carttable tr{
                /*padding: 0 10px;*/
                border-color: #9E9E9E;
                font-size: 12px
            }

            .detailstable td{
                padding:10px 20px;
            }

            .gn_table td{
                padding:3px 0px;
            }
            .gn_table th{
                padding:3px 0px;
                text-align: left;

            }
            .style_block{
                float: left;
                padding: 1px 1px;
                margin: 2.5px;
                /* background: #000; */
                color: white;
                border: 1px solid #e4e4e4;
                width: 47%;
                font-size: 12px;
            }


            .style_block span {
                background: #fff;
                margin-left: 5px;
                color: #000;
                padding: 0px 5px;
                width: 50%;
            }
            .style_block b {
                width: 46%;
                float: left;
                background: #dedede;
                color: black;
            }
            span.fr_value {
                margin-left: 1px;
                padding: 0;
                font-size: 9px;
                text-align: -webkit-left;
                position: absolute;
                margin-top: 0px;
                width: 20px;
            }

            .icon-circle{
                font-size: 19px;
                height: 31px;
                width: 31px;
                background-color: #000;
                float: left;
                border-radius: 50%;
                color: #fff;
                line-height: 28px;
                text-align: center;
            }
        </style>
    </head>

    <body style="margin: 0;
          padding: 0;
          background: rgb(225, 225, 225);
          font-family: sans-serif;">
        <div class="" style="padding:50px 0px">
            <?php echo EMAIL_HEADER; ?>
            <table class="detailstable" align="center" border="0" cellpadding="0" cellspacing="0" width="700" style="background: #fff">
                <tr>
                    <td style="font-size: 12px;width: 50%" >
                        <b>Shipping Address</b><br/><hr/>
                        <span style="text-transform: capitalize;margin-top: 10px;"> 
                            <?php echo $order_data->name; ?>
                        </span> <br/>
                        <div style="    padding: 5px 0px;">
                            <?php echo $order_data->address1; ?><br/>
                            <?php echo $order_data->address2; ?><br/>
                            <?php echo $order_data->state; ?>
                            <?php echo $order_data->city; ?>

                            <?php echo $order_data->country; ?> <?php echo $order_data->zipcode; ?>

                        </div>
                        <table class="gn_table">
                            <tr>
                                <th>Email</th>
                                <td>: <?php echo $order_data->email; ?> </td>
                            </tr>
                            <tr>
                                <th>Contact No.</th>
                                <td>: <?php echo $order_data->contact_no; ?> </td>
                            </tr>
                        </table>


                    </td>
                    <td style="font-size: 12px;width: 35%" >

                        <table class="gn_table">
                            <tr>
                                <td colspan="2">
                                    <b>Order Information</b><br/><hr/>
                                </td>
                            </tr>
                            <tr>
                                <th>Order No.</th>
                                <td>: <?php echo $order_data->order_no; ?> </td>
                            </tr>
                            <tr>
                                <th>Date/Time</th>
                                <td>: <?php echo $order_data->order_date; ?> <?php echo $order_data->order_time; ?>  </td>
                            </tr>
                            <tr>
                                <th>Payment Mode</th>
                                <td>: <?php echo $order_data->payment_mode; ?> </td>
                            </tr>
                            <tr>
                                <th>Txn No.</th>
                                <td>: <?php echo $payment_details['txn_id'] ? $payment_details['txn_id'] : '---'; ?> </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>: <?php
                                    if ($order_status) {
                                        echo end($order_status)->status;
                                    } else {
                                        echo "Pending";
                                    }
                                    ?> </td>
                            </tr>
                        </table>


                    </td>
                </tr>
            </table>


            <table class="carttable"  border-color= "#9E9E9E" align="center"  cellpadding="0" cellspacing="0" width="700" style="background: #fff;">


                <tr>
                    <td>
                        <div class = "" style = "    padding: 5px 5px;
                             border: 1px solid #000;
                             margin-bottom: 18px;
                             border-radius: 5px;">
                            <center>
                                <h3 style="padding: 2px;  margin: -2px;  background-color: #ECECEC; margin-bottom: 10px;  color: #000; font-weight: 300;">Order Status</h3>

                                <table class="orderstatustable" align="center"  cellpadding="0" cellspacing="0" width="600" >

                                    <?php
                                    $count = 0;
                                    $countord = count($order_status);
                                    foreach ($order_status as $oskey => $osvalue) {
                                        ?>
                                        <tr style="border-bottom: 1px solid #000;">
                                            <td style='text-align: right; border-left: 0px solid;padding: 0;'>

                                                <i class='icon-circle'><?php
                                                    echo $countord - $count;
                                                    ?></i>
                                            </td>
                                            <td style=' padding: 10px;'>
                                                <b>
                                                    <?php
                                                    echo $osvalue->status;
                                                    ?>
                                                </b>
                                                <br/>
                                                <small style="font-weight:300;font-size:13px">
                                                    <?php
                                                    if ($osvalue->status == "Shipped") {
                                                        echo $osvalue->description;
                                                    } else {
                                                        echo $osvalue->remark;
                                                    }
                                                    ?>

                                                </small>


                                            </td>

                                            <th style="
                                                text-align: left;">
                                                <span style="font-size: 10px;">
                                                    <?php
                                                    echo $osvalue->c_date . " " . $osvalue->c_time;
                                                    ?>
                                                </span>
                                            </th>

                                        </tr>
                                        <?php
                                        echo "<tr><td colspan=3><hr /></td></tr>";
                                        ?>
                                        <?php
                                        $count++;
                                    }
                                    ?>

                                </table>
                            </center>
                        </div>
                    </td>
                </tr>          

            </table>


            <table class="carttable"  border-color= "#9E9E9E" align="center" border="1" cellpadding="0" cellspacing="0" width="700" style="background: #fff;padding:20px">
                <tr style="font-weight: bold">
                    <td style="width: 20px;text-align: center">S.No.</td>
                    <td colspan="2"  style="text-align: center">Product</td>

                    <td style="text-align: right;width: 100px">Price (In <?php echo trim(GLOBAL_CURRENCY); ?>)</td>
                    <td style="text-align: right">Qnty.</td>
                    <td style="text-align: right;width: 100px">Total (In  <?php echo trim(GLOBAL_CURRENCY); ?>)</td>
                </tr>
                <!--cart details-->
                <?php
                foreach ($cart_data as $key => $product) {
                    ?>
                    <tr>
                        <td style="text-align: right">
                            <?php echo $key + 1; ?>
                        </td>

                        <td style="width: 50px">
                            <center>   <img src=" <?php echo $product->file_name; ?>" style="height: 50px;"></img>
                        </td>

                        <td style="width: 200px;">
                            <?php echo $product->title; ?> - <?php echo $product->item_name; ?><br/>
                            <small style="font-size: 10px;">(<?php echo $product->sku; ?>)</small>


                        </td>

                        <td style="text-align: right">
                            <?php echo $product->price; ?>
                        </td>

                        <td style="text-align: right">
                            <?php echo $product->quantity; ?>
                        </td>

                        <td style="text-align: right;">
                            <?php echo $product->total_price; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <b>Style Details : <?php echo $product->title; ?> - <?php echo $product->item_name; ?></b>
                            <br/><?php
                            foreach ($product->custom_dict as $key => $value) {
                                echo "<p class='style_block'><b>$key</b><span> $value</span></p>";
                            }
                            ?>  
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <!--end of cart details-->

                <tr>
                    <td colspan="6">
                        <b>Sizes: <?php echo $order_data->measurement_style; ?></b>
                        <br/><?php
                        if (count($measurements_items)) {
                            foreach ($measurements_items as $keym => $valuem) {
                                $mvalues = explode(" ", $valuem['measurement_value']);
                                $unit = $valuem['unit'] == "inch" ? '"' : '';
                                if ($unit) {
                                    echo "<p class='style_block'><b>" . $valuem['measurement_key'] . " </b><span> " . $mvalues[0] . " <span class='fr_value'>" . $mvalues[1] . "$unit</span></span></p>";
                                } else {
                                    echo "<p class='style_block'><b>" . $valuem['measurement_key'] . " </b><span> " . $valuem['measurement_value'] . " </span></p>";
                                }
                            }
                        }
                        ?>  
                    </td>
                </tr>


                <tr>
                    <td colspan="3"  rowspan="5" style="font-size: 12px">
                        <b>Total Amount in Words: </b><br/>
                        <span style="text-transform: capitalize"> <?php echo $order_data->amount_in_word; ?></span>
                    </td>

                </tr>
                <tr>
                    <td colspan="2" style="text-align: right">Sub Total</td>
                    <td style="text-align: right;width: 60px"><?php echo GLOBAL_CURRENCY . " " . number_format($order_data->sub_total_price, 2, '.', ''); ?> </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right">Shipping Amount</td>
                    <td style="text-align: right;width: 60px"><?php echo GLOBAL_CURRENCY . " " . number_format($order_data->shipping ? $order_data->shipping : 0, 2, '.', ''); ?> </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right">Coupon Discount</td>
                    <td style="text-align: right;width: 60px"><?php echo GLOBAL_CURRENCY . " " . number_format($order_data->discount ? $order_data->discount : 0, 2, '.', ''); ?> </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right">Toal Amount</td>
                    <td style="text-align: right;width: 60px"><?php echo GLOBAL_CURRENCY . " " . number_format($order_data->total_price, 2, '.', ''); ?> </td>
                </tr>


                <tr>
                    <td colspan="6" style="font-size: 12px;">



                        <?php
                        echo EMAIL_FOOTER;
                        ?>

                    </td>
                </tr>

            </table>

        </div>
    </body>
</html>