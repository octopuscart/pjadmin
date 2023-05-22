<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->library('session');
        $this->curd = $this->load->model('Curd_model');
        $session_user = $this->session->userdata('logged_in');
        if ($session_user) {
            $this->user_id = $session_user['login_id'];
            $this->user_type = isset($session_user['user_type']) ? $session_user['user_type'] : "";
        } else {
            $this->user_id = 0;
            $this->user_type = "";
        }
    }

//list of data according to date list
    public function date_graph_data($date1, $date2, $datalist) {
        $period = new DatePeriod(
                new DateTime($date1), new DateInterval('P1D'), new DateTime($date2)
        );
        $daterangearray = [$date1];
        foreach ($period as $key => $value) {
            array_push($daterangearray, $value->format('Y-m-d'));
        }
        array_push($daterangearray, $date2);

        $date_list_data = array();

        foreach ($daterangearray as $key => $value) {
            if (isset($datalist[$value])) {
                $date_list_data[$value] = $datalist[$value];
            } else {
                $date_list_data[$value] = 0;
            }
        }
        return $date_list_data;
    }

    public function index() {

        $date1 = date('Y-m-d', strtotime('-30 days'));
        $date2 = date('Y-m-d');

        $data = array();

        $data['blog_data'] = array();

        $this->db->order_by('id', 'desc');
        $this->db->where('order_date between "' . $date1 . '" and "' . $date2 . '"');
        $query = $this->db->get('user_order');

        $querypayment = "SELECT payment_mode, count(order_no) as count FROM `user_order` GROUP by payment_mode ORDER by count(order_no) desc";
        $querypayment2 = $this->db->query($querypayment);
        $paymentdata = $querypayment2->result_array();
        $data['paymentdata'] = $paymentdata;

        $orderlist = $query->result();
        $orderslistr = [];
        foreach ($orderlist as $key => $value) {
            $this->db->order_by('id', 'desc');
            $this->db->where('order_id', $value->id);
            $query = $this->db->get('user_order_status');
            $status = $query->row();
            $value->status = $status ? $status->status : $value->status;
            $value->status_datetime = $status ? $status->c_date . " " . $status->c_time : $value->order_date . " " . $value->order_time;
            $this->db->order_by('id', 'desc');
            $this->db->where('order_id', $value->id);
            $query = $this->db->get('cart');
            $cartdata = $query->result();
            $tempdata = array();
            $itemarray = array();

            array_push($orderslistr, $value);
        }
        $data['orderslist'] = $orderslistr;

        $data['exportdata'] = 'no';
        if ($this->user_type != 'Admin') {
            redirect('UserManager/not_granted');
        }
        $date1 = date('Y-m-d', strtotime('-30 days'));
        $date2 = date('Y-m-d');
        if (isset($_GET['daterange'])) {
            $daterange = $this->input->get('daterange');
            $datelist = explode(" to ", $daterange);
            $date1 = $datelist[0];
            $date2 = $datelist[1];
        }
        $daterange = $date1 . " to " . $date2;
        $data['daterange'] = $daterange;
        $this->db->order_by('id', 'desc');
        $this->db->where('order_date between "' . $date1 . '" and "' . $date2 . '"');
        $query = $this->db->get('user_order');
        $orderlist = $query->result_array();
        $orderslistr = [];
        $total_amount = 0;
        foreach ($orderlist as $key => $value) {
            $this->db->order_by('id', 'desc');
            $this->db->where('order_id', $value['id']);
            $total_amount += $value['total_price'];
            $query = $this->db->get('user_order_status');
            $status = $query->row();
            $value['status'] = $status ? $status->status : $value['status'];
            array_push($orderslistr, $value);
        }
        $data['total_amount'] = $total_amount;

        $this->db->order_by('id', 'desc');

        $query = $this->db->get('admin_users');
        $userlist = $query->result_array();

        $this->db->order_by('c.id', 'desc');
        $query = $this->db->from('cart as c');
        $this->db->join('user_order as uo', 'uo.id = c.order_id');
        $this->db->where('c.order_id > 0');
        $this->db->where('uo.order_date between "' . $date1 . '" and "' . $date2 . '"');
        $query = $this->db->get();
        $vendororderlist = $query->result_array();

        $data['vendor_orders'] = count($vendororderlist);
        $data['total_order'] = count($orderslistr);
        $data['total_users'] = count($userlist);

        $this->load->library('JsonSorting', $orderslistr);
        $orderstatus = $this->jsonsorting->collect_data('status');
        $orderuser = $this->jsonsorting->collect_data('name');
        $orderdate = $this->jsonsorting->collect_data('order_date');
        $data['orderstatus'] = $orderstatus;
        $data['orderuser'] = $orderuser;
        $data['orderdate'] = $orderdate;

//order graph date
        $dategraphdata = $this->date_graph_data($date1, $date2, $orderdate);
        $data['order_date_graph'] = $dategraphdata;

        $amount_date = $this->jsonsorting->data_combination_quantity('total_price', 'order_date');

        $salesgraph = array();

        foreach ($dategraphdata as $key => $value) {
            $salesgraph[$key] = 0;
            if (isset($amount_date[$key])) {
                $salesgraph[$key] = $amount_date[$key];
            }
        }

        $data['salesgraph'] = $salesgraph;

        $this->db->order_by('id', 'desc');
        $this->db->limit(10);
        $query = $this->db->get('admin_users');
        $systemlog = $query->result_array();

        $data['latestusers'] = $systemlog;

        $this->db->order_by('id', 'desc');
        $this->db->limit(10);
        $query = $this->db->get('system_log');
        $systemlog = $query->result_array();

        $data['systemlog'] = $systemlog;
        //order list            
        $this->db->order_by('id', 'desc');
        $this->db->where('order_date between "' . $date1 . '" and "' . $date2 . '"');
        $query = $this->db->get('user_order');
        $orderlist = $query->result();
        $orderslistr = [];
        foreach ($orderlist as $key => $value) {
            $value->status_datetime = $value->order_date . " " . $value->order_time;
            array_push($orderslistr, $value);
        }
        $data['orderslist'] = $orderslistr;
        //end of order list
        //order count
        $this->db->select('count(id) as order_count');
        $query = $this->db->get('user_order');
        $ordercount = $query->row();
        $data['total_order'] = $ordercount->order_count;

        //user count            
        $this->db->select('count(id) as total_users');
        $query = $this->db->get('admin_users');
        $userlist = $query->row();
        $data['total_users'] = $userlist->total_users;

        //visitore count
        $this->db->select('count(id) as total_users');
        $query = $this->db->get('ci_sessions');
        $userlist = $query->row();
        $data['total_visitor'] = $userlist->total_users;

        //lastest users            
        $this->db->order_by('id', 'desc');
        $this->db->limit(12);
        $query = $this->db->get('admin_users');
        $lastestuser = $query->result_array();
        $data['latestusers'] = $lastestuser;

        //system log            
        $this->db->order_by('id', 'desc');
        $this->db->limit(10);
        $query = $this->db->get('system_log');
        $systemlog = $query->result_array();
        $data['systemlog'] = $systemlog;

        //order datess
        $queryrawo = "SELECT order_date, count(id) as count FROM user_order where order_date between '$date1' and '$date2' group by order_date order by order_date desc";
        $queryrawo2 = $this->db->query($queryrawo);
        $order_dates = $queryrawo2->result_array();
        $order_dates_array = array();
        foreach ($order_dates as $key => $value) {
            $order_dates_array[$value['order_date']] = $value['count'];
        }
        $booking_dates_array = array();

        $listofdates = array();
        for ($i = 30; $i >= 0; $i--) {
            $tdate = date('Y-m-d', strtotime("-$i days"));
            $listofdates[$tdate] = array(
                "order" => isset($order_dates_array[$tdate]) ? $order_dates_array[$tdate] : 0,
                "booking" => isset($booking_dates_array[$tdate]) ? $booking_dates_array[$tdate] : 0
            );
        }

        $data['order_booking_date_list'] = $listofdates;

        $this->load->view('Order/dashboard', $data);
    }

    public function orderPrint($order_id, $subject = "") {
        setlocale(LC_MONETARY, 'en_US');
        $order_details = $this->Order_model->getOrderDetails($order_id, 0);
        if ($order_details) {
            $order_no = $order_details['order_data']->order_no;
            $html = $this->load->view('Email/order_pdf', $order_details, true);
            if (REPORT_MODE) {
                // Load pdf library
                $this->load->library('pdf');
                $this->dompdf->set_option('isRemoteEnabled', true);
                // Load HTML content
                $this->dompdf->loadHtml($html);
                // (Optional) Setup the paper size and orientation
                $this->dompdf->setPaper('A4');
                // Render the HTML as PDF
                $this->dompdf->render();
                // Output the generated PDF (1 = download and 0 = preview)
                $this->dompdf->stream("welcome.pdf", array("Attachment" => 0));
            } else {
                echo $html;
            }
        }
    }

