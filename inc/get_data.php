<?php

    include('../connect.php');

    //print_r($_POST);

        $Type = isset($_POST['type']) ? $_POST['type'] : 'All';
        if ( isset($_POST['dfrom']) && $_POST['dfrom'] !== '' ) {
            $From = date('Y-m-d', strtotime($_POST['dfrom']));
        }
        if ( isset($_POST['dto']) && $_POST['dto'] !== '' ) {
            $To = date('Y-m-d', strtotime($_POST['dto']));
        }

        $table = "names";

        $column_order  = array('first_name', 'last_name', 'email', 'birthdate', 'gender');
        $column_search = array('first_name', 'last_name', 'email', 'birthdate', 'gender');

        $mySQL = "SELECT * FROM names ";
        $w = 0;
        if ($From) {
            if ($w == 0) {
                $mySQL .= " WHERE ";
            } else {
               $mySQL .= " AND ";
           }
           $mySQL .= " birthdate >= '" .$From. "'";
            $w = 1;
        }
        if ($To) {
            if ($w == 0) {
                $mySQL .= " WHERE ";
            } else {
               $mySQL .= " AND ";
           }
           $mySQL .= " birthdate <= '" .$To. "'";
            $w = 1;
        }

        if ($Type !== 'All') {
            if ($w == 0) {
                $mySQL .= " WHERE ";
            } else {
                $mySQL .= " AND ";
            }
            $mySQL .= " gender = '".$Type."'";
            $w = 1;
        }

        /* Counting All Records in Table */
        $AllCount = "SELECT COUNT(*) AS Total FROM names";
        if( !$totalRecords = $db->query($AllCount)->fetch_object()->Total ){

            die('There was an error runnung the query [' .$db->error. ']');

        }

        /* Counting Number of Records Selected */
        if(!$filtered = $db->query($mySQL)){

            die('There was an error runnung the query [' .$db->error. ']');

        }
        $filteredCount = $filtered->num_rows;


        /* Preparing SQL Search String */
        $i = 0;
        $mySQLSearch = '';
        foreach ($column_search as $item)
        {
            if ($_POST['search']['value'])
            {
                if ($i==0)
                {
                    if ($w == 0) {
                        $mySQL .= " WHERE ";
                    } else {
                        $mySQL .= " AND ";
                    }

                    $mySQLSearch .= $item .' LIKE "%' .$_POST['search']['value']. '%" ';
                    $w = 1;

                }
                else
                {
                    $mySQLSearch .= ' OR '.$item .' LIKE "%' .$_POST['search']['value']. '%" ';
                }

            }
            $i++;
        }

        /* Preparing SQL Order String */
        $mySQLOrder = '';
        if(isset($_POST['order']))
        {
            $mySQLOrder = " ORDER BY " .$column_order[$_POST['order']['0']['column']]. " " .strtoupper($_POST['order']['0']['dir']). " ";
        }
        else if(isset($order))
        {
            $mySQLOrder = " ORDER BY ".key($order). " " .strtoupper($order[key($order)]). " ";
        }

        /* Creating New SQL Statement with Search and Order Included */
        $mySQLCommand = $mySQL .$mySQLSearch .$mySQLOrder;

        /* Adding Pagination to THe SQL Command */
        if ($_POST['length'] != -1)
        {
            $mySQLLimit = ' LIMIT '.$_POST['start']. ', '. $_POST['length'];
            $mySQLCommand .= $mySQLLimit;
        }


        if(!$result = $db->query($mySQLCommand)){

            die('There was an error runnung the query [' .$db->error. ']');

        }

        $data = array();
        $no = $_POST['start'];

        if ($result) {

            while($row = $result->fetch_assoc()) {

                $no++;
                $r = array();

                $r[] = $row['first_name'];
                $r[] = $row['last_name'];
                $r[] = $row['email'];
                $r[] = $row['birthdate'];
                $r[] = $row['gender'];

                $data[] = $r;

            }

            $output = array(
	            "draw" => $_POST['draw'],
	            "recordsTotal" => (int)$totalRecords,
	            "recordsFiltered" => (int)$filteredCount,
                "sqlCommand" => $mySQLCommand,
	            "data" => $data,
	        );

        } else {
            $output = array("data" => array());
        }

        //output to json format
        echo json_encode($output);

//    }

    die(); // Will return to ajax control here
