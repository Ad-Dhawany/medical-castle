<?php

    function lang($word){
        static $lang = array (
            'HOME'           => 'HOME',
            'DASHBOARD'      => 'Dashboard',
            'CATEGORIES'     => 'Categories',
            'ITEM'           => 'صنف',
            'COSTUMER'       => 'Costumer',
            'LOG'            => 'Log',
            'LOGIN'          => 'Login',
            'EDIT_PROFILE'   => 'Edit profile',
            'SETTINGS'       => 'Settings',
            'LOGOUT'         => 'Logout',
            'INFORMATION'    => 'Information',
            'ID'             => 'ID',
            'USERNAME'       => 'Username',
            'FULLNAME'       => 'Full-name',
            'PASSWORD'       => 'Password',
            'REPEAT'         => 'Repeat',
            'EMAIL'          => 'E-mail',
            'EDIT'           => 'Edit',
            'ADD'            => 'Add',
            'NEW'            => 'جديد',
            'DELETE'         => 'Delete',
            'MEMBER'         => 'عضو',
            'SAVE'           => 'Save',
            'MANAGE'         => 'Manage',
            'CONTROL'        => 'Control',
            'PERMISSION'     => 'Permission',
            'ADMIN'          => 'Admin',
            'RESPONSIBLE'    => 'Responsible',
            'EMPLOYEE'       => 'Employee',
            'REGISTERED'     => 'Registered',
            'DATE'           => 'Date',
            'ADDITION'       => 'Addition',
            'PAGE'           => 'Page',
            'TOTAL'          => 'إجمالي',
            'PENDING'        => 'Pending',
            'COMMENT'        => 'Comment',
            'LATEST'         => 'Latest',
            'USER'           => 'User',
            'ACTIVATE'       => 'Activate',
            'CATEGORY'       => 'Category',
            'NAME'           => 'Name',
            'DESCRIPTION'    => 'Description',
            'ORDER'          => 'Order',
            'VISIBLE'        => 'Visible',
            'YES'            => 'Yes',
            'NO'             => 'No',
            'IS'             => 'Is',
            '?'              => '?',
            'ALLOW'          => 'Allow',
            'ADS'            => 'ads',
            'ASC'            => 'Asc',
            'DESC'           => 'Desc',
            'BACK'           => 'Back',
        );
        return $lang[$word];
    }
    function langs($words){
        return lang($words).'';
    }
    
    function langes($wordes){
        return lang($wordes).'';
    }
    function langing($wording){
        return lang($wording).'';
    }
    function langTH($wordTH){
        return 'The '.lang($wordTH);
    }
    function langTHs($wordTHs){
        return 'The '.langs($wordTHs);
    }
    function langTXT($text){
        static $langTXT = array(
            "TOT USE"       => "Total Users",
            "PEN USE"       => "Pending Users",
            "TOT ITE"       => "Total Items",
            "TOT ITE"       => "Total Items",
            "TOT COS"       => "Total Costumers",
            "LA RE CO"      => "Latest Registered Costumers",
            "LAT ITE"       => "Latest Items",
            "MEM MAN"       => "Member Manegement",
            "REG DAT"       => "Registered Date",
        );
        return $langTXT[$text];
    }
?>