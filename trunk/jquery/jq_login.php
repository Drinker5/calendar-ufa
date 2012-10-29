<?php
	if(isset($_POST['email']) && isset($_POST['passw']))
    {
        if (is_email($_POST['email']))
        {
    		$result = $USER->AuthUser($_POST['email'],$_POST['passw']);
            if($result === true) 
                echo 'ok';
            else
                echo $result;
        }
        else
            echo "Некорректный email";
	}
    else
        echo "Поля не должны быть пустыми";


 	
?>