//order details
    public function orderdetails($order_key) {
        $order_status = $this->input->get('status');
        $data['status'] = $order_status;
        if ($this->user_type == 'Customer') {
            redirect('UserManager/not_granted');
        }




        $order_details = $this->Order_model->getOrderDetailsV2($order_key, 'key');
        $vendor_order_details = $this->Order_model->getVendorsOrder($order_key);
        $data['vendor_order'] = $vendor_order_details;
        if ($order_details) {
            $order_id = $order_details['order_data']->id;
            $data['ordersdetails'] = $order_details;
            $data['order_key'] = $order_key;
            $this->db->order_by('id', 'desc');
            $this->db->where('order_id', $order_id);
            $query = $this->db->get('user_order_status');
            $orderstatuslist = $query->result();

            $currentstatus = $orderstatuslist[0]->status;

            if ($order_status) {
                
            } else {
                //redirecttion
                switch ($currentstatus) {
                    case "Order Confirmed":
                        redirect("Order/orderdetails_payments/$order_key");
                        break;
                    case "Payment Confirmed":
                        redirect("Order/orderdetails_shipping/$order_key");
                        break;
                    case "Shipped":
                        if ($order_status == 'Delivered') {
                            
                        } else {
                            redirect("Order/orderdetails/$order_key/?status=Delivered");
                        }
                        break;
                    default:

                        echo "";
                }
            }
            //end of redirection



            $data['user_order_status'] = $orderstatuslist;
            if (isset($_POST['submit'])) {
                $productattr = array(
                    'c_date' => date('Y-m-d'),
                    'c_time' => date('H:i:s'),
                    'status' => $this->input->post('status'),
                    'remark' => $this->input->post('remark'),
                    'description' => $this->input->post('description'),
                    'order_id' => $order_id
                );
                $this->db->insert('user_order_status', $productattr);
                if ($this->input->post('sendmail') == TRUE) {
                    try {
                        $this->Order_model->order_mail($order_key, "");
                    } catch (Exception $e) {
                        echo 'Message: ' . $e->getMessage();
                    }
                }
                redirect("Order/orderdetails/$order_key");
            }
        } else {
            redirect('/');
        }
        $this->load->view('Order/orderdetails', $data);
    }

    public function orderdetails_payments($order_key) {
        $order_status = $this->input->get('status');
        $data['status'] = $order_status;
        if ($this->user_type == 'Customer') {
            redirect('UserManager/not_granted');
        }
        $order_details = $this->Order_model->getOrderDetailsV2($order_key, 'key');
        $vendor_order_details = $this->Order_model->getVendorsOrder($order_key);
        $data['vendor_order'] = $vendor_order_details;
        if ($order_details) {
            $order_id = $order_details['order_data']->id;
            $data['ordersdetails'] = $order_details;
            $data['order_key'] = $order_key;
            $this->db->order_by('id', 'desc');
            $this->db->where('order_id', $order_id);
            $query = $this->db->get('user_order_status');
            $orderstatuslist = $query->result();
            $data['user_order_status'] = $orderstatuslist;
            if (isset($_POST['submit'])) {
                $productattr = array(
                    'c_date' => date('Y-m-d'),
                    'c_time' => date('H:i:s'),
                    'status' => "Payment Confirmed",
                    'remark' => $this->input->post('remark'),
                    'description' => $this->input->post('description'),
                    'order_id' => $order_id
                );
                $this->db->insert('user_order_status', $productattr);

                $productattr = array(
                    'status' => "Payment Confirmed",
                    'remark' => $this->input->post('remark'),
                    'txn_no' => $this->input->post('txn_no'),
                    'c_date' => $this->input->post('c_date'),
                    'c_time ' => $this->input->post('c_time'),
                    'description' => "Payment Comfirmed Using " . $this->input->post('payment_mode') . " " . $this->input->post('description'),
                    'order_id' => $order_id
                );
                $this->db->insert('paypal_status', $productattr);

                if ($this->input->post('sendmail') == TRUE) {
                    try {
                        $this->Order_model->order_mail($order_key, "");
                    } catch (Exception $e) {
                        echo 'Message: ' . $e->getMessage();
                    }
                }
                redirect("Order/orderdetails/$order_key");
            }
        } else {
            redirect('/');
        }
        $this->load->view('Order/orderdetails_payment', $data);
    }

    public function orderdetails_shipping($order_key) {
        if ($this->user_type == 'Customer') {
            redirect('UserManager/not_granted');
        }
        $order_status = $this->input->get('status');
        $data['status'] = $order_status;

        $order_details = $this->Order_model->getOrderDetailsV2($order_key, 'key');
        $vendor_order_details = $this->Order_model->getVendorsOrder($order_key);
        $data['vendor_order'] = $vendor_order_details;

        if ($order_details) {
            $order_id = $order_details['order_data']->id;
            $data['ordersdetails'] = $order_details;
            $data['order_key'] = $order_key;
            $this->db->order_by('id', 'desc');
            $this->db->where('order_id', $order_id);
            $query = $this->db->get('user_order_status');
            $orderstatuslist = $query->result();
            $data['user_order_status'] = $orderstatuslist;

            if (isset($_POST['submit'])) {

                $tracking_no = $this->input->post('shipping_tracking_no');
                $tracking_link = $this->input->post('shipping_tracking_link');
                $shipping_company = $this->input->post('shipping_company');
                $shippping_contact_no = $this->input->post('shippping_contact_no');
                $shipping_time = $this->input->post('shipping_time');
                $shipping_time = $this->input->post('shipping_date');
                $total_weight = $this->input->post('total_weight');
                $weight_unit = $this->input->post('weight_unit');
                $description = $this->input->post('description');
                $customer_id = $this->input->post('customer_id');
                $shipping_country = $this->input->post('shipping_country');
                $description = $this->input->post('description');

                $shippingarray = array(
                    'shipping_tracking_no' => $this->input->post('shipping_tracking_no'),
                    'shipping_tracking_link' => $this->input->post('shipping_tracking_link'),
                    'shipping_company' => $this->input->post('shipping_company'),
                    'shippping_contact_no' => $this->input->post('shippping_contact_no'),
                    'shipping_time' => $this->input->post('shipping_time'),
                    'shipping_date' => $this->input->post('shipping_date'),
                    'total_weight' => $this->input->post('total_weight'),
                    'weight_unit' => $this->input->post('weight_unit'),
                    'description' => $this->input->post('description'),
                    'customer_id' => $this->input->post('customer_id'),
                    'shipping_country' => $this->input->post('shipping_country'),
                    'description' => $this->input->post('description'),
                    'order_id' => $order_id
                );
                $this->db->insert('shipping_order', $shippingarray);

                $remark = "Your order has been shipped.";
                $description1 = "Order Shipped By $shipping_company, Tracking No.: $tracking_no,<br/> Traking Link: <a href='$tracking_link' target='_blank'>$tracking_link</a>";

                $productattr = array(
                    'c_date' => date('Y-m-d'),
                    'c_time' => date('H:i:s'),
                    'status' => "Shipped",
                    'remark' => $remark,
                    'description' => $description1,
                    'order_id' => $order_id
                );
                $this->db->insert('user_order_status', $productattr);
                if ($this->input->post('sendmail') == TRUE) {
                    try {
                        $this->Order_model->order_mail($order_key, "");
                    } catch (Exception $e) {
                        echo 'Message: ' . $e->getMessage();
                    }
                }
                redirect("Order/orderdetails/$order_key");
            }
        } else {
            redirect('/');
        }
        $this->load->view('Order/orderdetails_shipping', $data);
    }

    function order_mail_send($order_id) {
        $this->Order_model->order_mail($order_id);
    }

    function order_mail_send_direct($order_key) {
        $this->Order_model->order_mail($order_key);
        redirect("Order/orderdetails/$order_key");
    }

    function order_pdf($order_id) {
        $this->Order_model->order_pdf($order_id);
    }

    function order_pdf_worker($order_id) {
        $this->Order_model->order_pdf_worker($order_id);
    }

    //remove order status
    public function remove_order_status($status_id, $orderkey) {
        $this->db->delete('user_order_status', array('id' => $status_id));
        redirect("Order/orderdetails/$orderkey");
    }

    //order list accroding to user type
    public function orderslist() {
        $data['exportdata'] = 'yes';
        $date1 = date('Y-m-') . "01";
        $date2 = date('Y-m-d');
      
        $this->load->view('Order/orderslist', $data);
        
    }

    //order list xls 
    public function orderslistxls($daterange) {
        $datelist = explode(" to ", urldecode($daterange));
        $date1 = $datelist[0];
        $date2 = $datelist[1];
        $daterange = $date1 . " to " . $date2;
        $data['daterange'] = $daterange;
        if ($this->user_type == 'Admin' || $this->user_type == 'Manager') {
            $this->db->order_by('id', 'desc');
            $this->db->where('order_date between "' . $date1 . '" and "' . $date2 . '"');
            $query = $this->db->get('user_order');
            $orderlist = $query->result();
            $orderslistr = [];
            foreach ($orderlist as $key => $value) {
                $this->db->order_by('id', 'desc');
                $this->db->where('order_id', $value->id);
                $query = $this->db->get('user_order_status');
                $status = $query->row();
                $value->status = $status ? $status->status : $value->status;
//                array_push($orderslistr, $value);

                $this->db->order_by('id', 'desc');
                $this->db->where('order_id', $value->id);
                $query = $this->db->get('cart');
                $cartdata = $query->result();
                $tempdata = array();
                foreach ($cartdata as $key1 => $value1) {
                    array_push($tempdata, $value1->item_name . "(" . $value1->quantity . ")");
                }

                $value->items = implode(", ", $tempdata);
                array_push($orderslistr, $value);
            }
            $data['orderslist'] = $orderslistr;
            $html = $this->load->view('Order/orderslist_xls', $data, TRUE);
        }
        if ($this->user_type == 'Vendor') {
            $this->db->order_by('vo.id', 'desc');
            $this->db->group_by('vo.id');
            $this->db->select('o.order_no, vo.id, o.name, o.email, o.address, o.city, o.contact_no, o.pincode,'
                    . 'o.state, vo.vendor_order_no, vo.total_price, vo.total_quantity, vo.c_date, vo.c_time');
            $this->db->join('user_order as o', 'o.id = vo.order_id', 'left');
            $this->db->where('vo.vendor_id', $this->user_id);
            $this->db->where('c_date between "' . $date1 . '" and "' . $date2 . '"');

            $this->db->from('vendor_order as vo');
            $query = $this->db->get();
            $orderlist = $query->result();
            $orderslistr = [];
            foreach ($orderlist as $key => $value) {
                $this->db->order_by('id', 'desc');
                $this->db->where('vendor_order_id', $value->id);
                $query = $this->db->get('vendor_order_status');
                $status = $query->row();
                $value->status = $status ? $status->status : $value->status;
                array_push($orderslistr, $value);
            }
            $data['orderslist'] = $orderslistr;
            $html = $this->load->view('Order/vendororderslist_xls', $data, TRUE);
        }
        $filename = 'orders_report_' . $daterange . ".xls";
        ob_clean();
        header("Content-Disposition: attachment; filename='$filename'");
        header("Content-Type: application/vnd.ms-excel");
        echo $html;
    }

    //vendor order status
    public function remove_vendor_order_status($status_id, $order_id) {
        $this->db->delete('vendor_order_status', array('id' => $status_id));
        redirect("Order/vendor_order_details/$order_id");
    }

    //order analisys
    public function orderAnalysis() {
        $data['exportdata'] = 'no';
        if ($this->user_type != 'Admin') {
            redirect('UserManager/not_granted');
        }
        $date1 = date('Y-m-') . "01";
        $date2 = date('Y-m-d');
        if (isset($_GET['daterange'])) {
            $daterange = $this->input->get('daterange');
            $datelist = explode(" to ", $daterange);
            $date1 = $datelist[0];
            $date2 = $datelist[1];
        }
        $daterange = $date1 . " to " . $date2;
        $data['daterange'] = $daterange;
        $this->db->order_by('id', 'desc');
        $this->db->where('order_date between "' . $date1 . '" and "' . $date2 . '"');
        $query = $this->db->get('user_order');
        $orderlist = $query->result_array();
        $orderslistr = [];
        $total_amount = 0;
        foreach ($orderlist as $key => $value) {
            $this->db->order_by('id', 'desc');
            $this->db->where('order_id', $value['id']);
            $total_amount += $value['total_price'];
            $query = $this->db->get('user_order_status');
            $status = $query->row();
            $value['status'] = $status ? $status->status : $value['status'];
            array_push($orderslistr, $value);
        }
        $data['total_amount'] = $total_amount;

        $this->db->order_by('id', 'desc');

        $query = $this->db->get('admin_users');
        $userlist = $query->result_array();

        $this->db->order_by('c.id', 'desc');
        $query = $this->db->from('cart as c');
        $this->db->join('user_order as uo', 'uo.id = c.order_id');
        $this->db->where('c.order_id > 0');
        $this->db->where('uo.order_date between "' . $date1 . '" and "' . $date2 . '"');
        $query = $this->db->get();
        $vendororderlist = $query->result_array();

        $data['vendor_orders'] = count($vendororderlist);
        $data['total_order'] = count($orderslistr);
        $data['total_users'] = count($userlist);
        $data['orderslist'] = $orderslistr;
        $this->load->library('JsonSorting', $orderslistr);
        $orderstatus = $this->jsonsorting->collect_data('status');
        $orderuser = $this->jsonsorting->collect_data('name');
        $orderdate = $this->jsonsorting->collect_data('order_date');
        $data['orderstatus'] = $orderstatus;
        $data['orderuser'] = $orderuser;
        $data['orderdate'] = $orderdate;

//order graph date
        $dategraphdata = $this->date_graph_data($date1, $date2, $orderdate);
        $data['order_date_graph'] = $dategraphdata;

        $amount_date = $this->jsonsorting->data_combination_quantity('total_price', 'order_date');

        $salesgraph = array();

        foreach ($dategraphdata as $key => $value) {
            $salesgraph[$key] = 0;
            if (isset($amount_date[$key])) {
                $salesgraph[$key] = $amount_date[$key];
            }
        }

        $data['salesgraph'] = $salesgraph;

        $this->load->view('Order/orderanalysis', $data);
    }

    public function selectPreviouseProfilesReport($desing_id, $ispdf_mail = 0) {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $desingdata = $this->Product_model->singleProfileDetails($desing_id);
        $data["desingdata"] = $desingdata;
        $html = $this->load->view('Email/desing_pdf', $data, true);
        $checkcode = 1;
        if ($checkcode == 0) {
            echo $html;
        } else {
            if ($ispdf_mail) {
                $this->load->library('pdf');
                $this->dompdf->set_option('isRemoteEnabled', true);
                
                // Load HTML content
                $this->dompdf->loadHtml($html);
                // (Optional) Setup the paper size and orientation
                $this->dompdf->setPaper('A4');
                // Render the HTML as PDF
                $this->dompdf->render();
                // Output the generated PDF (1 = download and 0 = preview)
                $this->dompdf->stream($desingdata["name"] . ".pdf", array("Attachment" => 1));
            } else {
                $emailsender = EMAIL_SENDER;
                $sendername = EMAIL_SENDER_NAME;
                $email_bcc = EMAIL_BCC;
                $this->email->from(EMAIL_SENDER, $sendername);
                $this->email->to($desingdata['user_data']["email"]);
//                $this->email->bcc($email_bcc);
                $subject = SITE_NAME . " Desing Profile: " . $desingdata["name"];
                $this->email->subject($subject);
                $this->email->message($html);
                $this->email->print_debugger();
                $result = $this->email->send();
            }
        }
    }

    public function selectPreviouseMeasurementProfilesReport($profile_id, $ispdf_mail = 0) {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $desingdata = $this->Product_model->selectPreviouseMeasurementProfilesReport($profile_id);
        $data["desingdata"] = $desingdata;
        $html = $this->load->view('Email/measurement_pdf', $data, true);
        $checkcode = REPORT_MODE;
        if ($checkcode == 0) {
            echo $html;
        } else {
            if ($ispdf_mail) {
                $this->load->library('pdf');
                $this->dompdf->set_option('isRemoteEnabled', true);
                // Load HTML content
                $this->dompdf->loadHtml($html);
                // (Optional) Setup the paper size and orientation
                $this->dompdf->setPaper('A4');
                // Render the HTML as PDF
                $this->dompdf->render();
                // Output the generated PDF (1 = download and 0 = preview)
                $this->dompdf->stream($desingdata["name"] . ".pdf", array("Attachment" => 1));
            } else {
                $emailsender = EMAIL_SENDER;
                $sendername = EMAIL_SENDER_NAME;
                $email_bcc = EMAIL_BCC;
                $this->email->from(EMAIL_SENDER, $sendername);
                $this->email->to($desingdata['user_data']["email"]);
//                $this->email->to($email_bcc);
                $subject = SITE_NAME . " Measurement Profile: " . $desingdata["name"];
                $this->email->subject($subject);
                $this->email->message($html);
                $this->email->print_debugger();
                $result = $this->email->send();
            }
        }
    }

    public function orderRefundFnction($order_key) {
        $order_status = $this->input->get('status');
        $data['status'] = $order_status;
        if ($this->user_type == 'Customer') {
            redirect('UserManager/not_granted');
        }




        $order_details = $this->Order_model->getOrderDetailsV2($order_key, 'key');
        $vendor_order_details = $this->Order_model->getVendorsOrder($order_key);
        $data['vendor_order'] = $vendor_order_details;
        if ($order_details) {
            $order_id = $order_details['order_data']->id;

            $data['ordersdetails'] = $order_details;
            $data['order_key'] = $order_key;
            $this->db->order_by('id', 'desc');
            $this->db->where('order_id', $order_id);
            $query = $this->db->get('user_order_status');
            $orderstatuslist = $query->result();

            $currentstatus = $orderstatuslist ? $orderstatuslist[0]->status : array();

            if ($order_status) {
                
            } else {
                //redirecttion
                switch ($currentstatus) {
                    case "Order Confirmed":
                        redirect("Order/orderdetails_payments/$order_key");
                        break;

                    case "Order Verifiaction":
                        redirect("Order/orderdetails_payments/$order_key?status=Pending");
                        break;

                    case "Order Enquiry":
                        redirect("Order/orderdetails_enquiry/$order_key");
                        break;
                    case "Payment Confirmed":
                        redirect("Order/orderdetails_shipping/$order_key");
                        break;
                    case "Shipped":
                        if ($order_status == 'Delivered') {
                            
                        } else {
                            redirect("Order/orderdetails/$order_key/?status=Delivered");
                        }
                        break;
                    default:

                        echo "";
                }
            }
            //end of redirection



            $data['user_order_status'] = $orderstatuslist;
            if (isset($_POST['submit'])) {
                $productattr = array(
                    'c_date' => date('Y-m-d'),
                    'c_time' => date('H:i:s'),
                    'status' => $this->input->post('status'),
                    'remark' => $this->input->post('remark'),
                    'description' => $this->input->post('description'),
                    'order_id' => $order_id
                );
                $this->db->insert('user_order_status', $productattr);
                if ($this->input->post('sendmail') == TRUE) {
                    try {
                        $this->Order_model->order_mail($order_key, "");
                    } catch (Exception $e) {
                        //echo 'Message: ' . $e->getMessage();
                    }
                }
                redirect("Order/orderdetails/$order_key");
            }
        } else {
            redirect('/');
        }
        $this->load->view('Order/orderdetailsrefund', $data);
    }

    function postureInsert() {
        
    }

}

?>
